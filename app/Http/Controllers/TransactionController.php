<?php
namespace App\Http\Controllers;

use App\Models\AccountingEntry;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'belum_lunas');

        $query = Transaction::with(['customer', 'package', 'payments'])->withCount('payments');

        if ($tab === 'refund') {
            $query->refund();
        } elseif ($tab === 'lunas') {
            $query->lunas();
        } else {
            $query->belumLunas();
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($paymentType = $request->input('payment_type')) {
            $query->where('payment_type', $paymentType);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'belum_lunas' => Transaction::belumLunas()->count(),
            'lunas' => Transaction::lunas()->count(),
            'refund' => Transaction::refund()->count(),
        ];

        return view('transactions.index', compact('transactions', 'tab', 'counts'))->with('pageTitle', 'Transactions');
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $packages = Package::where('status', 'active')->orderBy('name')->get();
        return view('transactions.create', compact('customers', 'packages'))->with('pageTitle', 'New Transaction');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
            'departure_date' => 'required|date',
            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'payment_type' => 'required|in:cash,cicilan',
            'payment_method' => 'nullable|required_if:payment_type,cash|in:cash,transfer,credit_card,debit_card,ewallet',
            'notes' => 'nullable|string',
            'first_payment_amount' => 'nullable|numeric|min:1|required_if:payment_type,cicilan',
            'first_payment_date' => 'nullable|date|required_if:payment_type,cicilan',
            'first_payment_method' => 'nullable|in:cash,transfer,credit_card,debit_card,ewallet|required_if:payment_type,cicilan',
            'first_payment_evidence' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,pdf|required_if:payment_type,cicilan',
        ]);
        $validated['user_id'] = auth()->id();
        $validated['transaction_number'] = Transaction::generateNumber();
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['grand_total'] = $validated['total_price'] - $validated['discount'];
        $validated['payment_status'] = 'unpaid';

        $transactionData = collect($validated)->except([
            'first_payment_amount', 'first_payment_date', 'first_payment_method', 'first_payment_evidence'
        ])->toArray();

        $transaction = Transaction::create($transactionData);

        if ($validated['payment_type'] === 'cicilan') {
            $this->createFirstPayment($transaction, $validated, $request);
        }

        ActivityLogger::log('created', 'Transaksi ' . $transaction->transaction_number . ' berhasil dibuat.', $transaction);

        // Create accounting entry for booking income
        AccountingEntry::create([
            'entry_number' => AccountingEntry::generateNumber('income'),
            'type' => 'income',
            'category' => 'booking_income',
            'description' => 'Booking: ' . ($transaction->package->name ?? 'Package') . ' - ' . ($transaction->customer->name ?? 'Customer'),
            'amount' => $transaction->grand_total,
            'date' => now(),
            'reference_type' => Transaction::class,
            'reference_id' => $transaction->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    public function edit(Transaction $transaction)
    {
        $customers = Customer::orderBy('name')->get();
        $packages = Package::where('status', 'active')->orderBy('name')->get();
        return view('transactions.edit', compact('transaction', 'customers', 'packages'))->with('pageTitle', 'Edit Transaction');
    }

    public function update(Request $request, Transaction $transaction)
    {
        $hasNoPayments = $transaction->payments()->count() === 0;
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
            'departure_date' => 'required|date',
            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'required|in:unpaid,partial,paid',
            'payment_type' => 'required|in:cash,cicilan',
            'payment_method' => 'nullable|required_if:payment_type,cash|in:cash,transfer,credit_card,debit_card,ewallet',
            'notes' => 'nullable|string',
            'first_payment_amount' => 'nullable|numeric|min:1',
            'first_payment_date' => 'nullable|date',
            'first_payment_method' => 'nullable|in:cash,transfer,credit_card,debit_card,ewallet',
            'first_payment_evidence' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,pdf',
        ]);

        if ($validated['payment_type'] === 'cicilan' && $hasNoPayments) {
            if (empty($validated['first_payment_amount']) || empty($validated['first_payment_date']) || empty($validated['first_payment_method'])) {
                return back()->withErrors(['first_payment_amount' => __('app.first_payment_required')])->withInput();
            }
        }

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['grand_total'] = $validated['total_price'] - $validated['discount'];

        $updateData = collect($validated)->except([
            'first_payment_amount', 'first_payment_date', 'first_payment_method', 'first_payment_evidence'
        ])->toArray();

        $transaction->update($updateData);

        if ($validated['payment_type'] === 'cicilan' && $hasNoPayments) {
            $this->createFirstPayment($transaction, $validated, $request);
        }

        ActivityLogger::log('updated', 'Transaksi ' . $transaction->transaction_number . ' berhasil diubah.', $transaction);
        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function storePayment(Request $request, Transaction $transaction)
    {
        $remaining = $transaction->remainingAmount();

        $validated = $request->validate([
            'amount' => "required|numeric|min:1|max:{$remaining}",
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer,credit_card,debit_card,ewallet',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['transaction_id'] = $transaction->id;
        $validated['user_id'] = auth()->id();

        Payment::create($validated);

        AccountingEntry::create([
            'entry_number' => AccountingEntry::generateNumber('income'),
            'type' => 'income',
            'category' => 'booking_income',
            'description' => 'Pembayaran: ' . $transaction->transaction_number . ' - ' . ($transaction->customer->name ?? 'Customer'),
            'amount' => $validated['amount'],
            'date' => $validated['payment_date'],
            'reference_type' => Transaction::class,
            'reference_id' => $transaction->id,
            'user_id' => auth()->id(),
        ]);

        ActivityLogger::log(
            'payment',
            'Pembayaran Rp ' . number_format($validated['amount'], 0, ',', '.') . ' untuk transaksi ' . $transaction->transaction_number . ' berhasil dicatat.',
            $transaction
        );

        return redirect()->route('transactions.index')->with('success', 'Payment recorded successfully.');
    }

    private function createFirstPayment(Transaction $transaction, array $validated, Request $request): void
    {
        $proofPath = null;
        if ($request->hasFile('first_payment_evidence')) {
            $file = $request->file('first_payment_evidence');
            $fileName = time() . '_payment_' . $file->getClientOriginalName();
            $file->storeAs('public/payment_proofs', $fileName);
            $proofPath = 'payment_proofs/' . $fileName;
        }

        Payment::create([
            'transaction_id' => $transaction->id,
            'amount' => $validated['first_payment_amount'],
            'payment_method' => $validated['first_payment_method'],
            'payment_date' => $validated['first_payment_date'],
            'proof' => $proofPath,
            'user_id' => auth()->id(),
        ]);

        AccountingEntry::create([
            'entry_number' => AccountingEntry::generateNumber('income'),
            'type' => 'income',
            'category' => 'booking_income',
            'description' => 'DP Pembayaran: ' . $transaction->transaction_number,
            'amount' => $validated['first_payment_amount'],
            'date' => $validated['first_payment_date'],
            'reference_type' => Transaction::class,
            'reference_id' => $transaction->id,
            'user_id' => auth()->id(),
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $number = $transaction->transaction_number;
        $transaction->delete();
        ActivityLogger::log('deleted', 'Transaksi ' . $number . ' berhasil dihapus.');
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    public function refund(Request $request, Transaction $transaction)
    {
        $request->validate([
            'refund_reason' => 'required|string|max:500',
        ]);

        $transaction->update([
            'status' => 'refund',
            'payment_status' => 'refunded',
            'refund_reason' => $request->input('refund_reason'),
            'refunded_at' => now(),
        ]);

        ActivityLogger::log('refunded', 'Transaksi ' . $transaction->transaction_number . ' berhasil dikembalikan. Alasan: ' . $request->input('refund_reason'), $transaction);

        // Create accounting entry for refund (expense)
        AccountingEntry::create([
            'entry_number' => AccountingEntry::generateNumber('expense'),
            'type' => 'expense',
            'category' => 'refund',
            'description' => 'Refund: ' . ($transaction->package->name ?? 'Package') . ' - ' . ($transaction->customer->name ?? 'Customer') . ' | Reason: ' . $request->input('refund_reason'),
            'amount' => $transaction->grand_total,
            'date' => now(),
            'reference_type' => Transaction::class,
            'reference_id' => $transaction->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('transactions.index', ['tab' => 'refund'])
                         ->with('success', 'Transaction has been refunded successfully.');
    }

    public function exportExcel(Request $request)
    {
        $query = Transaction::with(['customer', 'package']);
        if ($status = $request->input('status')) $query->where('status', $status);
        if ($from = $request->input('from')) $query->where('created_at', '>=', $from);
        if ($to = $request->input('to')) $query->where('created_at', '<=', $to);
        $transactions = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transactions_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Transaction Number', 'Customer', 'Package', 'Type', 'Payment Type', 'Payment Method', 'Total Price', 'Discount', 'Grand Total', 'Status', 'Payment Status', 'Date']);
            $no = 1;
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $no++, $t->transaction_number, $t->customer->name ?? '-',
                    $t->package->name ?? '-', $t->package->type ?? '-',
                    $t->payment_type_label, $t->payment_method_label,
                    $t->total_price, $t->discount, $t->grand_total,
                    $t->status, $t->payment_status, $t->created_at->format('Y-m-d'),
        ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $query = Transaction::with(['customer', 'package']);
        if ($status = $request->input('status')) $query->where('status', $status);
        $transactions = $query->latest()->get();

        $pdf = Pdf::loadView('transactions.pdf', compact('transactions'));
        return $pdf->download('transactions_' . now()->format('Y-m-d') . '.pdf');
    }

    public function invoice(Transaction $transaction)
    {
        $transaction->load(['customer', 'package', 'payments']);
        $pdf = Pdf::loadView('transactions.invoice', compact('transaction'));
        return $pdf->download('Invoice-' . $transaction->transaction_number . '.pdf');
    }

    public function invoicesBulk(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:transactions,id'])['ids'];

        $transactions = Transaction::with(['customer', 'package', 'payments'])
            ->whereIn('id', $ids)
            ->get();

        if ($transactions->isEmpty()) {
            return back()->with('error', 'No transactions selected.');
        }

        $zip = new \ZipArchive();
        $zipFileName = 'invoices-' . now()->format('Y-m-d-Hi') . '.zip';
        $zipPath = sys_get_temp_dir() . '/' . $zipFileName;

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Failed to create zip file.');
        }

        foreach ($transactions as $transaction) {
            $pdf = Pdf::loadView('transactions.invoice', compact('transaction'));
            $zip->addFromString('Invoice-' . $transaction->transaction_number . '.pdf', $pdf->output());
        }

        $zip->close();

        ActivityLogger::log('exported', count($ids) . ' invoice berhasil diunduh massal.', properties: ['transaction_ids' => $ids]);

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}
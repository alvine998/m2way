<?php
namespace App\Http\Controllers;

use App\Models\AccountingEntry;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountingEntry::query();
        if ($type = $request->input('type')) $query->where('type', $type);
        if ($category = $request->input('category')) $query->where('category', $category);
        if ($search = $request->input('search')) {
            $query->where('description', 'like', "%{$search}%");
        }
        $accountings = $query->latest('date')->paginate(15)->withQueryString();
        $totalIncome = AccountingEntry::where('type', 'income')->sum('amount');
        $totalExpense = AccountingEntry::where('type', 'expense')->sum('amount');

        return view('accounting.index', compact('accountings', 'totalIncome', 'totalExpense'))
            ->with('pageTitle', 'Accounting');
    }

    public function create()
    {
        return view('accounting.create')->with('pageTitle', 'Add Entry');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'evidence' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,pdf',
        ]);
        $validated['user_id'] = auth()->id();

        $evidenceData = collect($validated)->except(['evidence'])->toArray();
        if ($request->hasFile('evidence')) {
            $evidenceData['attachments'] = $this->handleEvidence($request->file('evidence')) ?: null;
        }

        $entry = AccountingEntry::create($evidenceData);
        ActivityLogger::log('created', 'Entri akuntansi ' . $entry->entry_number . ' berhasil dibuat.', $entry);
        return redirect()->route('accounting.index')->with('success', 'Entry created successfully.');
    }

    public function edit(AccountingEntry $accounting)
    {
        return view('accounting.edit', compact('accounting'))->with('pageTitle', 'Edit Entry');
    }

    public function update(Request $request, AccountingEntry $accounting)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'evidence' => ['nullable','file','max:20480','mimes:jpg,jpeg,png,pdf'],
        ]);
        $updateData = collect($validated)->except(['evidence'])->toArray();

        if ($request->hasFile('evidence')) {
            $this->deleteEvidenceFiles($accounting);
            $updateData['attachments'] = $this->handleEvidence($request->file('evidence')) ?: null;
        } elseif ($validated['type'] === 'income') {
            $this->deleteEvidenceFiles($accounting);
            $updateData['attachments'] = null;
        }

        $accounting->update($updateData);
        ActivityLogger::log('updated', 'Entri akuntansi ' . $accounting->entry_number . ' berhasil diubah.', $accounting);
        return redirect()->route('accounting.index')->with('success', 'Entry updated successfully.');
    }

    public function destroy(AccountingEntry $accounting)
    {
        $number = $accounting->entry_number;
        $accounting->delete();
        ActivityLogger::log('deleted', 'Entri akuntansi ' . $number . ' berhasil dihapus.');
        return redirect()->route('accounting.index')->with('success', 'Entry deleted successfully.');
    }

    private function handleEvidence($file): ?array
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'accounting_attachments/' . $fileName;

        $file->storeAs('public/accounting_attachments', $fileName);

        return [[
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]];
    }

    private function deleteEvidenceFiles(AccountingEntry $accounting): void
    {
        $attachments = $accounting->attachments ?? [];
        foreach ($attachments as $att) {
            $path = storage_path('app/public/' . ($att['file_path'] ?? ''));
            if (!empty($att['file_path']) && file_exists($path)) {
                unlink($path);
            }
        }
    }
}

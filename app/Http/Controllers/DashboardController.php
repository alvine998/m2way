<?php
namespace App\Http\Controllers;

use App\Models\AccountingEntry;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::where('payment_status', 'paid')->sum('grand_total');
        $totalPackages = Package::count();
        $pendingPayments = Transaction::where('payment_status', '!=', 'paid')->sum('grand_total');

        $recentTransactions = Transaction::with(['customer', 'package'])
            ->latest()->take(5)->get();

        $monthlyRevenue = Transaction::where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->get(['created_at', 'grand_total'])
            ->groupBy(fn(Transaction $transaction) => $transaction->created_at->month)
            ->map(fn($transactions) => $transactions->sum('grand_total'))
            ->toArray();
        $monthlyRevenue = $this->monthlyTotals($monthlyRevenue);

        $monthlyExpenses = AccountingEntry::where('type', 'expense')
            ->whereYear('created_at', now()->year)
            ->get(['created_at', 'amount'])
            ->groupBy(fn(AccountingEntry $entry) => $entry->created_at->month)
            ->map(fn($entries) => $entries->sum('amount'))
            ->toArray();
        $monthlyExpenses = $this->monthlyTotals($monthlyExpenses);

        $packageDistribution = Package::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $hajjCount = $packageDistribution['hajj'] ?? 0;
        $umrohCount = $packageDistribution['umroh'] ?? 0;

        return view('dashboard.index', compact(
            'totalCustomers', 'totalTransactions', 'totalRevenue', 'totalPackages',
            'pendingPayments', 'recentTransactions', 'monthlyRevenue', 'monthlyExpenses',
            'packageDistribution', 'hajjCount', 'umrohCount'
        ))->with('pageTitle', 'Dashboard');
    }

    private function monthlyTotals(array $totalsByMonth): array
    {
        $totals = array_fill(0, 12, 0);

        foreach ($totalsByMonth as $month => $total) {
            $index = (int) $month - 1;

            if ($index >= 0 && $index < 12) {
                $totals[$index] = (float) $total;
            }
        }

        return $totals;
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\AccountingEntry;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->format('Y'));

        $totalIncome = AccountingEntry::where('type', 'income')
            ->whereYear('date', $year)->sum('amount');
        $totalExpenses = AccountingEntry::where('type', 'expense')
            ->whereYear('date', $year)->sum('amount');
        $profit = $totalIncome - $totalExpenses;

        $monthlyData = AccountingEntry::query()
            ->whereYear('date', $year)
            ->get(['type', 'date', 'amount'])
            ->groupBy('type')
            ->mapWithKeys(fn($items, $type) => [
                $type => $items
                    ->groupBy(fn(AccountingEntry $entry) => $entry->date->month)
                    ->map(fn($entries) => $entries->sum('amount'))
                    ->toArray(),
            ]);
        $monthlyIncome = $this->monthlyTotals($monthlyData->get('income', []));
        $monthlyExpenses = $this->monthlyTotals($monthlyData->get('expense', []));

        $recentEntries = AccountingEntry::latest('date')->take(10)->get();

        return view('finance.index', compact(
            'totalIncome', 'totalExpenses', 'profit', 'monthlyIncome', 'monthlyExpenses', 'recentEntries', 'year'
        ))->with('pageTitle', 'Finance Overview');
    }

    public function exportPdf(Request $request)
    {
        $year = $request->input('year', now()->format('Y'));
        $month = $request->input('month');

        $query = AccountingEntry::whereYear('date', $year);
        if ($month) {
            $query->whereMonth('date', $month);
        }
        $entries = $query->orderBy('date')->get();

        $totalIncome = $entries->where('type', 'income')->sum('amount');
        $totalExpenses = $entries->where('type', 'expense')->sum('amount');
        $profit = $totalIncome - $totalExpenses;

        $monthName = $month ? \Carbon\Carbon::create()->month($month)->format('F') : 'Full Year';
        $title = "Finance Report - {$monthName} {$year}";

        $pdf = Pdf::loadView('finance.report-pdf', compact(
            'entries', 'totalIncome', 'totalExpenses', 'profit', 'year', 'month', 'monthName', 'title'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("finance-report-{$year}" . ($month ? "-{$month}" : "") . ".pdf");
    }

    public function exportExcel(Request $request)
    {
        $year = $request->input('year', now()->format('Y'));
        $month = $request->input('month');

        $query = AccountingEntry::whereYear('date', $year);
        if ($month) {
            $query->whereMonth('date', $month);
        }
        $entries = $query->orderBy('date')->get();

        $totalIncome = $entries->where('type', 'income')->sum('amount');
        $totalExpenses = $entries->where('type', 'expense')->sum('amount');
        $profit = $totalIncome - $totalExpenses;

        $monthName = $month ? \Carbon\Carbon::create()->month($month)->format('F') : 'Full Year';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"finance-report-{$year}" . ($month ? "-{$month}" : "") . ".csv\"",
        ];

        $callback = function () use ($entries, $totalIncome, $totalExpenses, $profit, $year, $monthName) {
            $file = fopen('php://output', 'w');

            // Header info
            fputcsv($file, ['M2Way Travel - Finance Report']);
            fputcsv($file, ['Period', "{$monthName} {$year}"]);
            fputcsv($file, ['Generated', now()->format('d M Y H:i')]);
            fputcsv($file, []);

            // Summary
            fputcsv($file, ['Summary']);
            fputcsv($file, ['Total Income', $totalIncome]);
            fputcsv($file, ['Total Expenses', $totalExpenses]);
            fputcsv($file, ['Net Profit', $profit]);
            fputcsv($file, []);

            // Table header
            fputcsv($file, ['Entry Number', 'Date', 'Type', 'Category', 'Description', 'Amount']);

            // Table rows
            foreach ($entries as $entry) {
                fputcsv($file, [
                    $entry->entry_number,
                    $entry->date->format('d/m/Y'),
                    ucfirst($entry->type),
                    ucfirst(str_replace('_', ' ', $entry->category)),
                    $entry->description,
                    $entry->amount,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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

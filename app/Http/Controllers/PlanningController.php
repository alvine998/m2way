<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PlannedSchedule;
use App\Models\Transaction;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    public function timeline()
    {
        $packages = Package::withCount('transactions')
            ->whereNotNull('departure_date')
            ->orderBy('departure_date')
            ->limit(18)
            ->get();

        $upcomingTransactions = Transaction::with(['customer', 'package'])
            ->whereNotNull('departure_date')
            ->orderBy('departure_date')
            ->limit(8)
            ->get();

        $plannedSchedules = PlannedSchedule::with('package')
            ->where('planned_date', '>=', now()->subMonths(3))
            ->orderBy('planned_date')
            ->limit(18)
            ->get();

        return view('planning.timeline', compact('packages', 'upcomingTransactions', 'plannedSchedules'))
            ->with('pageTitle', 'Planning Timeline');
    }

    public function calendar(Request $request)
    {
        $month = $this->selectedMonth($request->input('month'));
        $startOfMonth = $month->startOfMonth();
        $endOfMonth = $month->endOfMonth();
        $calendarStart = $startOfMonth->startOfWeek(CarbonInterface::MONDAY);
        $calendarEnd = $endOfMonth->endOfWeek(CarbonInterface::SUNDAY);

        $packages = Package::withCount('transactions')
            ->whereBetween('departure_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('departure_date')
            ->get();

        $transactions = Transaction::with(['customer', 'package'])
            ->whereBetween('departure_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('departure_date')
            ->get();

        $eventsByDate = collect();

        foreach ($packages as $package) {
            $eventsByDate->push([
                'date' => $package->departure_date->toDateString(),
                'type' => 'package',
                'title' => $package->name,
                'subtitle' => ucfirst($package->type) . ' package',
                'status' => $package->status,
                'id' => $package->id,
                'route' => route('packages.edit', $package),
            ]);
        }

        foreach ($transactions as $transaction) {
            $eventsByDate->push([
                'date' => $transaction->departure_date->toDateString(),
                'type' => 'booking',
                'title' => $transaction->customer->name ?? 'Customer booking',
                'subtitle' => $transaction->package->name ?? $transaction->transaction_number,
                'status' => $transaction->status,
                'id' => $transaction->id,
                'route' => route('transactions.edit', $transaction),
            ]);
        }

        $eventsByDate = $eventsByDate->groupBy('date');

        $calendarDays = collect();
        for ($day = $calendarStart; $day->lte($calendarEnd); $day = $day->addDay()) {
            $calendarDays->push($day);
        }

        return view('planning.calendar', [
            'calendarDays' => $calendarDays,
            'eventsByDate' => $eventsByDate,
            'month' => $month,
            'nextMonth' => $month->addMonth()->format('Y-m'),
            'previousMonth' => $month->subMonth()->format('Y-m'),
            'packagesCount' => $packages->count(),
            'bookingsCount' => $transactions->count(),
        ])->with('pageTitle', 'Planning Calendar');
    }

    private function selectedMonth(?string $month): CarbonImmutable
    {
        if (! $month) {
            return CarbonImmutable::now();
        }

        try {
            return CarbonImmutable::createFromFormat('Y-m', $month) ?: CarbonImmutable::now();
        } catch (\Throwable) {
            return CarbonImmutable::now();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PlannedSchedule;
use App\Services\ActivityLogger;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class PlanScheduleController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', CarbonImmutable::now()->format('Y-m'));

        try {
            $selectedMonth = CarbonImmutable::createFromFormat('Y-m', $month);
        } catch (\Throwable) {
            $selectedMonth = CarbonImmutable::now();
        }

        $schedules = PlannedSchedule::with('package')
            ->whereMonth('planned_date', $selectedMonth->month)
            ->whereYear('planned_date', $selectedMonth->year)
            ->orderBy('planned_date')
            ->get();

        return view('planning.schedule', [
            'schedules' => $schedules,
            'selectedMonth' => $selectedMonth,
            'nextMonth' => $selectedMonth->addMonth()->format('Y-m'),
            'previousMonth' => $selectedMonth->subMonth()->format('Y-m'),
        ])->with('pageTitle', __('app.plan_schedule'));
    }

    public function create()
    {
        $packages = Package::orderBy('name')->get();
        return view('planning.schedule-create', compact('packages'))
            ->with('pageTitle', __('app.add_schedule'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'planned_date' => 'required|date',
            'target_pax' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $schedule = PlannedSchedule::create($validated);
        ActivityLogger::log('created', 'Jadwal rencana pada ' . $schedule->planned_date->format('d M Y') . ' berhasil dibuat.', $schedule);

        return redirect()->route('plan-schedules.index')
            ->with('success', __('app.created_successfully', ['item' => __('app.schedule')]));
    }

    public function edit(PlannedSchedule $planSchedule)
    {
        $packages = Package::orderBy('name')->get();
        return view('planning.schedule-edit', ['planSchedule' => $planSchedule, 'packages' => $packages])
            ->with('pageTitle', __('app.edit_schedule'));
    }

    public function update(Request $request, PlannedSchedule $planSchedule)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'planned_date' => 'required|date',
            'target_pax' => 'required|integer|min:1',
            'booked_pax' => 'nullable|integer|min:0',
            'status' => 'required|in:planned,active,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $planSchedule->update($validated);

        ActivityLogger::log('updated', 'Jadwal rencana pada ' . $planSchedule->planned_date->format('d M Y') . ' berhasil diubah.', $planSchedule);

        return redirect()->route('plan-schedules.index')
            ->with('success', __('app.updated_successfully', ['item' => __('app.schedule')]));
    }

    public function destroy(PlannedSchedule $planSchedule)
    {
        $date = $planSchedule->planned_date->format('d M Y');
        $planSchedule->delete();
        ActivityLogger::log('deleted', 'Jadwal rencana pada ' . $date . ' berhasil dihapus.');
        return redirect()->route('plan-schedules.index')
            ->with('success', __('app.deleted_successfully', ['item' => __('app.schedule')]));
    }
}
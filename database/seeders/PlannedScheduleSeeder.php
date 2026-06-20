<?php

namespace Database\Seeders;

use App\Models\PlannedSchedule;
use Illuminate\Database\Seeder;

class PlannedScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            ['package_id' => 2, 'planned_date' => now()->addMonth()->toDateString(), 'target_pax' => 20, 'booked_pax' => 8, 'status' => 'active', 'notes' => 'Keberangkatan Umroh VIP'],
            ['package_id' => 1, 'planned_date' => now()->addMonths(2)->toDateString(), 'target_pax' => 40, 'booked_pax' => 5, 'status' => 'planned', 'notes' => 'Keberangkatan Umroh Reguler'],
            ['package_id' => 3, 'planned_date' => now()->addMonths(3)->toDateString(), 'target_pax' => 50, 'booked_pax' => 5, 'status' => 'planned', 'notes' => 'Keberangkatan Umroh Hemat dari Surabaya'],
            ['package_id' => 5, 'planned_date' => now()->addMonths(4)->toDateString(), 'target_pax' => 25, 'booked_pax' => 0, 'status' => 'planned', 'notes' => 'Ziarah Turki (masih draft)'],
            ['package_id' => 4, 'planned_date' => now()->addMonths(6)->toDateString(), 'target_pax' => 30, 'booked_pax' => 2, 'status' => 'planned', 'notes' => 'Keberangkatan Hajj Plus'],
            ['package_id' => 2, 'planned_date' => now()->addMonths(1)->addDays(15)->toDateString(), 'target_pax' => 20, 'booked_pax' => 12, 'status' => 'active', 'notes' => 'Umroh VIP gelombang 2'],
        ];

        foreach ($schedules as $schedule) {
            PlannedSchedule::create($schedule);
        }
    }
}

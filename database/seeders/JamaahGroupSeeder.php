<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\JamaahGroup;
use Illuminate\Database\Seeder;

class JamaahGroupSeeder extends Seeder
{
    public function run(): void
    {
        $group1 = JamaahGroup::create([
            'name' => 'Umroh Reguler Januari',
            'package_id' => 1, 'leader_id' => 1,
            'departure_date' => now()->addMonths(2), 'return_date' => now()->addMonths(2)->addDays(12),
            'quota' => 40, 'notes' => 'Group utama umroh reguler bulan Januari', 'status' => 'active',
        ]);

        $group2 = JamaahGroup::create([
            'name' => 'Hajj Plus 2026',
            'package_id' => 4, 'leader_id' => 3,
            'departure_date' => now()->addMonths(6), 'return_date' => now()->addMonths(6)->addDays(25),
            'quota' => 30, 'notes' => 'Group hajj plus dengan fasilitas premium', 'status' => 'planned',
        ]);

        $group3 = JamaahGroup::create([
            'name' => 'Umroh VIP Februari',
            'package_id' => 2, 'leader_id' => 5,
            'departure_date' => now()->addMonth(), 'return_date' => now()->addMonth()->addDays(9),
            'quota' => 20, 'notes' => 'Group umroh VIP paket eksklusif', 'status' => 'active',
        ]);

        $customerIds = Customer::pluck('id')->toArray();

        $group1->customers()->attach([
            $customerIds[0] => ['room_type' => 'double', 'notes' => ''],
            $customerIds[1] => ['room_type' => 'double', 'notes' => ''],
            $customerIds[3] => ['room_type' => 'double', 'notes' => 'Butuh kursi roda'],
            $customerIds[6] => ['room_type' => 'double', 'notes' => ''],
        ]);

        $group2->customers()->attach([
            $customerIds[4] => ['room_type' => 'double', 'notes' => ''],
            $customerIds[8] => ['room_type' => 'single', 'notes' => 'Minta kamar sendiri'],
        ]);

        $group3->customers()->attach([
            $customerIds[2] => ['room_type' => 'double', 'notes' => ''],
            $customerIds[6] => ['room_type' => 'double', 'notes' => ''],
        ]);
    }
}

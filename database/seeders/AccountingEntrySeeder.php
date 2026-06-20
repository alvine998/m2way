<?php

namespace Database\Seeders;

use App\Models\AccountingEntry;
use Illuminate\Database\Seeder;

class AccountingEntrySeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            ['type' => 'expense', 'category' => 'salary', 'description' => 'Gaji karyawan bulan ini', 'amount' => 15000000, 'date' => now()->subDays(2), 'user_id' => 1],
            ['type' => 'expense', 'category' => 'operational', 'description' => 'Sewa kantor bulan Januari', 'amount' => 5000000, 'date' => now()->subDays(5), 'user_id' => 1],
            ['type' => 'expense', 'category' => 'marketing', 'description' => 'Iklan Google Ads', 'amount' => 2000000, 'date' => now()->subDays(7), 'user_id' => 2],
            ['type' => 'expense', 'category' => 'operational', 'description' => 'Listrik, air, dan internet', 'amount' => 1500000, 'date' => now()->subDays(10), 'user_id' => 1],
            ['type' => 'expense', 'category' => 'travel', 'description' => 'Tiket pesawat Staff ke Surabaya', 'amount' => 1200000, 'date' => now()->subDays(12), 'user_id' => 2],
            ['type' => 'income', 'category' => 'booking_income', 'description' => 'Biaya administrasi pendaftaran', 'amount' => 500000, 'date' => now()->subDays(14), 'user_id' => 1],
        ];

        foreach ($entries as $data) {
            AccountingEntry::create($data);
        }
    }
}

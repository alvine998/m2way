<?php

namespace Database\Seeders;

use App\Models\AccountingCategory;
use Illuminate\Database\Seeder;

class AccountingCategorySeeder extends Seeder
{
    public function run(): void
    {
        AccountingCategory::insert([
            ['name' => 'Pendapatan Booking', 'slug' => 'booking_income', 'type' => 'income', 'icon' => '💰', 'color' => '#22c55e', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Biaya Tiket', 'slug' => 'flight', 'type' => 'expense', 'icon' => '✈️', 'color' => '#ef4444', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Biaya Hotel', 'slug' => 'hotel', 'type' => 'expense', 'icon' => '🏨', 'color' => '#f59e0b', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Biaya Visa', 'slug' => 'visa', 'type' => 'expense', 'icon' => '📄', 'color' => '#6366f1', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transportasi', 'slug' => 'transport', 'type' => 'expense', 'icon' => '🚌', 'color' => '#06b6d4', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gaji Karyawan', 'slug' => 'salary', 'type' => 'expense', 'icon' => '👤', 'color' => '#8b5cf6', 'sort_order' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Biaya Operasional', 'slug' => 'operational', 'type' => 'expense', 'icon' => '🏢', 'color' => '#ec4899', 'sort_order' => 7, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pemasaran', 'slug' => 'marketing', 'type' => 'expense', 'icon' => '📢', 'color' => '#f97316', 'sort_order' => 8, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\PackageCategory;
use Illuminate\Database\Seeder;

class PackageCategorySeeder extends Seeder
{
    public function run(): void
    {
        PackageCategory::insert([
            [
                'name' => 'Hajj', 'slug' => 'hajj', 'description' => 'Paket ibadah haji ke Tanah Suci',
                'icon' => '🕋', 'color' => '#f53003', 'bg_color' => '#fff4ed',
                'is_active' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Umroh', 'slug' => 'umroh', 'description' => 'Paket ibadah umroh ke Tanah Suci',
                'icon' => '✈️', 'color' => '#2563eb', 'bg_color' => '#eff6ff',
                'is_active' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Ziarah', 'slug' => 'ziarah', 'description' => 'Paket ziarah wisata religi',
                'icon' => '🕌', 'color' => '#7c3aed', 'bg_color' => '#f5f3ff',
                'is_active' => true, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}

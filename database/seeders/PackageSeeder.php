<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        Package::create([
            'name' => 'Umroh Reguler 12 Hari',
            'type' => 'umroh',
            'description' => 'Paket umroh reguler dengan hotel bintang 3, fasilitas standar untuk ibadah yang nyaman.',
            'duration' => 12,
            'departure_date' => now()->addMonths(2),
            'return_date' => now()->addMonths(2)->addDays(12),
            'departure_city' => 'Jakarta',
            'quota' => 40, 'quota_remaining' => 35,
            'base_price' => 28000000, 'hpp' => 22000000,
            'cost_details' => [
                ['label' => 'Tiket Pesawat', 'category' => 'flight', 'amount' => 8500000],
                ['label' => 'Hotel Makkah (5 malam)', 'category' => 'hotel', 'amount' => 5500000],
                ['label' => 'Hotel Madinah (3 malam)', 'category' => 'hotel', 'amount' => 3500000],
                ['label' => 'Visa Umroh', 'category' => 'visa', 'amount' => 2500000],
                ['label' => 'Transport & Makan', 'category' => 'transport', 'amount' => 2000000],
            ],
            'airline' => 'Saudi Airlines', 'hotel_name' => 'Hotel Rawda Al Naseem', 'hotel_star' => 3,
            'includes' => ['Tiket pesawat', 'Hotel', 'Visa', 'Transport', 'Makan 3x sehari', 'Pembimbing'],
            'excludes' => ['Perlengkapan pribadi', 'Dana talangan', 'Surat kesehatan'],
            'status' => 'active',
        ]);

        Package::create([
            'name' => 'Umroh VIP 9 Hari',
            'type' => 'umroh',
            'description' => 'Paket umroh VIP dengan hotel bintang 5 dan layanan premium.',
            'duration' => 9,
            'departure_date' => now()->addMonth(),
            'return_date' => now()->addMonth()->addDays(9),
            'departure_city' => 'Jakarta',
            'quota' => 20, 'quota_remaining' => 12,
            'base_price' => 42000000, 'hpp' => 34000000,
            'cost_details' => [
                ['label' => 'Tiket Pesawat Business', 'category' => 'flight', 'amount' => 15000000],
                ['label' => 'Hotel Makkah (4 malam)', 'category' => 'hotel', 'amount' => 8000000],
                ['label' => 'Hotel Madinah (3 malam)', 'category' => 'hotel', 'amount' => 6000000],
                ['label' => 'Visa Umroh Express', 'category' => 'visa', 'amount' => 3000000],
                ['label' => 'Transport & Makan Premier', 'category' => 'transport', 'amount' => 2000000],
            ],
            'airline' => 'Emirates', 'hotel_name' => 'Hotel Hilton Makkah', 'hotel_star' => 5,
            'includes' => ['Tiket business class', 'Hotel bintang 5', 'Visa express', 'Transport premium', 'Makan 4x sehari', 'Pembimbing berpengalaman'],
            'excludes' => ['Perlengkapan pribadi', 'Dana talangan'],
            'status' => 'active',
        ]);

        Package::create([
            'name' => 'Umroh Hemat 12 Hari',
            'type' => 'umroh',
            'description' => 'Paket umroh ekonomis dengan harga terjangkau, cocok untuk jamaah budget.',
            'duration' => 12,
            'departure_date' => now()->addMonths(3),
            'return_date' => now()->addMonths(3)->addDays(12),
            'departure_city' => 'Surabaya',
            'quota' => 50, 'quota_remaining' => 45,
            'base_price' => 22500000, 'hpp' => 18000000,
            'cost_details' => [
                ['label' => 'Tiket Pesawat', 'category' => 'flight', 'amount' => 7000000],
                ['label' => 'Hotel Makkah (5 malam)', 'category' => 'hotel', 'amount' => 4500000],
                ['label' => 'Hotel Madinah (3 malam)', 'category' => 'hotel', 'amount' => 2500000],
                ['label' => 'Visa Umroh', 'category' => 'visa', 'amount' => 2500000],
                ['label' => 'Transport & Makan', 'category' => 'transport', 'amount' => 1500000],
            ],
            'airline' => 'Lion Air', 'hotel_name' => 'Hotel Al Safwah', 'hotel_star' => 3,
            'includes' => ['Tiket pesawat', 'Hotel', 'Visa', 'Transport', 'Makan 3x sehari'],
            'excludes' => ['Perlengkapan pribadi', 'Dana talangan', 'Pembimbing khusus', 'Surat kesehatan'],
            'status' => 'active',
        ]);

        Package::create([
            'name' => 'Hajj Plus 25 Hari',
            'type' => 'hajj',
            'description' => 'Paket hajj plus dengan hotel bintang 5 dan layanan eksklusif sepanjang ibadah.',
            'duration' => 25,
            'departure_date' => now()->addMonths(6),
            'return_date' => now()->addMonths(6)->addDays(25),
            'departure_city' => 'Jakarta',
            'quota' => 30, 'quota_remaining' => 28,
            'base_price' => 45000000, 'hpp' => 38000000,
            'cost_details' => [
                ['label' => 'Tiket Pesawat', 'category' => 'flight', 'amount' => 12000000],
                ['label' => 'Hotel Makkah (10 malam)', 'category' => 'hotel', 'amount' => 12000000],
                ['label' => 'Hotel Madinah (7 malam)', 'category' => 'hotel', 'amount' => 8000000],
                ['label' => 'Visa Haji', 'category' => 'visa', 'amount' => 3000000],
                ['label' => 'Transport & Makan', 'category' => 'transport', 'amount' => 3000000],
            ],
            'airline' => 'Garuda Indonesia', 'hotel_name' => 'Hotel Movenpick Makkah', 'hotel_star' => 5,
            'includes' => ['Tiket pesawat', 'Hotel bintang 5', 'Visa', 'Transport', 'Makan 3x sehari', 'Pembimbing', 'Manasik'],
            'excludes' => ['Perlengkapan pribadi', 'Dana talangan'],
            'status' => 'active',
        ]);

        Package::create([
            'name' => 'Ziarah Turki 10 Hari',
            'type' => 'ziarah',
            'description' => 'Paket wisata religi ziarah ke tempat-tempat bersejarah Islam di Turki.',
            'duration' => 10,
            'departure_date' => now()->addMonths(4),
            'return_date' => now()->addMonths(4)->addDays(10),
            'departure_city' => 'Jakarta',
            'quota' => 25, 'quota_remaining' => 25,
            'base_price' => 32000000, 'hpp' => 26000000,
            'cost_details' => [
                ['label' => 'Tiket Pesawat', 'category' => 'flight', 'amount' => 10000000],
                ['label' => 'Hotel Istanbul (4 malam)', 'category' => 'hotel', 'amount' => 6000000],
                ['label' => 'Hotel Konya (2 malam)', 'category' => 'hotel', 'amount' => 3000000],
                ['label' => 'Tiket Masuk Wisata', 'category' => 'ticket', 'amount' => 2000000],
                ['label' => 'Transport & Makan', 'category' => 'transport', 'amount' => 3000000],
                ['label' => 'Visa Turki', 'category' => 'visa', 'amount' => 2000000],
            ],
            'airline' => 'Turkish Airlines', 'hotel_name' => 'Hotel Barcelo Istanbul', 'hotel_star' => 4,
            'includes' => ['Tiket pesawat', 'Hotel', 'Visa', 'Transport', 'Makan 3x sehari', 'Guide', 'Tiket masuk objek wisata'],
            'excludes' => ['Perlengkapan pribadi', 'Asuransi perjalanan', 'Souvenir'],
            'status' => 'draft',
        ]);
    }
}

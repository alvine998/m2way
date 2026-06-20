<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Ahmad Fauzi', 'phone' => '081234567890', 'email' => 'ahmad@email.com', 'address' => 'Jl. Merdeka No. 10, Jakarta Pusat', 'gender' => 'male', 'date_of_birth' => '1985-03-15', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890001', 'occupation' => 'Pengusaha'],
            ['name' => 'Siti Nurhaliza', 'phone' => '081234567891', 'email' => 'siti@email.com', 'address' => 'Jl. Sudirman No. 25, Jakarta Selatan', 'gender' => 'female', 'date_of_birth' => '1990-07-22', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890002', 'occupation' => 'Guru'],
            ['name' => 'Mohammed Al-Farsi', 'phone' => '+966501234567', 'email' => 'mohammed@email.com', 'address' => 'King Fahd Road, Riyadh', 'gender' => 'male', 'date_of_birth' => '1978-11-05', 'nationality' => 'SA', 'id_type' => 'passport', 'id_number' => 'A12345678', 'occupation' => 'Dokter'],
            ['name' => 'Fatimah Zahra', 'phone' => '081234567893', 'email' => 'fatimah@email.com', 'address' => 'Jl. Diponegoro No. 50, Bandung', 'gender' => 'female', 'date_of_birth' => '1982-09-18', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890003', 'occupation' => 'Ibu Rumah Tangga'],
            ['name' => 'Abdullah bin Hussein', 'phone' => '+60123456789', 'email' => 'abdullah@email.com', 'address' => 'Jalan Ampang, Kuala Lumpur', 'gender' => 'male', 'date_of_birth' => '1975-04-30', 'nationality' => 'MY', 'id_type' => 'passport', 'id_number' => 'B87654321', 'occupation' => 'Manager'],
            ['name' => 'Hj. Maryam', 'phone' => '081234567894', 'email' => 'maryam@email.com', 'address' => 'Jl. Gatot Subroto No. 8, Surabaya', 'gender' => 'female', 'date_of_birth' => '1965-12-01', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890004', 'occupation' => 'Pensiunan'],
            ['name' => 'H. Budi Santoso', 'phone' => '081234567895', 'email' => 'budi@email.com', 'address' => 'Jl. Malioboro No. 15, Yogyakarta', 'gender' => 'male', 'date_of_birth' => '1970-06-20', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890005', 'occupation' => 'Dosen'],
            ['name' => 'Aisyah Putri', 'phone' => '081234567896', 'email' => 'aisyah@email.com', 'address' => 'Jl. Asia Afrika No. 120, Bandung', 'gender' => 'female', 'date_of_birth' => '1995-02-14', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890006', 'occupation' => 'Karyawan Swasta'],
            ['name' => 'Umar Abdul Aziz', 'phone' => '081234567897', 'email' => 'umar@email.com', 'address' => 'Jl. Thamrin No. 5, Medan', 'gender' => 'male', 'date_of_birth' => '1988-08-08', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890007', 'occupation' => 'Pengacara'],
            ['name' => 'Khadijah binti Kamil', 'phone' => '081234567898', 'email' => 'khadijah@email.com', 'address' => 'Jl. Pahlawan No. 3, Makassar', 'gender' => 'female', 'date_of_birth' => '1983-05-25', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890008', 'occupation' => 'Dokter'],
            ['name' => 'Hasan Al-Bashri', 'phone' => '081234567899', 'email' => 'hasan@email.com', 'address' => 'Jl. Veteran No. 7, Semarang', 'gender' => 'male', 'date_of_birth' => '1972-10-10', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890009', 'occupation' => 'Pedagang'],
            ['name' => 'Husain Ali', 'phone' => '+971501234567', 'email' => 'husain@email.com', 'address' => 'Sheikh Zayed Road, Dubai', 'gender' => 'male', 'date_of_birth' => '1980-01-15', 'nationality' => 'AE', 'id_type' => 'passport', 'id_number' => 'C98765432', 'occupation' => 'Pengusaha'],
            ['name' => 'Zainab Al-Ghazali', 'phone' => '081234567800', 'email' => 'zainab@email.com', 'address' => 'Jl. Imam Bonjol No. 12, Padang', 'gender' => 'female', 'date_of_birth' => '1992-11-30', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890010', 'occupation' => 'Arsitek'],
            ['name' => 'Yusuf Ibrahim', 'phone' => '081234567801', 'email' => 'yusuf@email.com', 'address' => 'Jl. Ahmad Yani No. 88, Palembang', 'gender' => 'male', 'date_of_birth' => '1968-07-12', 'nationality' => 'ID', 'id_type' => 'nik', 'id_number' => '3201234567890011', 'occupation' => 'PNS'],
        ];

        foreach ($customers as $c) {
            Customer::create($c);
        }
    }
}

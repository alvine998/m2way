<?php

namespace Database\Seeders;

use App\Models\TravelDocument;
use Illuminate\Database\Seeder;

class TravelDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $docs = [
            ['customer_id' => 1, 'document_type' => 'passport', 'document_number' => 'A1234567', 'issuing_country' => 'Indonesia', 'issue_date' => '2023-01-15', 'expiry_date' => '2028-01-15', 'notes' => 'Passport a/n Ahmad Fauzi', 'uploaded_by' => 1],
            ['customer_id' => 1, 'document_type' => 'vaccination', 'document_number' => 'VAX-001', 'issuing_country' => 'Indonesia', 'issue_date' => '2025-06-01', 'expiry_date' => '2026-06-01', 'notes' => 'Vaksin meningitis', 'uploaded_by' => 1],
            ['customer_id' => 2, 'document_type' => 'passport', 'document_number' => 'B7654321', 'issuing_country' => 'Indonesia', 'issue_date' => '2024-03-20', 'expiry_date' => '2029-03-20', 'notes' => 'Passport a/n Siti Nurhaliza', 'uploaded_by' => 1],
            ['customer_id' => 3, 'document_type' => 'passport', 'document_number' => 'SA123456', 'issuing_country' => 'Saudi Arabia', 'issue_date' => '2022-08-10', 'expiry_date' => '2027-08-10', 'notes' => 'Passport a/n Mohammed Al-Farsi', 'uploaded_by' => 2],
            ['customer_id' => 3, 'document_type' => 'visa', 'document_number' => 'VISA-UM-001', 'issuing_country' => 'Saudi Arabia', 'issue_date' => '2025-11-01', 'expiry_date' => '2025-12-01', 'notes' => 'Visa umroh', 'uploaded_by' => 2],
            ['customer_id' => 4, 'document_type' => 'ktp', 'document_number' => '3201234567890003', 'issuing_country' => 'Indonesia', 'issue_date' => '2020-01-01', 'expiry_date' => '2030-01-01', 'notes' => 'KTP Fatimah Zahra', 'uploaded_by' => 1],
            ['customer_id' => 5, 'document_type' => 'passport', 'document_number' => 'MY876543', 'issuing_country' => 'Malaysia', 'issue_date' => '2024-06-01', 'expiry_date' => '2029-06-01', 'notes' => 'Passport Abdullah', 'uploaded_by' => 2],
            ['customer_id' => 7, 'document_type' => 'insurance', 'document_number' => 'INS-2025-001', 'issuing_country' => 'Indonesia', 'issue_date' => '2025-10-01', 'expiry_date' => '2026-10-01', 'notes' => 'Asuransi perjalanan hajj', 'uploaded_by' => 1],
        ];

        foreach ($docs as $doc) {
            TravelDocument::create($doc);
        }
    }
}

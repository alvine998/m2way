<?php

namespace Database\Seeders;

use App\Models\AccountingEntry;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = [1, 2, 3];
        $transactions = [
            ['customer_id' => 1, 'package_id' => 1, 'departure_date' => now()->addMonths(2)->toDateString(), 'total_price' => 28000000, 'discount' => 0, 'status' => 'confirmed', 'payment_status' => 'paid', 'payment_type' => 'cash', 'payment_method' => 'transfer'],
            ['customer_id' => 2, 'package_id' => 1, 'departure_date' => now()->addMonths(2)->toDateString(), 'total_price' => 28000000, 'discount' => 500000, 'status' => 'confirmed', 'payment_status' => 'partial', 'payment_type' => 'cicilan', 'payment_method' => null],
            ['customer_id' => 3, 'package_id' => 2, 'departure_date' => now()->addMonth()->toDateString(), 'total_price' => 42000000, 'discount' => 0, 'status' => 'confirmed', 'payment_status' => 'unpaid', 'payment_type' => 'cash', 'payment_method' => null],
            ['customer_id' => 4, 'package_id' => 1, 'departure_date' => now()->addMonths(2)->toDateString(), 'total_price' => 28000000, 'discount' => 0, 'status' => 'completed', 'payment_status' => 'paid', 'payment_type' => 'cash', 'payment_method' => 'cash'],
            ['customer_id' => 5, 'package_id' => 4, 'departure_date' => now()->addMonths(6)->toDateString(), 'total_price' => 45000000, 'discount' => 1000000, 'status' => 'pending', 'payment_status' => 'unpaid', 'payment_type' => 'cicilan', 'payment_method' => null],
            ['customer_id' => 6, 'package_id' => 3, 'departure_date' => now()->addMonths(3)->toDateString(), 'total_price' => 22500000, 'discount' => 0, 'status' => 'confirmed', 'payment_status' => 'paid', 'payment_type' => 'cash', 'payment_method' => 'transfer'],
            ['customer_id' => 7, 'package_id' => 2, 'departure_date' => now()->addMonth()->toDateString(), 'total_price' => 42000000, 'discount' => 0, 'status' => 'confirmed', 'payment_status' => 'partial', 'payment_type' => 'cicilan', 'payment_method' => null],
            ['customer_id' => 8, 'package_id' => 1, 'departure_date' => now()->addMonths(2)->toDateString(), 'total_price' => 28000000, 'discount' => 0, 'status' => 'cancelled', 'payment_status' => 'unpaid', 'payment_type' => 'cash', 'payment_method' => null],
            ['customer_id' => 9, 'package_id' => 4, 'departure_date' => now()->addMonths(6)->toDateString(), 'total_price' => 45000000, 'discount' => 0, 'status' => 'confirmed', 'payment_status' => 'paid', 'payment_type' => 'cash', 'payment_method' => 'transfer'],
            ['customer_id' => 10, 'package_id' => 3, 'departure_date' => now()->addMonths(3)->toDateString(), 'total_price' => 22500000, 'discount' => 250000, 'status' => 'pending', 'payment_status' => 'unpaid', 'payment_type' => 'cicilan', 'payment_method' => null],
        ];

        foreach ($transactions as $i => $data) {
            $data['user_id'] = $userIds[$i % 3];
            $data['grand_total'] = $data['total_price'] - $data['discount'];
            $tx = Transaction::create($data);

            TransactionItem::create([
                'transaction_id' => $tx->id,
                'customer_id' => $data['customer_id'],
                'package_id' => $data['package_id'],
                'room_type' => 'double',
                'price' => $data['grand_total'],
            ]);

            AccountingEntry::create([
                'entry_number' => AccountingEntry::generateNumber('income'),
                'type' => 'income', 'category' => 'booking_income',
                'description' => 'Booking: ' . ($tx->package->name ?? 'Package'),
                'amount' => $data['grand_total'], 'date' => now()->subDays(10 - $i),
                'reference_type' => Transaction::class, 'reference_id' => $tx->id,
                'user_id' => $data['user_id'],
            ]);

            if ($data['payment_status'] === 'paid') {
                Payment::create([
                    'payment_number' => Payment::generateNumber(),
                    'transaction_id' => $tx->id, 'amount' => $data['grand_total'],
                    'payment_method' => $data['payment_method'] ?? 'transfer',
                    'payment_date' => now()->subDays(10 - $i), 'user_id' => $data['user_id'],
                ]);
            } elseif ($data['payment_status'] === 'partial') {
                $partialAmount = round($data['grand_total'] * 0.4);
                Payment::create([
                    'payment_number' => Payment::generateNumber(),
                    'transaction_id' => $tx->id, 'amount' => $partialAmount,
                    'payment_method' => 'transfer', 'payment_date' => now()->subDays(10 - $i),
                    'notes' => 'Pembayaran pertama (DP 40%)',
                    'user_id' => $data['user_id'],
                ]);
            }
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionsPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_transactions_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('transactions.index'))
            ->assertOk();
    }

    public function test_authenticated_user_can_view_transaction_create_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('transactions.create'))
            ->assertOk();
    }

    public function test_transaction_create_persists_selected_status(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Jane Customer',
            'phone' => '08123456789',
        ]);
        $package = Package::create([
            'name' => 'Umroh Regular',
            'type' => 'umroh',
            'duration' => 9,
            'quota' => 20,
            'quota_remaining' => 20,
            'base_price' => 25000000,
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->post(route('transactions.store'), [
                'customer_id' => $customer->id,
                'package_id' => $package->id,
                'departure_date' => now()->addMonth()->toDateString(),
                'total_price' => 25000000,
                'discount' => 0,
                'status' => 'confirmed',
                'notes' => 'Ready for departure.',
            ])
            ->assertRedirect(route('transactions.index'));

        $this->assertSame('confirmed', Transaction::first()->status);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::firstOrCreate([
            'email' => 'dashboard-test@example.com',
        ], [
            'name' => 'Dashboard Test',
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_authenticated_user_can_view_finance_overview(): void
    {
        $user = User::firstOrCreate([
            'email' => 'finance-test@example.com',
        ], [
            'name' => 'Finance Test',
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->get(route('finance.index'))
            ->assertOk();
    }
}

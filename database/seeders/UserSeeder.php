<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@m2way.com'],
            [
                'name' => 'Admin M2Way',
                'password' => Hash::make('password'),
                'role_id' => Role::where('slug', 'admin')->value('id'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'finance@m2way.com'],
            [
                'name' => 'Staff Keuangan',
                'password' => Hash::make('password'),
                'role_id' => Role::where('slug', 'finance')->value('id'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'ops@m2way.com'],
            [
                'name' => 'Staff Operasional',
                'password' => Hash::make('password'),
                'role_id' => Role::where('slug', 'ops')->value('id'),
            ]
        );
    }
}

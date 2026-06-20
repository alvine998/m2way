<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin' => 'Admin',
            'finance' => 'Finance',
            'ops' => 'Operational',
        ];

        $permissionMap = [
            'admin' => [
                'dashboard.view',
                'schedules.view', 'schedules.manage', 'timeline.view', 'calendar.view',
                'transactions.view', 'transactions.manage',
                'customers.view', 'customers.manage',
                'jamaah-groups.view', 'jamaah-groups.manage',
                'packages.view', 'packages.manage',
                'package-categories.view', 'package-categories.manage',
                'finance.view', 'finance.export',
                'accounting.view', 'accounting.manage',
                'accounting-categories.view', 'accounting-categories.manage',
                'travel-documents.view', 'travel-documents.manage',
                'activity-logs.view',
                'users.view', 'users.manage',
                'roles.view', 'roles.manage',
            ],
            'finance' => [
                'dashboard.view',
                'transactions.view',
                'customers.view',
                'finance.view', 'finance.export',
                'accounting.view', 'accounting.manage',
                'accounting-categories.view', 'accounting-categories.manage',
                'activity-logs.view',
            ],
            'ops' => [
                'dashboard.view',
                'schedules.view', 'schedules.manage', 'timeline.view', 'calendar.view',
                'transactions.view', 'transactions.manage',
                'customers.view', 'customers.manage',
                'jamaah-groups.view', 'jamaah-groups.manage',
                'packages.view', 'packages.manage',
                'package-categories.view', 'package-categories.manage',
                'travel-documents.view', 'travel-documents.manage',
                'activity-logs.view',
            ],
        ];

        foreach ($roles as $slug => $name) {
            $role = Role::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
            $role->permissions()->delete();
            foreach ($permissionMap[$slug] as $permission) {
                $role->permissions()->create(['permission' => $permission]);
            }
        }


    }
}

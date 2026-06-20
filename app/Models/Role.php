<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function permissions(): HasMany
    {
        return $this->hasMany(RolePermission::class);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('permission', $permission)->exists();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function allPermissions(): array
    {
        return [
            'Dashboard' => [
                'dashboard.view' => 'View Dashboard',
            ],
            'Planning' => [
                'schedules.view' => 'View Schedules',
                'schedules.manage' => 'Manage Schedules',
                'timeline.view' => 'View Timeline',
                'calendar.view' => 'View Calendar',
            ],
            'Transactions' => [
                'transactions.view' => 'View Transactions',
                'transactions.manage' => 'Manage Transactions',
            ],
            'Customers' => [
                'customers.view' => 'View Customers',
                'customers.manage' => 'Manage Customers',
            ],
            'Jamaah Groups' => [
                'jamaah-groups.view' => 'View Jamaah Groups',
                'jamaah-groups.manage' => 'Manage Jamaah Groups',
            ],
            'Packages' => [
                'packages.view' => 'View Packages',
                'packages.manage' => 'Manage Packages',
                'package-categories.view' => 'View Package Categories',
                'package-categories.manage' => 'Manage Package Categories',
            ],
            'Finance' => [
                'finance.view' => 'View Finance',
                'finance.export' => 'Export Finance',
            ],
            'Accounting' => [
                'accounting.view' => 'View Accounting',
                'accounting.manage' => 'Manage Accounting',
                'accounting-categories.view' => 'View Accounting Categories',
                'accounting-categories.manage' => 'Manage Accounting Categories',
            ],
            'Travel Documents' => [
                'travel-documents.view' => 'View Travel Documents',
                'travel-documents.manage' => 'Manage Travel Documents',
            ],
            'Activity Logs' => [
                'activity-logs.view' => 'View Activity Logs',
            ],
            'Administration' => [
                'users.view' => 'View Users',
                'users.manage' => 'Manage Users',
                'roles.view' => 'View Roles',
                'roles.manage' => 'Manage Roles',
            ],
        ];
    }
}

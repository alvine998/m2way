<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PackageCategorySeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            PackageSeeder::class,
            AccountingCategorySeeder::class,
            TransactionSeeder::class,
            JamaahGroupSeeder::class,
            TravelDocumentSeeder::class,
            PlannedScheduleSeeder::class,
            AccountingEntrySeeder::class,
        ]);
    }
}

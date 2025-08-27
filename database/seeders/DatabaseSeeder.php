<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the role and permission seeder
        $this->call([
            RolePermissionSeeder::class,
            CategoriesSeeder::class,
            SuppliersSeeder::class,
        ]);
    }
}

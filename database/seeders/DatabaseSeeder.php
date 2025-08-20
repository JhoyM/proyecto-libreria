<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1) Seed básicos
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
            CategoriesSeeder::class,
            SuppliersSeeder::class,
        ]);

        // 2) Usuario administrador y asignación de rol (Spatie)
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@libreria.local'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Principal',
                'phone' => '000-000-0000',
                'document_number' => 'ADM-0001',
                'password' => Hash::make('Admin12345!'),
                'status' => true,
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol (requiere que la tabla roles de Spatie esté migrada)
        if ($admin && method_exists($admin, 'assignRole')) {
            $admin->assignRole('Administrador');
        }
    }
}

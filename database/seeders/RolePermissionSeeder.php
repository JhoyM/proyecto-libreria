<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Users
            'users.index',
            'users.create',
            'users.edit',
            'users.delete',
            'users.view',
            
            // Products
            'products.index',
            'products.create',
            'products.edit',
            'products.delete',
            'products.view',
            'inventory.manage',
            
            // Categories
            'categories.index',
            'categories.create',
            'categories.edit',
            'categories.delete',
            'categories.view',
            
            // Suppliers
            'suppliers.index',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.delete',
            'suppliers.view',
            
            // Customers
            'customers.index',
            'customers.create',
            'customers.edit',
            'customers.delete',
            'customers.view',
            
            // Sales
            'sales.index',
            'sales.create',
            'sales.edit',
            'sales.delete',
            'sales.view',
            'sales.report',
            'sales.export',
            
            // System
            'settings.manage',
            'logs.view',
            'backup.create',
            'backup.restore',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create roles and assign created permissions
        
        // Admin - gets all permissions
        $adminRole = Role::findOrCreate('Administrador');
        $adminRole->syncPermissions(Permission::all());
        
        // Vendedor - Sales related permissions
        $sellerRole = Role::findOrCreate('Vendedor');
        $sellerRole->syncPermissions([
            'products.index',
            'products.view',
            'categories.index',
            'categories.view',
            'customers.index',
            'customers.view',
            'sales.index',
            'sales.create',
            'sales.edit',
            'sales.view'
        ]);
        
        // Almacen - Inventory and products
        $storeRole = Role::findOrCreate('Almacen');
        $storeRole->syncPermissions([
            'products.index',
            'products.create',
            'products.edit',
            'products.view',
            'inventory.manage',
            'categories.index',
            'categories.view',
            'suppliers.index',
            'suppliers.view'
        ]);
        
        // Ensure 'Cliente' role does NOT exist in the system
        if ($client = Role::where('name', 'Cliente')->first()) {
            // Remove permissions and delete the role (will cascade on pivots)
            $client->syncPermissions([]);
            $client->delete();
        }

        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@libreria.local'],
            [
                'first_name' => 'Administrador',
                'last_name' => 'Sistema',
                'document_number' => '00000000',
                'phone' => '123456789',
                'password' => Hash::make('Admin12345!'),
                'status' => true,
            ]
        );
        
        // Assign admin role if not already assigned
        if (!$admin->hasRole('Administrador')) {
            $admin->assignRole('Administrador');
        }
        
        $this->command->info('Roles y permisos creados exitosamente.');
        $this->command->info('Usuario administrador creado:');
        $this->command->info('Email: admin@libreria.local');
        $this->command->info('Contraseña: Admin12345!');
    }
}

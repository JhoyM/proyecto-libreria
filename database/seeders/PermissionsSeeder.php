<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'products',
            'categories',
            'suppliers',
            'customers',
            'sales',
            'inventory',
            'reports',
            'users',
        ];

        $actions = ['view', 'create', 'update', 'delete'];

        $allPermissions = [];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $name = $module . '.' . $action;
                $perm = Permission::firstOrCreate([
                    'name' => $name,
                    'guard_name' => 'web',
                ]);
                $allPermissions[] = $perm->name;
            }
        }

        // Roles existentes (creados en RolesSeeder)
        $admin = Role::where('name', 'Administrador')->first();
        $seller = Role::where('name', 'Vendedor')->first();
        $store = Role::where('name', 'Almacenero')->first();

        if ($admin) {
            $admin->syncPermissions($allPermissions);
        }

        if ($seller) {
            $sellerPerms = [
                'products.view',
                'customers.view', 'customers.create', 'customers.update',
                'sales.view', 'sales.create',
                'reports.view',
            ];
            $seller->syncPermissions($sellerPerms);
        }

        if ($store) {
            $storePerms = [
                'products.view', 'products.create', 'products.update',
                'categories.view',
                'suppliers.view',
                'inventory.view', 'inventory.create',
            ];
            $store->syncPermissions($storePerms);
        }
    }
}

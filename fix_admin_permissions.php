<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illware_Console_Kernel::class);

$app->boot();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Find the admin user
$admin = User::where('email', 'admin@libreria.local')->first();

if (!$admin) {
    echo "Admin user not found!\n";
    exit(1);
}

echo "Found admin user with ID: " . $admin->id . "\n";

// Ensure the Administrator role exists
$adminRole = Role::firstOrCreate(
    ['name' => 'Administrador'],
    ['guard_name' => 'web']
);

echo "Admin role ID: " . $adminRole->id . "\n";

// Assign the admin role to the admin user
$admin->assignRole($adminRole);
echo "Assigned Administrator role to admin user\n";

// Get all permissions
$permissions = Permission::all();
$permissionNames = $permissions->pluck('name')->toArray();

// Sync all permissions to the admin role
$adminRole->syncPermissions($permissionNames);
echo "Synced all permissions to Administrator role\n";

// Verify the admin has the role and permissions
$admin->load('roles', 'permissions');

echo "\nAdmin roles: " . json_encode($admin->getRoleNames()) . "\n";
echo "Admin permissions: " . json_encode($admin->getAllPermissions()->pluck('name')) . "\n";

echo "\nDone!\n";

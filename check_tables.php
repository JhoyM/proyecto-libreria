<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// List all tables in the public schema
try {
    echo "Checking database connection...\n";
    $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    
    echo "\n=== Database Tables ===\n";
    foreach ($tables as $table) {
        echo "- " . $table->table_name . "\n";
    }
    
    // Check if roles and permissions tables exist
    $hasRolesTable = in_array('roles', array_column($tables, 'table_name'));
    $hasPermissionsTable = in_array('permissions', array_column($tables, 'permissions'));
    
    echo "\n=== Status ===\n";
    echo "Roles table exists: " . ($hasRolesTable ? 'Yes' : 'No') . "\n";
    echo "Permissions table exists: " . ($hasPermissionsTable ? 'Yes' : 'No') . "\n";
    
} catch (Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\nDone!\n";

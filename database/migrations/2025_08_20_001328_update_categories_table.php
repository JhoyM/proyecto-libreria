<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add slug column if it doesn't exist
        if (!Schema::hasColumn('categories', 'slug')) {
            // First add the column as nullable
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });

            // Generate slugs for existing categories
            $categories = DB::table('categories')->get();
            foreach ($categories as $category) {
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update([
                        'slug' => \Illuminate\Support\Str::slug($category->name)
                    ]);
            }

            // Now make it not nullable and unique
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->nullable(false)->unique()->change();
            });
        }

        // Ensure type column has the correct values (PostgreSQL-safe)
        if (Schema::hasColumn('categories', 'type')) {
            // Normalize column type to VARCHAR to avoid enum issues across drivers
            try {
                DB::statement("ALTER TABLE categories ALTER COLUMN type TYPE VARCHAR(32) USING type::text");
            } catch (\Throwable $e) {
                // ignore if already varchar or conversion not needed
            }

            // Add the check constraint only if it doesn't exist
            $exists = DB::select(<<<SQL
                SELECT 1
                FROM pg_constraint c
                JOIN pg_class t ON c.conrelid = t.oid
                WHERE t.relname = 'categories'
                  AND c.conname = 'categories_type_check'
            SQL);

            if (empty($exists)) {
                DB::statement("ALTER TABLE categories ADD CONSTRAINT categories_type_check CHECK (type IN ('libros','utiles_escolares','oficina'))");
            }

            // Ensure default and not null
            DB::statement("UPDATE categories SET type = 'libros' WHERE type IS NULL OR btrim(type) = ''");
            try { DB::statement("ALTER TABLE categories ALTER COLUMN type SET DEFAULT 'libros'"); } catch (\Throwable $e) {}
            try { DB::statement("ALTER TABLE categories ALTER COLUMN type SET NOT NULL"); } catch (\Throwable $e) {}
        }

        // Ensure status has a default value (PostgreSQL-safe)
        if (Schema::hasColumn('categories', 'status')) {
            try { DB::statement("ALTER TABLE categories ALTER COLUMN status TYPE BOOLEAN USING status::boolean"); } catch (\Throwable $e) {}
            DB::statement("UPDATE categories SET status = true WHERE status IS NULL");
            try { DB::statement("ALTER TABLE categories ALTER COLUMN status SET DEFAULT true"); } catch (\Throwable $e) {}
            try { DB::statement("ALTER TABLE categories ALTER COLUMN status SET NOT NULL"); } catch (\Throwable $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a non-destructive migration, so we don't need to do anything in down
    }
};

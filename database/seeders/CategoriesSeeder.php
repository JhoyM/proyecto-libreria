<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $data = [
            [
                'name' => 'Libros',
                'description' => 'Libros de distintas categorías y autores',
                'type' => 'libros',
                'slug' => Str::slug('Libros'),
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Útiles Escolares',
                'description' => 'Artículos para estudiantes y colegios',
                'type' => 'utiles_escolares',
                'slug' => Str::slug('Útiles Escolares'),
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Oficina',
                'description' => 'Suministros y accesorios de oficina',
                'type' => 'oficina',
                'slug' => Str::slug('Oficina'),
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('categories')->upsert($data, ['name'], ['description', 'type', 'slug', 'status', 'updated_at']);
    }
}

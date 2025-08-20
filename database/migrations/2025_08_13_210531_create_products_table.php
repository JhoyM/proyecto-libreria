<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);
            
            // Campos específicos para libros
            $table->string('author', 150)->nullable();
            $table->string('publisher', 100)->nullable()->comment('Editorial');
            $table->string('isbn', 20)->nullable();
            $table->integer('publication_year')->nullable();
            $table->string('genre', 100)->nullable();
            $table->integer('pages')->nullable();
            $table->string('language', 50)->default('Español');
            
            // Campos para otros productos
            $table->string('brand', 100)->nullable()->comment('Marca');
            $table->string('model', 100)->nullable()->comment('Modelo');
            $table->string('color', 50)->nullable();
            $table->string('size', 50)->nullable()->comment('Tamaño');
            
            $table->string('image_path', 255)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index('code');
            $table->index('name');
            $table->index('author');
            $table->index('category_id');
            $table->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

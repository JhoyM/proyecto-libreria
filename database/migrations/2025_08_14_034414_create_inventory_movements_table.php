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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('user_id')->constrained('users')->comment('Usuario que realizó el movimiento');
            $table->enum('movement_type', ['entrada', 'salida', 'ajuste', 'venta', 'devolucion']);
            $table->integer('quantity')->comment('Positivo para entradas, negativo para salidas');
            $table->integer('previous_stock');
            $table->integer('new_stock');
            $table->string('reference', 100)->nullable()->comment('Número de venta, orden de compra, etc.');
            $table->text('description')->nullable();
            $table->timestamp('movement_date');
            $table->timestamps();
            
            // Índices
            $table->index('product_id');
            $table->index('movement_date');
            $table->index('movement_type');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};

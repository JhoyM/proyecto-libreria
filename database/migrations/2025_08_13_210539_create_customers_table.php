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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->enum('document_type', ['CI', 'NIT', 'Pasaporte'])->default('CI');
            $table->string('document_number', 20)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('customer_type', ['regular', 'frecuente', 'mayorista'])->default('regular');
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index('document_number');
            $table->index(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

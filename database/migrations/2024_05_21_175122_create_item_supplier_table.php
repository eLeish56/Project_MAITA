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
        // Tabel items sudah dibuat di 0001_01_01_000000_create_items_table.php
        // File ini hanya membuat suppliers dan item_supplier pivot table
        
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('email')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
       Schema::create('item_supplier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('supplier_id');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->unique(['item_id', 'supplier_id']); //  ok
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_supplier');
        Schema::dropIfExists('suppliers');
        // items akan dihapus oleh migration 0001_01_01_000000_create_items_table.php
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create the goods_receipt_items table.
 *
 * Each record captures a line item within a goods receipt, storing
 * the product name and the quantity actually received. This can be
 * compared against the PO to highlight variances.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receipt_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('item_id')->nullable()->default(null); // Menambah relasi ke items dengan default null
            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            $table->string('product_name');
            $table->string('unit')->nullable();
            $table->integer('quantity_received');
            $table->integer('remaining_quantity')->default(0);
            $table->date('expiry_date')->nullable();
            $table->string('lot_code')->nullable();
            $table->string('batch_number')->nullable();
            $table->enum('expiry_status', ['safe', 'warning', 'expired'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Tambah index untuk optimasi query
            $table->index('lot_code');
            $table->index(['item_id', 'expiry_date']);
            $table->index('batch_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_items');
    }
};
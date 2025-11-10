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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->date('po_date');
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable(); // penting untuk menyimpan user pembuat
            $table->boolean('prices_confirmed')->default(false);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->foreignId('purchase_request_id')->nullable()->constrained()->onDelete('set null');
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->date('estimated_delivery_date')->nullable();
            $table->dateTime('confirmation_date')->nullable();
            $table->dateTime('confirmed_delivery_date')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->boolean('supplier_confirmed')->default(false);
            $table->text('supplier_notes')->nullable();
            $table->string('invoice_image_path')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

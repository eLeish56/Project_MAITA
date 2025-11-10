<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receipt_item_id')->constrained('goods_receipt_items');
            $table->enum('type', ['in', 'out']);
            $table->decimal('qty', 10, 2);
            $table->string('ref_type'); // GR, Sales, etc
            $table->unsignedBigInteger('ref_id');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['ref_type', 'ref_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_movements');
    }
};
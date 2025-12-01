<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kolom batch_number, expiry_status, dan remaining_quantity
        // sudah ada di tabel goods_receipt_items sejak dibuat.
        // Migration ini tidak perlu melakukan apa-apa.
        // (Dipertahankan untuk consistency dengan migration history)
    }

    public function down()
    {
        // Tidak ada yang perlu di-rollback karena kolom sudah ada
        // di tabel goods_receipt_items sejak awal pembuatan
    }
};
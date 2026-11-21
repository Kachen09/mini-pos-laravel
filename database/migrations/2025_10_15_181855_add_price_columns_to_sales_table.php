<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // เราจะเพิ่มแค่คอลัมน์ 'subtotal' ที่ขาดไปจริงๆ เท่านั้น
            // และให้เพิ่มหลังคอลัมน์ 'payment_type'
            $table->decimal('subtotal', 10, 2)->after('payment_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // โค้ดสำหรับยกเลิก (เผื่อไว้)
            $table->dropColumn(['subtotal']);
        });
    }
};
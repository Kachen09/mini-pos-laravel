<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // เพิ่ม 2 คอลัมน์นี้เข้าไป (อาจจะไว้หลัง grand_total)
            $table->unsignedInteger('points_used')->nullable()->default(0)->after('grand_total'); // จำนวนแต้มที่ใช้
            $table->decimal('points_discount', 10, 2)->nullable()->default(0)->after('points_used'); // จำนวนเงินส่วนลดที่ได้จากแต้ม
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['points_used', 'points_discount']);
        });
    }
};
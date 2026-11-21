<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // เพิ่มคอลัมน์ 'points' หลังคอลัมน์ 'email' (หรือคอลัมน์สุดท้ายที่คุณมี)
            // ชนิดเป็น integer (จำนวนเต็ม), ไม่ติดลบ (unsigned), เริ่มต้นที่ 0
            $table->unsignedInteger('points')->default(0)->after('email'); 
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
};
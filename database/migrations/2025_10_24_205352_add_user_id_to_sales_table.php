<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // เพิ่ม user_id หลัง customer_id (หรือตำแหน่งที่เหมาะสม)
            // constrained('users') คือสร้าง Foreign Key ไปยังตาราง users
            // onDelete('set null') คือถ้า User ถูกลบ ให้ค่า user_id ใน sales เป็น null (ไม่ลบ sale ทิ้ง)
            $table->foreignId('user_id')->nullable()->after('customer_id')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // ลบ Foreign Key ก่อน
            $table->dropColumn('user_id');   // แล้วค่อยลบคอลัมน์
        });
    }
};
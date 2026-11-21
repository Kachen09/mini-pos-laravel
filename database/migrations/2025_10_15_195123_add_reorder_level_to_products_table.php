<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // เพิ่มคอลัมน์ 'reorder_level' หลังคอลัมน์ 'stock'
            // กำหนดค่าเริ่มต้นเป็น 5 (สามารถเปลี่ยนได้)
            $table->integer('reorder_level')->default(5)->after('stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('reorder_level');
        });
    }
};
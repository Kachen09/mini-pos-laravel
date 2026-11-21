<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }

    public function down(): void // โค้ดสำหรับ rollback (เผื่อไว้)
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
        });
    }
};

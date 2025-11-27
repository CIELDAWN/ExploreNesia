<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('mitra_type', ['hotel', 'wisata', 'restoran'])->nullable()->after('role');
            $table->string('business_name')->nullable()->after('mitra_type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mitra_type', 'business_name']);
        });
    }
};


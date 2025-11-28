<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('city_name')->nullable()->after('city_id');
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('city_name')->nullable()->after('city_id');
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('city_name');
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('city_name');
        });
    }
};



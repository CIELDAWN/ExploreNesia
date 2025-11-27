<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->decimal('price_per_night_min', 12, 2)->nullable()->change();
            $table->decimal('price_per_night_max', 12, 2)->nullable()->change();
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->decimal('average_price_min', 12, 2)->nullable()->change();
            $table->decimal('average_price_max', 12, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->decimal('price_per_night_min', 12, 2)->nullable(false)->change();
            $table->decimal('price_per_night_max', 12, 2)->nullable(false)->change();
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->decimal('average_price_min', 12, 2)->nullable(false)->change();
            $table->decimal('average_price_max', 12, 2)->nullable(false)->change();
        });
    }
};

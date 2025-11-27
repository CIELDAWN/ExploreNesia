<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        // Only drop from destinations if the table exists
        if (Schema::hasTable('destinations')) {
            Schema::table('destinations', function (Blueprint $table) {
                $table->dropColumn(['latitude', 'longitude']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
        });

        if (Schema::hasTable('destinations')) {
            Schema::table('destinations', function (Blueprint $table) {
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
            });
        }
    }
};

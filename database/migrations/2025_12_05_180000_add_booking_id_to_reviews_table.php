<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('booking_id')->nullable()->after('user_id')->constrained('bookings')->onDelete('cascade');

            // Optional snapshot info for non-destination reviews
            $table->string('business_type')->nullable()->after('destination_id');
            $table->string('business_name')->nullable()->after('business_type');

            $table->unique('booking_id');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(['booking_id']);
            $table->dropConstrainedForeignId('booking_id');
            $table->dropColumn(['business_type', 'business_name']);
        });
    }
};

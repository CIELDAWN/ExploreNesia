<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            $table->decimal('ticket_price', 12, 2)->nullable()->after('business_address');         // untuk wisata
            $table->decimal('reservation_price', 12, 2)->nullable()->after('ticket_price');        // untuk restoran
            $table->decimal('room_price_single', 12, 2)->nullable()->after('reservation_price');   // hotel kasur 1
            $table->decimal('room_price_double', 12, 2)->nullable()->after('room_price_single');   // hotel kasur 2
        });
    }

    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            $table->dropColumn([
                'ticket_price',
                'reservation_price',
                'room_price_single',
                'room_price_double',
            ]);
        });
    }
};





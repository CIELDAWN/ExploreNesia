<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('bookable'); // polymorphic: destination, hotel, restaurant - sudah auto create index
            $table->date('booking_date');
            $table->date('visit_date');
            $table->integer('quantity')->default(1); // jumlah orang/kamar
            $table->decimal('total_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('final_price', 12, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            
            $table->index('booking_code');
            $table->index('user_id');
            // index untuk bookable sudah otomatis dibuat oleh morphs()
            $table->index('status');
            $table->index('visit_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
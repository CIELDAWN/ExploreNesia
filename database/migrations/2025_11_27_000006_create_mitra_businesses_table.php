<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 1 user = 1 bisnis
            $table->enum('business_type', ['hotel', 'wisata', 'restoran']);
            $table->string('business_name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('address');
            $table->foreignId('province_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            
            // Contact Info
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('website')->nullable();
            
            // Business Specific Fields - Hotel
            $table->integer('star_rating')->nullable(); // untuk hotel
            $table->decimal('price_per_night_min', 12, 2)->nullable(); // untuk hotel
            $table->decimal('price_per_night_max', 12, 2)->nullable(); // untuk hotel
            $table->integer('total_rooms')->nullable(); // untuk hotel
            $table->json('hotel_facilities')->nullable(); // untuk hotel
            
            // Business Specific Fields - Restaurant
            $table->json('cuisine_types')->nullable(); // untuk restoran
            $table->decimal('average_price_min', 12, 2)->nullable(); // untuk restoran
            $table->decimal('average_price_max', 12, 2)->nullable(); // untuk restoran
            $table->time('opening_time')->nullable(); // untuk restoran
            $table->time('closing_time')->nullable(); // untuk restoran
            $table->integer('capacity')->nullable(); // untuk restoran
            $table->json('restaurant_facilities')->nullable(); // untuk restoran
            
            // Business Specific Fields - Wisata
            $table->decimal('ticket_price_adult', 12, 2)->nullable(); // untuk wisata
            $table->decimal('ticket_price_child', 12, 2)->nullable(); // untuk wisata
            $table->time('operating_hours_start')->nullable(); // untuk wisata
            $table->time('operating_hours_end')->nullable(); // untuk wisata
            $table->json('wisata_facilities')->nullable(); // untuk wisata
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null'); // untuk wisata
            
            // Media
            $table->string('thumbnail')->nullable();
            $table->json('gallery')->nullable(); // array of image paths
            
            // Rating & Reviews (calculated fields)
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            
            // Status & Approval
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('user_id');
            $table->index('business_type');
            $table->index('status');
            $table->index('is_active');
            $table->index('average_rating');
            $table->index(['province_id', 'city_id']);
            
            // Unique constraint: 1 user = 1 business
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_businesses');
    }
};


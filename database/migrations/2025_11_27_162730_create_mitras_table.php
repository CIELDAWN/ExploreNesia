<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('province_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            
            // Data Bisnis Utama
            $table->string('business_name');
            $table->enum('business_type', ['hotel', 'restoran', 'wisata']);
            $table->text('business_description')->nullable();
            $table->text('business_address')->nullable();
            
            // Kontak
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('website')->nullable();
            
            // Rating & Status
            $table->decimal('average_rating', 2, 1)->nullable();
            $table->integer('total_reviews')->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            
            // Media
            $table->string('logo')->nullable();
            $table->json('gallery')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};
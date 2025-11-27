<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Mitra
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('address');
            $table->json('cuisine_types')->nullable(); // [Indonesian, Chinese, Western, dll]
            $table->decimal('average_price_min', 12, 2)->nullable();
            $table->decimal('average_price_max', 12, 2)->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('website')->nullable();
            $table->json('facilities')->nullable(); // [wifi, parkir, smoking area, dll]
            $table->integer('capacity')->default(0);
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('city_id');
            $table->index('slug');
            $table->index('status');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
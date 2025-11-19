<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('reviewable'); // polymorphic - sudah auto create index
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->json('photos')->nullable(); // array foto
            $table->boolean('is_verified')->default(false); // verified booking
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            
            $table->index('user_id');
            // index untuk reviewable sudah otomatis dibuat oleh morphs()
            $table->index('rating');
            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
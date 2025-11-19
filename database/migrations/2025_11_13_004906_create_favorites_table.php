<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->nullableMorphs('favoritable'); // menggunakan nullableMorphs untuk avoid conflict
            $table->timestamps();
            
            // Unique constraint: satu user tidak bisa favorite item yang sama 2x
            $table->unique(['user_id', 'favoritable_type', 'favoritable_id'], 'favorites_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
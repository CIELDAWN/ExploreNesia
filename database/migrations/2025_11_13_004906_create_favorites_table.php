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
            $table->unsignedBigInteger('favoritable_id');
            $table->string('favoritable_type');
            $table->timestamps();

            // Pastikan user tidak bisa favorite item yang sama 2x
            $table->unique(['user_id', 'favoritable_type', 'favoritable_id'], 'favorites_user_favoritable_unique');
            $table->index(['favoritable_id', 'favoritable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};

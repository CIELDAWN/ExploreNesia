<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->date('visit_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index untuk query yang lebih cepat
            $table->index(['user_id', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_histories');
    }
};

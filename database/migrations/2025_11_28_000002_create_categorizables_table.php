<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorizables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->morphs('categorizable');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['category_id', 'categorizable_id', 'categorizable_type']);
            $table->index(['categorizable_id', 'categorizable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorizables');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('promotionable'); // menggunakan nullableMorphs untuk avoid conflict
            $table->string('title');
            $table->text('description');
            $table->enum('discount_type', ['percentage', 'fixed']); // % atau nominal
            $table->decimal('discount_value', 12, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('max_usage')->nullable(); // batas penggunaan
            $table->integer('current_usage')->default(0);
            $table->decimal('min_transaction', 12, 2)->nullable(); // min pembelian
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Custom indexes
            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
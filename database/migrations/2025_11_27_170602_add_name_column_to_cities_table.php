<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            // Check if name column doesn't exist and add it
            if (!Schema::hasColumn('cities', 'name')) {
                $table->string('name')->after('id');
            }
            
            // Check if province_id column doesn't exist and add it
            if (!Schema::hasColumn('cities', 'province_id')) {
                $table->foreignId('province_id')->nullable()->constrained()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            if (Schema::hasColumn('cities', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('cities', 'province_id')) {
                $table->dropForeign(['province_id']);
                $table->dropColumn('province_id');
            }
        });
    }
};
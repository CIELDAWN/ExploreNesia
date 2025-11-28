<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            if (Schema::hasColumn('mitras', 'logo')) {
                $table->renameColumn('logo', 'thumbnail');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            if (Schema::hasColumn('mitras', 'thumbnail')) {
                $table->renameColumn('thumbnail', 'logo');
            }
        });
    }
};
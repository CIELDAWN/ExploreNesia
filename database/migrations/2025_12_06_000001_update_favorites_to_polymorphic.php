<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('favoritable_id')->nullable()->after('user_id');
            $table->string('favoritable_type')->nullable()->after('favoritable_id');

            // destination_id tetap dipertahankan sementara untuk kompatibilitas lama
            $table->dropUnique(['user_id', 'destination_id']);
        });

        // Migrasikan data lama: anggap semua baris lama adalah destinasi
        DB::table('favorites')
            ->whereNull('favoritable_id')
            ->update([
                'favoritable_type' => 'App\\Models\\Destination',
                'favoritable_id' => DB::raw('destination_id'),
            ]);

        Schema::table('favorites', function (Blueprint $table) {
            $table->unique(['user_id', 'favoritable_type', 'favoritable_id'], 'favorites_user_favoritable_unique');
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique('favorites_user_favoritable_unique');
            $table->dropColumn(['favoritable_id', 'favoritable_type']);
            $table->unique(['user_id', 'destination_id']);
        });
    }
};

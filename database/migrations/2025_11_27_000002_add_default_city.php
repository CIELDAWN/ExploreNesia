<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if cities table exists and what columns it has
        if (Schema::hasTable('cities')) {
            $columns = Schema::getColumnListing('cities');
            
            // Only insert if the table has the expected columns
            if (in_array('name', $columns) && in_array('province_id', $columns)) {
                // First, make sure we have a default province (biarkan ID auto-increment)
                $provinceId = null;
                if (Schema::hasTable('provinces')) {
                    $provinceId = DB::table('provinces')->where('code', 'default')->value('id');

                    if (! $provinceId) {
                        $provinceId = DB::table('provinces')->insertGetId([
                            'code' => 'default',
                            'name' => 'Default Province',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Then add a default city if we have a province id
                if ($provinceId) {
                    DB::table('cities')->updateOrInsert(
                        ['name' => 'Default City', 'province_id' => $provinceId],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }

    public function down(): void
    {
        DB::table('cities')->where('name', 'Default City')->delete();
        DB::table('provinces')->where('code', 'default')->delete();
    }
};

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
                // First, make sure we have a default province
                if (Schema::hasTable('provinces')) {
                    DB::table('provinces')->insertOrIgnore([
                        'id' => 1,
                        'code' => 'default',
                        'name' => 'Default Province',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Then add a default city
                DB::table('cities')->insertOrIgnore([
                    'id' => 1,
                    'name' => 'Default City',
                    'province_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('cities')->where('id', 1)->delete();
        DB::table('provinces')->where('id', 1)->delete();
    }
};

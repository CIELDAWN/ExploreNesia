<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk PostgreSQL: jadikan kolom destination_id nullable
        DB::statement('ALTER TABLE favorites ALTER COLUMN destination_id DROP NOT NULL');
    }

    public function down(): void
    {
        // Kembalikan menjadi NOT NULL (hanya jika tidak ada baris dengan destination_id NULL)
        DB::statement('ALTER TABLE favorites ALTER COLUMN destination_id SET NOT NULL');
    }
};

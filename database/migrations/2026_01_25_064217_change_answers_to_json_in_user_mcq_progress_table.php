<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE user_mcq_progress
            MODIFY answers JSON NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE user_mcq_progress
            MODIFY answers LONGTEXT NULL
        ");
    }
};

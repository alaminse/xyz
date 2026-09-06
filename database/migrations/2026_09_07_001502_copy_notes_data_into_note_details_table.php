<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('
            INSERT INTO note_details (id, note_id, title, slug, description, created_at, updated_at)
            SELECT id, id, title, slug, description, created_at, updated_at
            FROM notes
        ');

        $maxId = DB::table('note_details')->max('id') ?? 0;
        DB::statement('ALTER TABLE note_details AUTO_INCREMENT = '.($maxId + 1));
    }

    public function down(): void
    {
        DB::table('note_details')->truncate();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function dropForeignKey($table, $column)
    {
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND COLUMN_NAME = ?
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$table, $column]);

        if ($fk) {
            DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
        }
    }

    public function up(): void
    {
        /** ---------- SBA QUESTIONS ---------- */
        $this->dropForeignKey('sba_questions', 'note_id');

        DB::statement('ALTER TABLE sba_questions MODIFY note_id BIGINT UNSIGNED NULL');

        // 🔥 FIX INVALID DATA
        DB::statement('
            UPDATE sba_questions
            SET note_id = NULL
            WHERE note_id IS NOT NULL
              AND note_id NOT IN (SELECT id FROM notes)
        ');

        DB::statement('
            ALTER TABLE sba_questions
            ADD FOREIGN KEY (note_id) REFERENCES notes(id)
            ON DELETE SET NULL
        ');

        /** ---------- MCQ QUESTIONS ---------- */
        $this->dropForeignKey('mcq_questions', 'note_id');

        DB::statement('ALTER TABLE mcq_questions MODIFY note_id BIGINT UNSIGNED NULL');

        // 🔥 FIX INVALID DATA
        DB::statement('
            UPDATE mcq_questions
            SET note_id = NULL
            WHERE note_id IS NOT NULL
              AND note_id NOT IN (SELECT id FROM notes)
        ');

        DB::statement('
            ALTER TABLE mcq_questions
            ADD FOREIGN KEY (note_id) REFERENCES notes(id)
            ON DELETE SET NULL
        ');
    }

    public function down(): void
    {
        $this->dropForeignKey('sba_questions', 'note_id');
        DB::statement('ALTER TABLE sba_questions MODIFY note_id BIGINT UNSIGNED NOT NULL');
        DB::statement('
            ALTER TABLE sba_questions
            ADD FOREIGN KEY (note_id) REFERENCES notes(id)
            ON DELETE CASCADE
        ');

        $this->dropForeignKey('mcq_questions', 'note_id');
        DB::statement('ALTER TABLE mcq_questions MODIFY note_id BIGINT UNSIGNED NOT NULL');
        DB::statement('
            ALTER TABLE mcq_questions
            ADD FOREIGN KEY (note_id) REFERENCES notes(id)
            ON DELETE CASCADE
        ');
    }
};

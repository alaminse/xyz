<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if note_id column exists, if not then add it
        if (!Schema::hasColumn('ospe_questions', 'note_id')) {
            Schema::table('ospe_questions', function (Blueprint $table) {
                $table->unsignedBigInteger('note_id')->nullable()->after('ospe_station_id');
            });
        }

        // Check if foreign key exists, if not then add it
        $foreignKeyExists = DB::select(
            "SELECT COUNT(*) as count
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
             AND TABLE_NAME = 'ospe_questions'
             AND CONSTRAINT_NAME = 'ospe_questions_note_id_foreign'
             AND CONSTRAINT_TYPE = 'FOREIGN KEY'"
        );

        if ($foreignKeyExists[0]->count == 0) {
            Schema::table('ospe_questions', function (Blueprint $table) {
                $table->foreign('note_id')
                      ->references('id')
                      ->on('notes')
                      ->onDelete('set null');
            });
        }

        // Change questions column to LONGTEXT using raw SQL
        DB::statement('ALTER TABLE ospe_questions MODIFY questions LONGTEXT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if foreign key exists before dropping
        $foreignKeyExists = DB::select(
            "SELECT COUNT(*) as count
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
             AND TABLE_NAME = 'ospe_questions'
             AND CONSTRAINT_NAME = 'ospe_questions_note_id_foreign'
             AND CONSTRAINT_TYPE = 'FOREIGN KEY'"
        );

        if ($foreignKeyExists[0]->count > 0) {
            Schema::table('ospe_questions', function (Blueprint $table) {
                $table->dropForeign(['note_id']);
            });
        }

        // Check if note_id column exists before dropping
        if (Schema::hasColumn('ospe_questions', 'note_id')) {
            Schema::table('ospe_questions', function (Blueprint $table) {
                $table->dropColumn('note_id');
            });
        }

        // Revert questions column back to TEXT
        DB::statement('ALTER TABLE ospe_questions MODIFY questions TEXT');
    }
};

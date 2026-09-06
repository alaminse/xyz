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
        Schema::table('chapters', function (Blueprint $table) {
            // Same pattern as the existing sba/note/mcq/.../secure_pdf flags.
            $table->tinyInteger('modeltest')->default(0)->after('secure_pdf');
            $table->tinyInteger('question_bank')->default(0)->after('modeltest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn(['modeltest', 'question_bank']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('secure_pdfs', function (Blueprint $table) {
            $table->unsignedBigInteger('chapter_id')->nullable()->after('category');
            $table->unsignedBigInteger('lesson_id')->nullable()->after('chapter_id');
        });
    }

    public function down(): void
    {
        Schema::table('secure_pdfs', function (Blueprint $table) {
            $table->dropColumn(['chapter_id', 'lesson_id']);
        });
    }
};

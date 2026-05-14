<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // course_details table
        if (!Schema::hasColumn('course_details', 'secure_pdf')) {
            Schema::table('course_details', function (Blueprint $table) {
                $table->boolean('secure_pdf')->default(false)->after('self_assessment');
            });
        }

        // chapters table
        if (!Schema::hasColumn('chapters', 'secure_pdf')) {
            Schema::table('chapters', function (Blueprint $table) {
                $table->boolean('secure_pdf')->default(false)->after('self_assessment');
            });
        }

        // chapter_lesson table
        if (!Schema::hasColumn('chapter_lesson', 'secure_pdf')) {
            Schema::table('chapter_lesson', function (Blueprint $table) {
                $table->boolean('secure_pdf')->default(false)->after('self_assessment');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('course_details', 'secure_pdf')) {
            Schema::table('course_details', function (Blueprint $table) {
                $table->dropColumn('secure_pdf');
            });
        }

        if (Schema::hasColumn('chapters', 'secure_pdf')) {
            Schema::table('chapters', function (Blueprint $table) {
                $table->dropColumn('secure_pdf');
            });
        }

        if (Schema::hasColumn('chapter_lesson', 'secure_pdf')) {
            Schema::table('chapter_lesson', function (Blueprint $table) {
                $table->dropColumn('secure_pdf');
            });
        }
    }
};

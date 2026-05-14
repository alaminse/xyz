<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_details', function (Blueprint $table) {
            if (!Schema::hasColumn('course_details', 'secure_pdf')) {
                $table->boolean('secure_pdf')->default(false)->after('self_assessment');
            }
        });

        Schema::table('chapters', function (Blueprint $table) {
            if (!Schema::hasColumn('chapters', 'secure_pdf')) {
                $table->boolean('secure_pdf')->default(false)->after('self_assessment');
            }
        });

        Schema::table('chapter_lesson', function (Blueprint $table) {
            if (!Schema::hasColumn('chapter_lesson', 'secure_pdf')) {
                $table->boolean('secure_pdf')->default(false)->after('self_assessment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_details', function (Blueprint $table) {
            if (Schema::hasColumn('course_details', 'secure_pdf')) {
                $table->dropColumn('secure_pdf');
            }
        });

        Schema::table('chapters', function (Blueprint $table) {
            if (Schema::hasColumn('chapters', 'secure_pdf')) {
                $table->dropColumn('secure_pdf');
            }
        });

        Schema::table('chapter_lesson', function (Blueprint $table) {
            if (Schema::hasColumn('chapter_lesson', 'secure_pdf')) {
                $table->dropColumn('secure_pdf');
            }
        });
    }
};

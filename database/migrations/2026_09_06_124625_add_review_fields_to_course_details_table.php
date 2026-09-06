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
        Schema::table('course_details', function (Blueprint $table) {
            // Placed right after self_assessment, matching the existing
            // sba/note/mcq/flush/written/videos/mock_viva/ospe flag pattern.
            $table->tinyInteger('review_questions')->default(0)->after('self_assessment');
            $table->tinyInteger('performance_status')->default(0)->after('review_questions');
            $table->tinyInteger('modeltest')->default(0)->after('performance_status');
            $table->tinyInteger('question_bank')->default(0)->after('modeltest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_details', function (Blueprint $table) {
            $table->dropColumn([
                'review_questions',
                'performance_status',
                'modeltest',
                'question_bank',
            ]);
        });
    }
};

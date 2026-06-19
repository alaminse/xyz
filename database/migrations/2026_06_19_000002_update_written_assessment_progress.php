<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('written_assessment_progress', function (Blueprint $table) {
            $table->unsignedBigInteger('question_group_id')->nullable()->after('lesson_id');
        });
    }

    public function down()
    {
        Schema::table('written_assessment_progress', function (Blueprint $table) {
            $table->dropColumn('question_group_id');
        });
    }
};

<?php

use App\Models\Assessment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_assessment_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->nullable()->constrained((new Assessment())->getTable());
            $table->foreignId('course_id')->nullable()->constrained((new Course())->getTable());
            $table->foreignId('user_id')->nullable()->constrained((new User())->getTable());
            $table->string('slug')->nullable();
            $table->float('total_marks')->default(0);
            $table->float('achive_marks')->default(0);
            $table->integer('current_index')->default(0);
            $table->json('question_ids');
            $table->json('remaining_question')->nullable();
            $table->json('answered_question')->nullable();
            $table->json('details')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_assessment_progress');
    }
};

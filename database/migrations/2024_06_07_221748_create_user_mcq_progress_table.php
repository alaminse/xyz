<?php

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
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
        Schema::create('user_mcq_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained((new User())->getTable());
            $table->foreignId('course_id')->nullable()->constrained((new Course())->getTable());
            $table->foreignId('chapter_id')->nullable()->constrained((new Chapter())->getTable());
            $table->foreignId('lesson_id')->nullable()->constrained((new Lesson())->getTable());
            $table->string('slug')->nullable();
            $table->integer('total')->default(0);
            $table->integer(column: 'current_question_index')->default(0);
            $table->text('mcq_ids')->nullable();
            $table->json('remaining_mcq')->nullable();
            $table->json('answered_mcq')->nullable();
            $table->double('progress')->default(0);
            $table->double('progress_cut')->default(0.0);
            $table->text('answers')->nullable();
            $table->integer('correct')->default(0);
            $table->integer('wrong')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_mcq_progress');
    }
};

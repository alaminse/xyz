<?php

use App\Enums\Status;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->nullable()->constrained((new Chapter())->getTable());
            $table->foreignId('lesson_id')->nullable()->constrained((new Lesson())->getTable());
            $table->string('name');
            $table->string('slug');
            $table->timestamp('start_date_time')->nullable();
            $table->timestamp('end_date_time')->nullable();
            $table->float('total_marks')->default(0);
            $table->integer('time');
            $table->json('question_ids')->nullable();
            $table->tinyInteger('isPaid')->default(1);
            $table->tinyInteger('status')->default(Status::PENDING());
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
        Schema::dropIfExists('assessments');
    }
};

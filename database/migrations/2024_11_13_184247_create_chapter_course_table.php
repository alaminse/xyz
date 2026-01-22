<?php

use App\Models\Chapter;
use App\Models\Course;
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
        Schema::create('chapter_course', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->constrained((new Course())->getTable());
            $table->foreignId('chapter_id')->nullable()->constrained((new Chapter())->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter_course');
    }
};

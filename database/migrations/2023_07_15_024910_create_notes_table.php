<?php

use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Note;
use App\Models\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->nullable()->constrained((new Chapter())->getTable());
            $table->foreignId('lesson_id')->nullable()->constrained((new Lesson())->getTable());
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('isPaid')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('course_note', function (Blueprint $table) {
            $table->foreignId('note_id')->nullable()->constrained((new Note())->getTable());
            $table->foreignId('course_id')->nullable()->constrained((new Course())->getTable());
        });

        // drop course_id if exists
        if (Schema::hasColumn('notes', 'course_id')) {
            Schema::table('notes', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
                $table->dropColumn('course_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_note');
        Schema::dropIfExists('notes');
    }
};

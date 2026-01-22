<?php

use App\Models\Chapter;
use App\Models\Note;
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
        Schema::create('mock_vivas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->nullable()->constrained((new Note())->getTable());
            $table->foreignId('chapter_id')->nullable()->constrained((new Chapter())->getTable());
            $table->foreignId('lesson_id')->nullable()->constrained((new Lesson())->getTable());
            $table->string('slug')->nullable();
            $table->tinyInteger('isPaid')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mock_vivas');
    }
};

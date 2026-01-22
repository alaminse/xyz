<?php

use App\Enums\Status;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained((new Course())->getTable());
            $table->string('slug')->nullable();
            $table->string('banner')->nullable();
            $table->string('name');
            $table->text('details')->nullable();
            $table->integer('is_pricing')->default(0);
            $table->integer('status')->default(Status::ACTIVE());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
};

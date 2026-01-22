<?php

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
        Schema::create('course_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained((new Course())->getTable());
            $table->integer('duration')->nullable();
            $table->string('type')->nullable();
            $table->float('price')->default(0.0);
            $table->float('sell_price')->default(0.0);
            $table->tinyInteger('sba')->default(0);
            $table->tinyInteger('note')->default(0);
            $table->tinyInteger('mcq')->default(0);
            $table->tinyInteger('flush')->default(0);
            $table->tinyInteger('written')->default(0)->note('Written Assessment');
            $table->tinyInteger('videos')->default(0)->note('Lecture Video');
            $table->tinyInteger('mock_viva')->default(0)->note('Mock Viva');
            $table->tinyInteger('ospe')->default(0)->note('OSPE Station');
            $table->tinyInteger('self_assessment')->default(0)->note('Self Assessment');
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
        Schema::dropIfExists('course_details');
    }
};

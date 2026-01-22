<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Course;
use App\Models\MockViva;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_mock_viva', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->constrained((new Course())->getTable());
            $table->foreignId('mock_viva_id')->nullable()->constrained((new MockViva())->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_mock_viva');
    }
};

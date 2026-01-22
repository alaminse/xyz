<?php

use App\Enums\Status;
use App\Models\Chapter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('name');
            $table->tinyInteger('sba')->default(0);
            $table->tinyInteger('note')->default(0);
            $table->tinyInteger('mcq')->default(0);
            $table->tinyInteger('flush')->default(0);
            $table->tinyInteger('videos')->default(0);
            $table->tinyInteger('ospe')->default(0);
            $table->tinyInteger('written')->default(0);
            $table->tinyInteger('mock_viva')->default(0);
            $table->tinyInteger('self_assessment')->default(0);
            $table->tinyInteger('status')->default(Status::ACTIVE());
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
        Schema::dropIfExists('chapters');
    }
};

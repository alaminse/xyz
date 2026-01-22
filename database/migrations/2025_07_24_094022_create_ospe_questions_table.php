<?php

use App\Models\OspeStation;
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
        Schema::create('ospe_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ospe_station_id')->nullable()->constrained((new OspeStation())->getTable());
            $table->string('image')->nullable();
            $table->text('questions')->nullable();
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
        Schema::dropIfExists('ospe_questions');
    }
};

<?php

use App\Models\MockViva;
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
        Schema::create('mock_viva_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mock_viva_id')->nullable()->constrained((new MockViva())->getTable());
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
        Schema::dropIfExists('mock_viva_questions');
    }
};

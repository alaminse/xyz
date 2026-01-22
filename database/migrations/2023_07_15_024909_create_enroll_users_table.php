<?php

use App\Enums\Status;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enroll_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained((new Course())->getTable());
            $table->foreignId('user_id')->nullable()->constrained((new User())->getTable());
            $table->string('slug')->unique();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->float('price')->default(0.0);
            $table->float('sell_price')->default(0.0);
            $table->tinyInteger('status')->default(Status::PENDING());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enroll_users');
    }
};

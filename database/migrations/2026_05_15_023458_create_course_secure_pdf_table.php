<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_secure_pdf', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('secure_pdf_id');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('secure_pdf_id')->references('id')->on('secure_pdfs')->onDelete('cascade');

            $table->unique(['course_id', 'secure_pdf_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_secure_pdf');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secure_pdfs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('file_path');
            $table->string('original_name');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->integer('total_pages')->default(0);
            $table->bigInteger('file_size')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_print')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pdf_view_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secure_pdf_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token', 64)->unique();
            $table->string('ip_address', 45);
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('pdf_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secure_pdf_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('action');
            $table->timestamp('accessed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pdf_access_logs');
        Schema::dropIfExists('pdf_view_tokens');
        Schema::dropIfExists('secure_pdfs');
    }
};

<?php

use App\Enums\Status;
use App\Models\Country;
use App\Models\EnrollUser;
use App\Models\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->foreignId('country_id')->nullable();
            $table->foreignId('state_id')->nullable();
            $table->foreignId('enroll_user_id')->nullable()->constrained((new EnrollUser())->getTable());
            $table->string('firstName');
            $table->string('lastName')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->text('address2')->nullable();
            $table->text('zip')->nullable();
            $table->tinyInteger('paymentMethod')->nullable();
            $table->float('p-amount')->default(0.0);
            $table->text('p-t_id')->nullable();
            $table->string('p-phone')->nullable();
            $table->string('cc-name')->nullable();
            $table->string('cc-number')->nullable();
            $table->string('cc-expiration')->nullable();
            $table->string('cc-cvv')->nullable();
            $table->tinyInteger('status')->default(Status::PENDING());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};

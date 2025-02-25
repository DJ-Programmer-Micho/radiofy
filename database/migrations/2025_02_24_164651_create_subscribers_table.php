<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('radio_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('uid')->nullable();
            $table->string('status')->default('active');
            $table->boolean('email_verify')->default(false);
            $table->boolean('phone_verify')->default(false);
            $table->string('email_otp_number')->nullable();
            $table->string('phone_otp_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};

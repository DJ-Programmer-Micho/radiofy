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
        Schema::create('listeners', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedInteger('status')->default(1);
            $table->boolean('email_verify')->default(false);
            $table->boolean('phone_verify')->default(false);
            $table->string('email_otp_number')->nullable();
            $table->string('phone_otp_number')->nullable();
            $table->string('uid')->nullable();
            $table->json('recent_radios')->nullable();
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
        Schema::dropIfExists('listeners');
    }
};

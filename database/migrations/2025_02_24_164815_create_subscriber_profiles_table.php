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
        Schema::create('subscriber_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id')->unique();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_profiles');
    }
};

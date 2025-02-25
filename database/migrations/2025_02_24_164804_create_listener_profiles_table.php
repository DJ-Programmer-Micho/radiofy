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
        Schema::create('listener_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listener_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('business_module')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();

            $table->foreign('listener_id')->references('id')->on('listeners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listener_profiles');
    }
};

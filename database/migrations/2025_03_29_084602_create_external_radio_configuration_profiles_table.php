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
        Schema::create('external_radio_configuration_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_id')->unique();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('frequency')->nullable();
            $table->string('location')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('description')->nullable();
            $table->json('genres')->nullable();
            $table->unsignedInteger('highest_peak_listeners')->nullable();
            $table->json('social_media')->nullable();
            $table->json('radio_locale')->nullable();
            $table->timestamps();

            $table->foreign('radio_id')->references('id')->on('external_radio_configurations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_radio_configuration_profiles');
    }
};

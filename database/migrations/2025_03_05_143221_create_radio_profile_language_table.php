<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('radio_profile_language', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_configuration_profile_id');
            $table->unsignedBigInteger('language_id');
            $table->timestamps();

            $table->foreign('radio_configuration_profile_id')
                  ->references('id')->on('radio_configuration_profiles')
                  ->onDelete('cascade');
            $table->foreign('language_id')
                  ->references('id')->on('languages')
                  ->onDelete('cascade');

            // Ensure each combination is unique
            $table->unique(['radio_configuration_profile_id', 'language_id'], 'radio_profile_lang_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('radio_profile_language');
    }
};

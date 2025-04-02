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
        Schema::create('languageables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('languageable_id');
            $table->string('languageable_type'); // RadioConfiguration or ExternalRadioConfiguration
            $table->timestamps();
        
            $table->index(['languageable_id', 'languageable_type'], 'languageables_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languageables');
    }
};

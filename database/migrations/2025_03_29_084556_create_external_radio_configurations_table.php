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
        Schema::create('external_radio_configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id');
            $table->string('radio_name')->unique();
            $table->string('radio_name_slug')->unique();
            $table->string('stream_url')->unique()->nullable();
            $table->unsignedInteger('verified')->default(0);
            $table->unsignedInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_radio_configurations');
    }
};

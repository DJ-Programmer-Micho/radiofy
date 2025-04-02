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
        Schema::create('radio_configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('radio_name')->unique();
            $table->string('radio_name_slug')->unique();
            $table->string('source')->unique()->nullable();
            $table->string('source_password');
            $table->string('fallback_mount')->nullable();
            $table->unsignedInteger('verified')->default(0);
            $table->unsignedInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radio_ronfigurations');
    }
};

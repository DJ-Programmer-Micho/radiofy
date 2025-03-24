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
        Schema::create('radio_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('radio_configuration_id');
            $table->unsignedBigInteger('plan_id');
            $table->enum('payment_frequency', ['monthly', 'yearly'])->default('monthly');
            $table->dateTime('subscribed_date');
            $table->dateTime('renew_date');
            $table->dateTime('expire_date');
            
            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
            $table->foreign('radio_configuration_id')->references('id')->on('radio_configurations')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radio_subscriptions');
    }
};

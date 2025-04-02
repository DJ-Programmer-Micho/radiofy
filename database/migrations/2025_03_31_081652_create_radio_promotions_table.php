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
        Schema::create('radio_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('radioable_id')->nullable();
            $table->string('radioable_type')->nullable();
            $table->unsignedBigInteger('promotion_id'); // links to the Promotion table
            $table->unsignedBigInteger('verification_id')->nullable(); // optional: if you want to track which verification provided the discount
            $table->string('promotion_text');
            $table->dateTime('promotion_date');
            $table->dateTime('expire_date');
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2)->default(0); // This can be calculated from Verification::discount_ad if applicable
            $table->json('target_gender')->nullable();     // Will store an array like [1] or [1,2]
            $table->json('target_age_range')->nullable();
            $table->unsignedInteger('new_listener_count')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
            
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
            $table->foreign('verification_id')->references('id')->on('radio_verifications')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radio_promotions');
    }
};

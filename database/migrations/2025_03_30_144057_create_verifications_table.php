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
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('sell_price_monthly', 8, 2)->nullable();
            $table->decimal('sell_price_yearly', 8, 2)->nullable();
            $table->decimal('discount_ad',8,2)->default(1);
            $table->integer('priority')->default(1);
            $table->boolean('status')->default(1);
            $table->boolean('ribbon')->default(0);
            $table->string('rib_text')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};

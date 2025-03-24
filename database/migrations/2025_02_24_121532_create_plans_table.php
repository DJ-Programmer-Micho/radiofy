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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->integer('bitrate');         
            $table->integer('max_listeners');   
            $table->decimal('sell_price_monthly', 8, 2);
            $table->decimal('sell_price_yearly', 8, 2);
            $table->integer('priority');
            $table->unsignedInteger('status')->default(1);
            $table->unsignedInteger('support')->default(0);
            $table->unsignedInteger('ribbon')->default(0);
            $table->string('rib_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

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
        Schema::create('radio_verifications', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('subscriber_id')->constrained()->onDelete('cascade');
        
            $table->unsignedBigInteger('radioable_id');
            $table->string('radioable_type');
            
            $table->foreignId('verification_id')->constrained()->onDelete('cascade');
            $table->decimal('ad_dicount',8,2);
        
            $table->enum('payment_frequency', ['monthly', 'yearly']);
            $table->date('verified_date')->nullable();
            $table->date('renew_date')->nullable();
            $table->date('expire_date')->nullable();
        
            $table->timestamps();        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radio_verifications');
    }
};

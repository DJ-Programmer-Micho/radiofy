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
        Schema::create('icecast_configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id')->unique();
            $table->string('radio_name');
            $table->string('location')->nullable();
            $table->string('server_admin')->nullable();
            $table->string('server_password');
            $table->unsignedInteger('max_listeners')->default(100);
            $table->unsignedInteger('burst_size')->default(1024);
            $table->unsignedInteger('port')->default(8000);
            $table->string('bind_address')->default('0.0.0.0');
            $table->string('source_password');
            $table->string('relay_password')->nullable();
            $table->string('admin_password')->nullable();
            $table->string('fallback_mount')->nullable();
            $table->string('genre')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('bitrate')->nullable();
            $table->unsignedInteger('sample_rate')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('icecast_configurations');
    }
};

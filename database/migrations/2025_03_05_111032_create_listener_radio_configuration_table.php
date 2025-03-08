<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('listener_radio_configuration', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listener_id');
            $table->unsignedBigInteger('radio_configuration_id');
            $table->timestamps();

            $table->foreign('listener_id')->references('id')->on('listeners')->onDelete('cascade');
            $table->foreign('radio_configuration_id')->references('id')->on('radio_configurations')->onDelete('cascade');

            $table->unique(['listener_id', 'radio_configuration_id'], 'lr_config_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('listener_radio_configuration');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListenerLikedRadiosTable extends Migration
{
    public function up()
    {
        Schema::create('listener_liked_radios', function (Blueprint $table) {
            $table->unsignedBigInteger('listener_id');
            $table->unsignedBigInteger('radio_configuration_id');
            $table->timestamps();

            // Composite primary key for uniqueness
            $table->primary(['listener_id', 'radio_configuration_id']);

            $table->foreign('listener_id')
                ->references('id')->on('listeners')
                ->onDelete('cascade');

            $table->foreign('radio_configuration_id')
                ->references('id')->on('radio_configurations')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('listener_liked_radios');
    }
}

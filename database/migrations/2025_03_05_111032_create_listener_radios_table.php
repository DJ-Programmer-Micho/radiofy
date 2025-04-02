<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListenerRadiosTable extends Migration
{
    public function up()
    {
        Schema::create('listener_radios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listener_id');
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('radioable_id');
            $table->string('radioable_type');
            $table->timestamps();

            $table->foreign('listener_id')
                ->references('id')->on('listeners')
                ->onDelete('cascade');

            // Prevent duplicates
            // $table->unique(['listener_id', 'radioable_id', 'radioable_type'], 'listener_radios_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('listener_radios');
    }
}

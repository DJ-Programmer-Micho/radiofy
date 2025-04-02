<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listener_id');
            $table->morphs('radioable');
            $table->timestamps();

            $table->foreign('listener_id')
                  ->references('id')->on('listeners')
                  ->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('follows');
    }
}

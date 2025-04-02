<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listener_id');
            $table->morphs('radioable'); // Creates radioable_id and radioable_type
            $table->timestamps();

            $table->foreign('listener_id')
                  ->references('id')->on('listeners')
                  ->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}

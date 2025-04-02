<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenreablesTable extends Migration
{
    public function up()
    {
        Schema::create('genreables', function (Blueprint $table) {
            $table->unsignedBigInteger('genre_id');
            $table->unsignedBigInteger('genreable_id');
            $table->string('genreable_type');
            $table->timestamps();

            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
            $table->primary(['genre_id', 'genreable_id', 'genreable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('genreables');
    }
}

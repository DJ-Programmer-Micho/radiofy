<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); //'en'
            $table->string('name');           //'English'
            $table->string('image');           //'English'
            $table->string('image_sq');           //'English'
            $table->integer('priority');      //'1'
            $table->unsignedInteger('status');//'1'
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('languages');
    }
};

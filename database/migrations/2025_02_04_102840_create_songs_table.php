<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('artist')->nullable();
            $table->string('spotify_link')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('songs');
    }
};

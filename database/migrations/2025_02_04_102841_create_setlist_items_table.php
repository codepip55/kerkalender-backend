<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('setlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setlist_id')->constrained()->onDelete('cascade');
            $table->foreignId('song_id')->constrained()->onDelete('cascade');
            $table->string('key')->nullable();
            $table->text('vocal_notes')->nullable();
            $table->text('band_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('setlist_items');
    }
};

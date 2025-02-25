<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations
     */
    public function up(): void
    {
        // Create users table
        Schema::hasTable('users') || Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('refreshToken')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Create team table
        Schema::hasTable('teams') || Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Create team_user table
        Schema::hasTable('team_user') || Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->timestamps();
        });

        // Create Services table
        Schema::hasTable('services') || Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('service_manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Create service team user table
        Schema::hasTable('service_team_user') || Schema::create('service_team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['accepted', 'denied', 'waiting'])->default('waiting');
            $table->timestamps();
        });

        // Create songs table
        Schema::hasTable('songs') || Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('artist');
            $table->string('spotify_id')->nullable();
            $table->timestamps();
        });

        // Create setlists table
        Schema::hasTable('setlists') || Schema::create('setlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Create setlist items table
        Schema::hasTable('setlist_items') || Schema::create('setlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setlist_id')->constrained()->onDelete('cascade');
            $table->foreignId('song_id')->constrained()->onDelete('cascade');
            $table->string('key')->nullable();
            $table->text('vocal_notes')->nullable();
            $table->text('band_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setlist_items');
        Schema::dropIfExists('songs');
        Schema::dropIfExists('setlists');
        Schema::dropIfExists('service_team_user');
        Schema::dropIfExists('services');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('users');
    }
};

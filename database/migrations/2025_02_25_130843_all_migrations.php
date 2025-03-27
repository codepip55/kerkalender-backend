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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->longText('refreshToken')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Create teams table (general teams, not service-specific)
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Create team_user table (users belonging to general teams)
        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->timestamps();
        });

        // Create setlists table
        Schema::create('setlists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        // Create services table
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('service_manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('setlist_id')->nullable()->constrained('setlists')->onDelete('cascade');
            $table->timestamps();
        });

        // After creating the tables, you can add the `service_id` foreign key to `setlists` later
        Schema::table('setlists', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
        });

        // Create service_teams table (teams within a specific service)
        Schema::create('service_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // Create positions table (positions within a service team)
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_team_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // Create position_members table (users assigned to positions in service teams)
        Schema::create('position_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['accepted', 'denied', 'waiting'])->default('waiting');
            $table->timestamps();
        });

        // Create songs table
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('artist');
            $table->string('spotify_link')->nullable();
            $table->timestamps();
        });

        // Create setlist_items table
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

    public function down(): void
    {
        Schema::dropIfExists('setlist_items');
        Schema::dropIfExists('setlists');
        Schema::dropIfExists('songs');
        Schema::dropIfExists('position_members');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('service_teams');
        Schema::dropIfExists('services');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('users');
    }
};

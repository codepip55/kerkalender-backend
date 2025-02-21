<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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

        Schema::hasTable('team_user') || Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->timestamps();
        });

        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'service_manager_id')) {
                $table->foreignId('service_manager_id')->nullable()->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user');
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['service_manager_id']);
            $table->dropColumn('service_manager_id');
        });
        Schema::dropIfExists('users');
    }
};

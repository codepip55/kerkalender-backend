<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Web Dev 01',
            'email' => 'webdev01@pepijncolenbrander.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Web Dev 02',
            'email' => 'webdev02@pepijncolenbrander.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Web Dev 03',
            'email' => 'webdev03@pepijncolenbrander.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Web Dev 04',
            'email' => 'webdev04@pepijncolenbrander.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Web Dev 05',
            'email' => 'webdev05@pepijncolenbrander.com',
            'password' => Hash::make('password'),
        ]);
    }
}

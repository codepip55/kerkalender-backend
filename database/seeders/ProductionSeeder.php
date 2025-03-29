<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => 0,
            'name' => 'System Admin',
            'email' => 'kerkalenderadmin@pepijncolenbrander.com',
            'password' => Hash::make(env('ADMIN_ACCOUNT_PASSWORD')),
        ]);
    }
}

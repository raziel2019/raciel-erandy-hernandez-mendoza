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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Raciel',
            'email' => 'razieltest@gmail.com',
            'img_profile' => 'testimg.png',
            'password' => Hash::make('1234567890'),
            'phone' => '1234567890',
        ]);
    }
}

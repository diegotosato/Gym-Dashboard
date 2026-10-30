<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Diego Tosato',
            'email' => 'tosato1998@gmail.com',
            'password' => Hash::make('triplete'),
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Riccardo Pistolato',
            'email' => 'riccardo@gmail.com',
            'password' => Hash::make('triplete'),
            'role_id' => 2,
        ]);
    }
}

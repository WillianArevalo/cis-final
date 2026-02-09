<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'awillianernesto@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Becado',
            'email' => 'becado@example.com',
            'role' => 'becado',
            'password' => bcrypt('password'),
        ]);
    }
}

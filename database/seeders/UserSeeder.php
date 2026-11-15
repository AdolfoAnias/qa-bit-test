<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->delete();

        User::create([
            'name' => 'Adolfo Gutierrez',
            'email' => 'adolfo@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'María Pérez',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Carlos Ramírez',
            'email' => 'carlos@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Laura Sánchez',
            'email' => 'laura@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}

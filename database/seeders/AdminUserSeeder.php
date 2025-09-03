<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'francescovirgillito97@gmail.com'], // Cerca per email
            [
                'nome' => 'Francesco',
                'cognome' => 'Virgillito', 
                'username' => 'virgillito',
                'codice_fiscale' => 'VRGFNC97E21G371Y',
                'password' => Hash::make('Papino61'),
                'email_verified_at' => now(),
                'is_admin' => true,
                'role' => 'admin',
            ]
        );

        User::create([
            'nome' => 'Mario',
            'cognome' => 'Rossi',
            'username' => 'mariorossi',
            'codice_fiscale' => 'RSSMRA85M01H501U',
            'email' => 'mario.rossi@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => false,
            'role' => 'user',
        ]);
    }
}

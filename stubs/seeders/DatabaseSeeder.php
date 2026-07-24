<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin-Benutzer anlegen (Login: admin@dauercamping.local / password)
        User::firstOrCreate(
            ['email' => 'admin@dauercamping.local'],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('password'),
                'role'              => 'admin',
                'aktiv'             => true,
                'email_verified_at' => now(),
            ]
        );
    }
}

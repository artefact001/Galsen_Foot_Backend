<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Créer le rôle admin si il n'existe pas

        // Créer l'utilisateur admin
        $user = User::firstOrCreate(
            ['email' => 'cheikhsane202@gmail.com'],
            [
                'nom' => 'Cheikh Sane',
                'password' => Hash::make('passer123'),
            ]
        );

        // Attacher le rôle admin à l'utilisateur
    }
}

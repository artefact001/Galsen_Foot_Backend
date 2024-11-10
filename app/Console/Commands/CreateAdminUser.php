<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'create:admin-user';
    protected $description = 'Create admin, zone, and team users with their respective roles';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Créer les rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $zoneRole = Role::firstOrCreate(['name' => 'zone']);
        $teamRole = Role::firstOrCreate(['name' => 'equipe']);

        // Créer l'utilisateur admin et lui attribuer le rôle
        $adminUser = User::firstOrCreate(
            ['email' => 'cheikhsane656@gmail.com'],
            [
                'nom' => 'Cheikh Sane',
                'password' => Hash::make('passer123'),
            ]
        );
        $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);

        // Créer l'utilisateur zone et lui attribuer le rôle
        $zoneUser = User::firstOrCreate(
            ['email' => 'cheikhsane@gmail.com'],
            [
                'nom' => 'Cheikh Sane',
                'password' => Hash::make('passer123'),
            ]
        );
        $zoneUser->roles()->syncWithoutDetaching([$zoneRole->id]);

        // Créer l'utilisateur équipe et lui attribuer le rôle
        $teamUser = User::firstOrCreate(
            ['email' => 'cheikh@gmail.com'],
            [
                'nom' => 'Cheikh',
                'password' => Hash::make('passer123'),
            ]
        );
        $teamUser->roles()->syncWithoutDetaching([$teamRole->id]);

        $this->info('Admin, Zone, and Team users created successfully with their respective roles.');
    }
}

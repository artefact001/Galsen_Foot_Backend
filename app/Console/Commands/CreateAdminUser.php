<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'create:admin-user';
    protected $description = 'Create an admin user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Créer le rôle admin si il n'existe pas
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Créer l'utilisateur admin
        $user = User::firstOrCreate(
            ['email' => 'cheikhsane656@gmail.com'],
            [
                'name' => 'Cheikh Sane',
                'password' => Hash::make('passer123'),
            ]
        );

        // Attacher le rôle admin à l'utilisateur
        $user->roles()->sync([$role->id]);

        $this->info('Admin user created successfully.');
    }
}

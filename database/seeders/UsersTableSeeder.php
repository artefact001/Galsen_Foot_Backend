<?php  

namespace Database\Seeders;  

use App\Models\User;  
use Spatie\Permission\Models\Role;  
use Illuminate\Database\Seeder;  
use Illuminate\Support\Facades\Hash;  

class UsersTableSeeder extends Seeder  
{  
    public function run(): void  
    {  
        // Création des rôles  
        $roles = ['admin', 'zone', 'equipe'];  

        // Assurez-vous que les rôles existent dans la base de données  
        foreach ($roles as $roleName) {  
            // Role::firstOrCreate(['name' => $roleName]);  
        }  

        // Création des utilisateurs avec les rôles correspondants  
        $users = [  
            [   'email' => 'Cheikhsane656@gmail.com',  
                'nom' => 'Cheikh Tidiane Sane',  
                'password' => Hash::make('password'),  
                'role' => 'admin',  
            ],  

            [  
                 'email' => 'Cheikh@gmail.com',  
                'nom' => 'Cheikh Tidiane Sane',  
                'password' => Hash::make('password'),  
                'role' => 'zone',  
            ],  
            [  
                'email' => 'souleymane9700@gmail.com',  
                'nom' => 'Souleymane',  
                'password' => Hash::make('password'),  
                'role' => 'zone',  
            ],  
            [  
                'email' => 'equipe@gmail.com',  
                'nom' => 'Barro Amadou',  
                'password' => Hash::make('password'),  
                'role' => 'equipe',  
            ],  
            [  
                'nom' => 'equipe1',  
                'email' => 'equipe@example.com',  
                'password' => Hash::make('password'),  
                'role' => 'Equipe',  
            ],  
        ];  

        foreach ($users as $userData) {  
            $user = User::firstOrCreate(  
                ['email' => $userData['email']],  
                ['nom' => $userData['nom'], 'password' => $userData['password']]  
            );  

            // Récupérer le rôle correspondant au nom spécifié dans la base de données  
            // $role = Role::where('name', $userData['role'])->first();

            // Si le rôle existe, on l'assigne à l'utilisateur créé  
            // if ($role) {  
            //     $user->assignRole($role);  
            // }  
        }  
    }  
}
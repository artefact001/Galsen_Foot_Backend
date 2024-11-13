<?php  

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'zone', 'equipe'];
        
        foreach ($roles as $role) {
            for ($i = 1; $i <= 10; $i++) {
                DB::table('users')->insert([
                    'nom' => "User{$i}_{$role}",
                    'email' => "user{$i}_{$role}@example.com",
                    'password' => Hash::make('password'), // Mot de passe haché
                    'role' => $role,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                    'photo_profile' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('articles')->insert([
            [
                'titre' => 'Championnat National',
                'contenu' => 'azertyuio',
                'user_id' => 1, // Assurez-vous que cet utilisateur existe dans la table users
                // 'zone_id'=> 1,
                'file_path' => 'path/to/file.jpg', // Chemin du fichier
            
            ],
             [
                'titre' => 'Championnat National',
                'contenu' => 'azertyuio',
                'user_id' => 1, // Assurez-vous que cet utilisateur existe dans la table users
                // 'zone_id'=> 1,
                'file_path' => 'path/to/file.jpg', // Chemin du fichier
           
            ],
             [
                'titre' => 'Championnat National',
                'contenu' => 'azertyuio',
                'user_id' => 1, // Assurez-vous que cet utilisateur existe dans la table users
                // 'zone_id'=> 1,
                'file_path' => 'path/to/file.jpg', // Chemin du fichier
             
            ],
            // Ajoutez d'autres articles si nécessaire
        ]);
    }
}

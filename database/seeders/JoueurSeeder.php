<?php  

namespace Database\Seeders;  

use Illuminate\Database\Seeder;  
use Illuminate\Support\Facades\DB;  

class JoueurSeeder extends Seeder  
{  
    /**  
     * Run the database seeds.  
     *  
     * @return void  
     */  
    public function run()  
    {  
        $prenoms = ['Moussa', 'Aissatou', 'Sidy', 'Fatou', 'Cheikh', 'Mariama', 'Ousmane', 'Ndeye', 'Ibrahima', 'Diatou'];  
        $noms = ['Diallo', 'Sow', 'Ba', 'Faye', 'Gueye', 'Diop', 'Kane', 'Lamine', 'Sy', 'Ndiaye'];  

        // Récupérer les IDs d'équipes et catégories  
        $equipes = DB::table('equipes')->pluck('id')->toArray();  
        $categories = DB::table('categories')->pluck('id')->toArray();  

        for ($i = 0; $i < 25; $i++) {  
            DB::table('joueurs')->insert([  
                'nom' => $prenoms[array_rand($prenoms)] . ' ' . $noms[array_rand($noms)],  
                'age' => rand(18, 35),  
                'licence' => 'LIC-' . strtoupper(uniqid()),  
                'equipe_id' => $equipes[array_rand($equipes)],  
                'categorie_id' => $categories[array_rand($categories)],  
                'created_at' => now(),  
                'updated_at' => now(),  
            ]);  
        }  
    }  
}
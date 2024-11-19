<?php  

namespace Database\Seeders;  

use Illuminate\Database\Seeder;  
use Illuminate\Support\Facades\DB;  

class ReclamationsSeeder extends Seeder  
{  
    /**  
     * Run the database seeds.  
     *  
     * @return void  
     */  
    public function run()  
    {  
        $equipes = DB::table('equipes')->pluck('id')->toArray();  

        foreach ($equipes as $equipe_id) {  
            for ($i = 0; $i < 3; $i++) {  // Créer 3 réclamations par équipe  
                DB::table('reclamations')->insert([  
                    'equipe_id' => $equipe_id,  
                    'description' => 'Description de la réclamation ' . ($i + 1) . ' pour l\'équipe ' . $equipe_id,  
                    'statut' => 'en_attente',  
                    'created_at' => now(),  
                    'updated_at' => now(),  
                ]);  
            }  
        }  
    }  
}
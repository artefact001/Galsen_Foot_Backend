<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Competition;
use App\Models\Tirage;
class TirageSeeder extends Seeder
        {
            /**
             * Run the database seeds.
             */
            public function run(): void
            {
            // Assurez-vous qu'il existe des compétitions
            $competition1 = Competition::find(1); // ID 1 doit exister
            $competition2 = Competition::find(2); // ID 2 doit exister
        
            if ($competition1 && $competition2) {
                Tirage::create([
                    'competition_id' => $competition1->id,
                    'phase' => ["Phase de groupes", "Quarts de finale", "Demi-finales", "Finale"],
                    'poul' => ["Poule A" => ["Equipe 1", "Equipe 4", "Equipe 7", "Equipe 2"], "Poule B" => ["Equipe 5", "Equipe 6", "Equipe 3", "Equipe 8"]],
                ]);
        
                Tirage::create([
                    'competition_id' => $competition2->id,
                    'phase' => ["Phase éliminatoire", "Quarts de finale", "Demi-finales", "Finale"],
                    'poul' => ["Poule A" => ["Equipe 5", "Equipe 6"], "Poule B" => ["Equipe 7", "Equipe 8"]],
                ]);
            } else {
                throw new \Exception('Les compétitions nécessaires pour les tirages n\'ont pas été trouvées.');
            }
        }
        
        
        
        }
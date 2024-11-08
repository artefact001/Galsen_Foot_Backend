<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TirageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tirages')->insert([
            [
                'competition_id' => 1,  // Assurez-vous que la compétition 1 existe dans la table 'competitions'
                'phase' => json_encode(['Phase de groupes', 'Quarts de finale', 'Demi-finales', 'Finale']),  // Phases de la compétition
                'poul' => json_encode(['Poule A' => ['Equipe 1', 'Equipe 2'], 'Poule B' => ['Equipe 3', 'Equipe 4']]),  // Poules et équipes
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'competition_id' => 2,  // Compétition 2
                'phase' => json_encode(['Phase éliminatoire', 'Quarts de finale', 'Demi-finales', 'Finale']),
                'poul' => json_encode(['Poule A' => ['Equipe 5', 'Equipe 6'], 'Poule B' => ['Equipe 7', 'Equipe 8']]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

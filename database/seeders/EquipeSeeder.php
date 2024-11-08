<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('equipes')->insert([
            [
                'nom' => 'Équipe A',
                'logo' => 'equipe_a_logo.png',
                'date_creer' => '2020-03-15',
                'zone_id' => 1,  // Assurez-vous que la zone 1 existe dans la table 'zones'
                'user_id' => 1,  // Assurez-vous que l'utilisateur 1 existe dans la table 'users'
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe B',
                'logo' => 'equipe_b_logo.png',
                'date_creer' => '2021-07-10',
                'zone_id' => 1,
                'user_id' => null,  // Pas de gestionnaire attribué
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe C',
                'logo' => 'equipe_c_logo.png',
                'date_creer' => '2019-09-25',
                'zone_id' => 1,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe D',
                'logo' => 'equipe_d_logo.png',
                'date_creer' => '2022-01-11',
                'zone_id' => 1,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe E',
                'logo' => 'equipe_e_logo.png',
                'date_creer' => '2018-05-20',
                'zone_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe F',
                'logo' => 'equipe_f_logo.png',
                'date_creer' => '2023-03-05',
                'zone_id' => 1,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe G',
                'logo' => 'equipe_g_logo.png',
                'date_creer' => '2022-08-15',
                'zone_id' => 1,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe H',
                'logo' => 'equipe_h_logo.png',
                'date_creer' => '2021-11-30',
                'zone_id' => 1,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe I',
                'logo' => 'equipe_i_logo.png',
                'date_creer' => '2020-07-25',
                'zone_id' => 1,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe J',
                'logo' => 'equipe_j_logo.png',
                'date_creer' => '2019-04-10',
                'zone_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe K',
                'logo' => 'equipe_k_logo.png',
                'date_creer' => '2022-10-05',
                'zone_id' => 1,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe L',
                'logo' => 'equipe_l_logo.png',
                'date_creer' => '2021-06-15',
                'zone_id' => 1,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe M',
                'logo' => 'equipe_m_logo.png',
                'date_creer' => '2023-02-20',
                'zone_id' => 1,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe N',
                'logo' => 'equipe_n_logo.png',
                'date_creer' => '2018-12-30',
                'zone_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Équipe O',
                'logo' => 'equipe_o_logo.png',
                'date_creer' => '2020-09-12',
                'zone_id' => 1,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

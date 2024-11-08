<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MatcheRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'competition_id' => ['required', 'integer', 'exists:competitions,id'],
            // 'equipe1_id' => ['required', 'integer', 'exists:equipes,id'],
            // 'equipe2_id' => [
            //     'required',
            //     'integer',
            //     'exists:equipes,id',
            //     'different:equipe1_id'
            // ],
            // 'score_equipe1' => ['required', 'integer', 'min:0'],
            // 'score_equipe2' => ['required', 'integer', 'min:0'],
            // 'date_matche' => ['required', 'date'],

            // // Validation des buteurs
            // 'buteurs' => ['required', 'array'],
            // 'buteurs.equipe1' => ['required', 'array'],
            // 'buteurs.equipe1.*.joueur_id' => ['required', 'integer', 'exists:joueurs,id'],
            // 'buteurs.equipe1.*.minute' => ['required', 'integer', 'min:1', 'max:120'],
            // 'buteurs.equipe2' => ['required', 'array'],
            // 'buteurs.equipe2.*.joueur_id' => ['required', 'integer', 'exists:joueurs,id'],
            // 'buteurs.equipe2.*.minute' => ['required', 'integer', 'min:1', 'max:120'],

            // // Validation des passeurs
            // 'passeurs' => ['required', 'array'],
            // 'passeurs.equipe1' => ['required', 'array'],
            // 'passeurs.equipe1.*.joueur_id' => ['required', 'integer', 'exists:joueurs,id'],
            // 'passeurs.equipe1.*.minute' => ['required', 'integer', 'min:1', 'max:120'],
            // 'passeurs.equipe2' => ['required', 'array'],
            // 'passeurs.equipe2.*.joueur_id' => ['required', 'integer', 'exists:joueurs,id'],
            // 'passeurs.equipe2.*.minute' => ['required', 'integer', 'min:1', 'max:120'],

            // // Validation des cartons
            // 'cartons' => ['required', 'array'],
            // 'cartons.jaunes' => ['present', 'array'],
            // 'cartons.jaunes.*.joueur_id' => ['required', 'integer', 'exists:joueurs,id'],
            // 'cartons.jaunes.*.equipe_id' => ['required', 'integer', 'exists:equipes,id'],
            // 'cartons.jaunes.*.minute' => ['required', 'integer', 'min:1', 'max:120'],
            // 'cartons.rouges' => ['present', 'array'],
            // 'cartons.rouges.*.joueur_id' => ['required', 'integer', 'exists:joueurs,id'],
            // 'cartons.rouges.*.equipe_id' => ['required', 'integer', 'exists:equipes,id'],
            // 'cartons.rouges.*.minute' => ['required', 'integer', 'min:1', 'max:120'],

            // 'homme_du_matche' => ['required', 'integer', 'exists:joueurs,id'],
            // 'resultat' => ['required', Rule::in(['gagne', 'nul', 'perdu'])],
        ];
    }

    public function messages(): array
    {
        return [
            'equipe2_id.different' => "L'équipe visiteuse doit être différente de l'équipe locale",
            'buteurs.equipe1.*.joueur_id.exists' => "L'un des buteurs de l'équipe 1 n'existe pas",
            'buteurs.equipe2.*.joueur_id.exists' => "L'un des buteurs de l'équipe 2 n'existe pas",
            'passeurs.equipe1.*.joueur_id.exists' => "L'un des passeurs de l'équipe 1 n'existe pas",
            'passeurs.equipe2.*.joueur_id.exists' => "L'un des passeurs de l'équipe 2 n'existe pas",
            'cartons.jaunes.*.joueur_id.exists' => "L'un des joueurs ayant reçu un carton jaune n'existe pas",
            'cartons.rouges.*.joueur_id.exists' => "L'un des joueurs ayant reçu un carton rouge n'existe pas",
            'cartons.*.equipe_id.exists' => "L'une des équipes référencées pour les cartons n'existe pas",
            'homme_du_matche.exists' => "Le joueur sélectionné comme homme du match n'existe pas",
            'resultat.in' => 'Le résultat doit être soit "gagne", "nul" ou "perdu"',
        ];
    }
}

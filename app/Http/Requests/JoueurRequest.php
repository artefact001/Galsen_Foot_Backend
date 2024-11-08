<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoueurRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true; // Assurez-vous que les autorisations sont correctes
    }

    /**
     * Règles de validation pour cette requête.
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:35',
            'licence' => 'required|string|unique:joueurs,licence',
            'equipe_id' => 'required|exists:equipes,id',
            'categorie_id' => 'required|exists:categories,id',
        ];
    }
}

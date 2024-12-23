<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipeRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Peut être modifié pour limiter l'accès
    }

    /**
     * Règles de validation pour la création/mise à jour d'une équipe.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'date_creer' => 'required|date',
            'zone_id' => 'required|integer|exists:zones,id',
            'user_id' => 'nullable|integer|exists:users,id'
        ];
    }

    /**
     * Messages d'erreurs personnalisés pour les validations.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nom.required' => 'Le nom de l\'équipe est requis.',
            'date_creer.required' => 'La date de création est requise.',
            'zone_id.exists' => 'La zone sélectionnée doit exister.',
            'user_id.exists' => 'L\'utilisateur sélectionné doit exister.',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    use HasFactory;

    // Les attributs qui peuvent être assignés en masse
    protected $fillable = ['nom', 'logo', 'date_creer', 'zone_id', 'user_id'];

    /**
     * Relation avec le modèle Zone.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Relation avec le modèle User (gestionnaire de l'équipe).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation un à plusieurs avec le modèle Joueur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function joueurs()
    {
        return $this->hasMany(Joueur::class);
    }

    /**
     * Relation plusieurs à plusieurs avec le modèle Competition.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function competitions()
    {
        return $this->belongsToMany(Competition::class, 'competition_equipe');
    }

    /**
     * Relation un à plusieurs avec le modèle Match pour les matchs en tant qu'équipe locale.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matchesLocaux()
    {
        return $this->hasMany(Matche::class, 'equipe1_id'); // Remplacez par le nom correct de la colonne
    }

    /**
     * Relation un à plusieurs avec le modèle Match pour les matchs en tant qu'équipe visiteur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matchesVisiteurs()
    {
        return $this->hasMany(Matche::class, 'equipe2_id'); // Remplacez par le nom correct de la colonne
    }

    /**
     * Relation un à plusieurs avec le modèle Reclamation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reclamations()
    {
        return $this->hasMany(Reclamation::class);
    }
}

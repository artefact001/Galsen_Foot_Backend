<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matche extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'competition_id',
        'equipe1_id',
        'equipe2_id',
        'date_matche',
         'lieux',
        'statut',

        'score_equipe1',
        'score_equipe2',
        'buteurs',
        'passeurs',
        'homme_du_matche',
        'cartons',
        'resultat',
    ];

    protected $casts = [
        'buteurs' => 'array',
        'passeurs' => 'array',
        'cartons' => 'array',
        'date_matche' => 'datetime',
        'equipe1_is_winner' => 'boolean',
        'equipe2_is_winner' => 'boolean'
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function equipe1(): BelongsTo
    {
        return $this->belongsTo(Equipe::class, 'equipe1_id');
    }

    public function equipe2(): BelongsTo
    {
        return $this->belongsTo(Equipe::class, 'equipe2_id');
    }

    public function hommeDuMatch(): BelongsTo
    {
        return $this->belongsTo(Joueur::class, 'homme_du_matche');
    }

    public function classement(): BelongsTo

    {
       return $this->belongsTo(classement::class, 'classement_id');

    }
}

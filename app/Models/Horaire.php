<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'salle_id',
        'jour',
        'heure_ouverture',
        'heure_fermeture',
    ];

    protected $casts = [
        'heure_ouverture' => 'datetime',
        'heure_fermeture' => 'datetime',
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}

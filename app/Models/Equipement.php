<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;

    protected $fillable = [
        'salle_id',
        'nom',
        'description',
        'quantite',
        'etat',
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}

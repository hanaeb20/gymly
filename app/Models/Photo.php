<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'chemin',
        'salle_id'
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}

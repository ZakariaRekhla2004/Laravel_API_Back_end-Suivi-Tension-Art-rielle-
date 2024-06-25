<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;


class Tension_Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'Systolique',
        'Diastolique',
        'date_Examen',
        'heure_Examen','Etat'
    ];
    public function patient()
    {
        return $this->belongsTo(User::class, 'id');
    }
}

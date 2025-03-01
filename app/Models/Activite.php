<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;


class Activite extends Model
{
    use HasFactory;

    protected $fillable = [
        "Poids",
        'Taille',
        'Imc',
        'dateExam',
        "patient_id"
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}

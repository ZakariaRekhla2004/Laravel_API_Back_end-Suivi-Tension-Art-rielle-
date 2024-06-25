<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'medecin_id',
        'patient_id',
        'heure',
        'date',
        'status',
        
    ];

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class notifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'recever',
        'sender',
        'message',
        'dossier_id',
        'Appoint_id',
        'read',
        'title',

    ];
    public function patient()
    {
        return $this->belongsTo(User::class, 'recever');
    }
    public function medecin()
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }
}

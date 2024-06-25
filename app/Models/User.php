<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;


class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'date_naissance',
        'status',
        'id_medecin',
    ];
    public function medecin()
    {
        return $this->belongsTo(User::class, 'id_medecin', '_id');
    }

    // Relationship to patients of the medecin
    public function patients()
    {
        return $this->hasMany(User::class, 'id_medecin', '_id');
    }

    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'medecin_id');
    }
    public function dossierPatient()
    {
        return $this->hasOne(Dossier::class, 'patient_id');
    }
    public function exams()
    {
        return $this->hasMany(Tension_Exam::class, 'id');
    }
    public function latestExam()
    {
        return $this->hasOne(Tension_Exam::class, 'id')->latest();
    }
    public function activites()
    {
        return $this->hasMany(Activite::class, 'patient_id');
    }
    public function notifications()
    {
        return $this->hasMany(notifications::class, 'patient_id');
    }
    public function sentNotifications()
    {
        return $this->hasMany(notifications::class, 'medecin_id');
    }

    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
    /**
     * Relationship to the appointments as a medecin (doctor).
     */
    public function medecinAppointments()
    {
        return $this->hasMany(Appointment::class, 'medecin_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

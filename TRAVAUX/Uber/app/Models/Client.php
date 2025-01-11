<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'client';
    protected $primaryKey = 'idclient';
    public $timestamps = false;

    protected $fillable = [
        'identreprise',
        'idadresse',
        'genreuser',
        'nomuser',
        'prenomuser',
        'datenaissance',
        'telephone',
        'emailuser',
        'motdepasseuser',
        'photoprofile',
        'souhaiterecevoirbonplan',
        'mfa_activee',
        'typeclient',
    ];

    protected $casts = [
        'mfa_activee' => 'boolean',
    ];

    public function otps()
    {
        return $this->hasMany(Otp::class, 'idclient', 'idclient');
    }

    public function hasMfaEnabled()
    {
        return $this->mfa_activee;
    }

    public function routeNotificationForMail()
    {
        return $this->emailuser;
    }





    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'identreprise');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }

    public function cartesBancaires()
    {
        return $this->belongsToMany(CarteBancaire::class, 'appartient_2', 'idclient', 'idcb');
    }

    public function lieuFavoris()
    {
        return $this->hasMany(LieuFavori::class, 'idclient', 'idclient');
    }

    public function courses()
    {
        return $this->hasManyThrough(
            Course::class,
            Reservation::class,
            'idclient',
            'idreservation',
            'idclient',
            'idreservation'
        );
    }




    public function isUberClient()
    {
        return $this->typeclient === 'Uber';
    }

    public function isUberEatsClient()
    {
        return $this->typeclient === 'Uber Eats';
    }
}

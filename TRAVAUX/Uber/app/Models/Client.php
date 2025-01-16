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
        'last_connexion',
        'demande_suppression',
    ];

    protected $casts = [
        'souhaiterecevoirbonplan' => 'boolean',
        'mfa_activee' => 'boolean',
        'demande_suppression' => 'boolean',
        'last_connexion' => 'datetime',
    ];

    // Relations
    public function otps()
    {
        return $this->hasMany(Otp::class, 'idclient', 'idclient');
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'identreprise', 'identreprise');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
    }

    public function cartesBancaires()
    {
        return $this->belongsToMany(
            CarteBancaire::class,
            'client_carte',
            'idclient',
            'idcb'
        );
    }

    public function lieuFavoris()
    {
        return $this->hasMany(LieuFavori::class, 'idclient', 'idclient');
    }

    public function paniers()
    {
        return $this->hasMany(Panier::class, 'idclient', 'idclient');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idclient', 'idclient');
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

    // Accessors
    public function isUberClient()
    {
        return $this->typeclient === 'Uber';
    }

    public function isUberEatsClient()
    {
        return $this->typeclient === 'Uber Eats';
    }

    public function demandeSuppression()
    {
        return $this->demande_suppression;
    }

    // Methods
    public function markAsDeleted()
    {
        $this->demande_suppression = true;
        $this->save();
    }

    public function updateLastConnexion()
    {
        $this->last_connexion = now();
        $this->save();
    }

    public function deleteClient()
    {
        if ($this->last_connexion <= now()->subYears(3)) {
            $this->demande_suppression = true;
            $this->save();
        }

        return true;
    }
}

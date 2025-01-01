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
    ];

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

    public function courses()
    {
        return $this->hasManyThrough(
            Course::class,
            Reservation::class,
            'idclient',        // Foreign key on Reservation table...
            'idreservation',   // Foreign key on Course table...
            'idclient',        // Local key on Client table...
            'idreservation'    // Local key on Reservation table...
        );
    }
}

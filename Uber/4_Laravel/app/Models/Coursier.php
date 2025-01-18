<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coursier extends Model
{
    use HasFactory;

    protected $table = "coursier";
    protected $primaryKey = "idcoursier";
    public $timestamps = false;

    protected $fillable = [
        'idcoursier',
        'identreprise',
        'idadresse',
        'genreuser',
        'nomuser',
        'prenomuser',
        'datenaissance',
        'telephone',
        'emailuser',
        'motdepasseuser',
        'numerocartevtc',
        'iban',
        'datedebutactivite',
        'notemoyenne',
    ];

    protected $hidden = [
        'motdepasseuser',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'idcoursier');
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'identreprise');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
    }

    public function entretien()
    {
        return $this->hasOne(Entretien::class, 'idcoursier', 'idcoursier');
    }

    public function vehicules()
    {
        return $this->hasMany(Vehicule::class, 'idcoursier', 'idcoursier');
    }

    public function horaires()
    {
        return $this->hasMany(Horaires::class, 'idcoursier', 'idcoursier');
    }
}

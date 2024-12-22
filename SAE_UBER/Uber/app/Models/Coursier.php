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

    public $incrementing = true;
    
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
        'idadresse',
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

    public function adresseDepart()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }
    protected $attributes = [
        'notemoyenne' => 0,
    ];
}

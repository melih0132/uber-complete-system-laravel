<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
{
    use HasFactory;

    protected $table = 'livreur';
    protected $primaryKey = 'idlivreur';
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
        'iban',
        'datedebutactivite',
        'notemoyenne',
    ];

    protected $casts = [
        'datenaissance' => 'date',
        'datedebutactivite' => 'date',
        'notemoyenne' => 'float',
    ];


    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'identreprise');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }

    public function horaires()
    {
        return $this->hasMany(Horaires::class, 'idlivreur', 'idlivreur');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'idlivreur');
    }
}

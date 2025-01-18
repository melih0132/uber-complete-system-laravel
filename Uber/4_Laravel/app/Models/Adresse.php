<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    use HasFactory;

    protected $table = 'adresse';
    protected $primaryKey = 'idadresse';
    public $timestamps = false;

    protected $fillable = [
        'libelleadresse',
        'idville',
    ];

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'idville', 'idville');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'idadresse', 'idadresse');
    }

    public function lieuFavoris()
    {
        return $this->hasMany(LieuFavori::class, 'idadresse', 'idadresse');
    }

    public function velos()
    {
        return $this->hasMany(Velo::class, 'idadresse', 'idadresse');
    }

    public function entreprises()
    {
        return $this->hasMany(Entreprise::class, 'idadresse', 'idadresse');
    }

    public function coursiers()
    {
        return $this->hasMany(Coursier::class, 'idadresse', 'idadresse');
    }
}

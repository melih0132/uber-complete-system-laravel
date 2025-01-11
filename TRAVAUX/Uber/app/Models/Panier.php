<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;
    protected $table = 'panier';
    protected $primaryKey = 'idpanier';
    public $timestamps = false;

    protected $fillable = [
        'idpanier',
        'idclient',
        'prix',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient', 'idclient');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'contient_2', 'idpanier', 'idproduit')
            ->withPivot('quantite', 'idetablissement');
    }

    public function etablissements()
    {
        return $this->hasManyThrough(
            Etablissement::class,
            'contient_2',
            'idpanier',
            'idetablissement',
            'idpanier',
            'idetablissement'
        )->distinct();
    }

    public function getTotalPrixAttribute()
    {
        return $this->produits->sum(function ($produit) {
            return $produit->pivot->quantite * $produit->prixproduit;
        });
    }

    public function hasProduit($produitId)
    {
        return $this->produits->contains('idproduit', $produitId);
    }
}

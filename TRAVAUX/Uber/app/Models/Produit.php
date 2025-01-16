<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = "produit";
    protected $primaryKey = "idproduit";
    public $timestamps = false;

    protected $fillable = [
        'idproduit',
        'nomproduit',
        'prixproduit',
        'imageproduit',
        'description'
    ];

    public function categories()
    {
        return $this->belongsToMany(
            CategorieProduit::class,
            'produit_categorie',
            'idproduit',
            'idcategorie'
        );
    }

    public function etablissements()
    {
        return $this->belongsToMany(
            Etablissement::class,
            'est_situe_a_2',
            'idproduit',
            'idetablissement'
        );
    }

    public function paniers()
    {
        return $this->belongsToMany(
            Panier::class,
            'contient_2',
            'idproduit',
            'idpanier'
        )->withPivot('quantite', 'idetablissement')
            ->as('pivot');
    }
}

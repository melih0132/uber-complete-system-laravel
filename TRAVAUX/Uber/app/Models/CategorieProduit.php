<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieProduit extends Model
{
    use HasFactory;

    protected $table = "categorie_produit";
    protected $primaryKey = "idcategorie";
    public $timestamps = false;

    protected $fillable = [
        'nomcategorie',
    ];

    public function produits()
    {
        return $this->belongsToMany(
            Produit::class,
            'produit_categorie',
            'idcategorie',
            'idproduit'
        );
    }
}

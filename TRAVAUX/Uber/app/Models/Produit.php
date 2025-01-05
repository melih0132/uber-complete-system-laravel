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

    public function etablissements()
    {
        return $this->belongsToMany(Etablissement::class, 'est_situe_a_2', 'idproduit', 'idetablissement');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'contient_2', 'idpanier', 'idproduit');
    }
}

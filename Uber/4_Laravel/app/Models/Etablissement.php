<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    use HasFactory;

    protected $table = 'etablissement';
    protected $primaryKey = 'idetablissement';
    public $timestamps = false;

    protected $fillable = [
        'idrestaurateur',
        'typeetablissement',
        'idadresse',
        'nometablissement',
        'description',
        'imageetablissement',
        'livraison',
        'aemporter',
    ];

    public function responsables()
    {
        return $this->belongsToMany(
            ResponsableEnseigne::class,
            'gestion_etablissement',
            'idetablissement',
            'idresponsable'
        );
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }

    public function horaires()
    {
        return $this->hasMany(Horaires::class, 'idetablissement', 'idetablissement');
    }

    public function categories()
    {
        return $this->belongsToMany(
            CategoriePrestation::class,
            'a_comme_categorie',
            'idetablissement',
            'idcategorieprestation'
        );
    }

    public function produits()
    {
        return $this->belongsToMany(
            Produit::class,
            'est_situe_a_2',
            'idetablissement',
            'idproduit'
        );
    }

    public function commandes()
    {
        return $this->hasManyThrough(
            Commande::class,
            Panier::class,
            'idetablissement',
            'idpanier',
            'idetablissement',
            'idpanier'
        );
    }

    // Méthodes
    public static function getByRestaurateur($restaurateurId)
    {
        return self::where('idrestaurateur', $restaurateurId)->get();
    }
}

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
        'idresponsable',
        'typeetablissement',
        'idadresse',
        'nometablissement',
        'description',
        'imageetablissement',
        'livraison',
        'aemporter',
    ];

    public function responsable()
    {
        return $this->belongsTo(ResponsableEnseigne::class, 'idresponsable');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }

    public function horaires()
    {
        return $this->hasMany(Horaires::class, 'idetablissement');
    }

    public function categories()
    {
        return $this->belongsToMany(CategoriePrestation::class, 'a_comme_categorie', 'idetablissement', 'idcategorieprestation');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'est_situe_a_2', 'idetablissement', 'idproduit');
    }

    public static function getByResponsable($responsableId)
    {
        return self::where('idresponsable', $responsableId)->get();
    }
}

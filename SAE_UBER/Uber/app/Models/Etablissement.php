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
        'idetablissement',
        'typeetablissement',
        'idadresse',
        'nometablissement',
        'description',
        'imageetablissement',
        'livraison',
        'aemporter',
    ];

    public function categories()
    {
        return $this->belongsToMany(Categorie_prestation::class, 'a_comme_categorie', 'idetablissement', 'idcategorieprestation');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }
}

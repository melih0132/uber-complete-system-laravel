<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriePrestation extends Model
{
    use HasFactory;

    protected $table = "categorie_prestation";
    protected $primaryKey = "idcategorieprestation";
    public $timestamps = false;

    protected $fillable = [
        'libellecategorieprestation',
        'descriptioncategorieprestation',
        'imagecategorieprestation',
    ];

    public function etablissements()
    {
        return $this->belongsToMany(
            Etablissement::class,
            'a_comme_categorie',
            'idcategorieprestation',
            'idetablissement'
        );
    }
}

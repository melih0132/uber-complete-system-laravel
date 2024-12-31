<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie_prestation extends Model
{
    use HasFactory;
    protected $table = "categorie_prestation";
    protected $primaryKey = "idcategorieprestation";
    public $timestamps = false;

    protected $fillable = [
        'libellecategorieprestation',
    ];
}

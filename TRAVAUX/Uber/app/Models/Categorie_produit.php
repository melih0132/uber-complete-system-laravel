<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie_produit extends Model
{
    use HasFactory;
    protected $table = "categorie_produit";
    protected $primaryKey = "idcategorieproduit";
    public $timestamps = false;
}

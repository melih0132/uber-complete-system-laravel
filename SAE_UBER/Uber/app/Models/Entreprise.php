<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;
    protected $table = "entreprise";
    protected $primaryKey = "identreprise";
    public $timestamps = false;

    protected $fillable = [
        'identreprise',
        'idadresse',
        'siretentreprise',
        'nomentreprise',
        'taille',
    ];

    public function client()
    {
        return $this->hasMany(Client::class, 'idclient');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }
}

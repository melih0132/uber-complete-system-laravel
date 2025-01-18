<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $table = 'entreprise';
    protected $primaryKey = 'identreprise';
    public $timestamps = false;

    protected $fillable = [
        'idadresse',
        'siretentreprise',
        'nomentreprise',
        'taille',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'identreprise');
    }

    public function coursiers()
    {
        return $this->hasMany(Coursier::class, 'identreprise');
    }

    public function livreurs()
    {
        return $this->hasMany(Livreur::class, 'identreprise');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }
}

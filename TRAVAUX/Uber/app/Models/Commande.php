<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $table = "commande";
    protected $primaryKey = "idcommande";
    public $timestamps = false;

    protected $fillable = [
        'idpanier',
        'idcoursier',
        'idadresse',
        'adr_idadresse',
        'prixcommande',
        'tempscommande',
        'heurecommande',
        'estlivraison',
        'statutcommande'
    ];

    public function panier()
    {
        return $this->belongsTo(Panier::class, 'idpanier');
    }

    public function adresseDepart()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }

    public function adresseDestination()
    {
        return $this->belongsTo(Adresse::class, 'adr_idadresse');
    }

    public function coursier()
    {
        return $this->belongsTo(Coursier::class, 'idcoursier');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient');
    }

    public function scopeLivraison($query)
    {
        return $query->where('estlivraison', true);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statutcommande', 'En attente');
    }
}

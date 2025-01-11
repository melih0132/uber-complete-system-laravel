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
        'idcb',
        'idadresse',
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

    public function adresseDestination()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }

    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'idlivreur');
    }

    public function carteBancaire()
    {
        return $this->belongsTo(CarteBancaire::class, 'idcb');
    }

    public function client()
    {
        return $this->hasOneThrough(
            Client::class,
            Panier::class,
            'idpanier',
            'idclient',
            'idpanier',
            'idclient'
        );
    }




    public function scopeLivraison($query)
    {
        return $query->where('estlivraison', true);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statutcommande', 'En attente');
    }

    public function scopeParClient($query, $clientId)
    {
        return $query->whereHas('panier', function ($q) use ($clientId) {
            $q->where('idclient', $clientId);
        });
    }
}

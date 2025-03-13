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
        'idlivreur',
        'idcb',
        'idadresse',
        'prixcommande',
        'tempscommande',
        'heurecreation',
        'heurecommande',
        'estlivraison',
        'statutcommande',
        'refus_demandee',
        'remboursement_effectue'
    ];

    // Relations
    public function panier()
    {
        return $this->belongsTo(Panier::class, 'idpanier');
    }

    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'idlivreur');
    }

    public function carteBancaire()
    {
        return $this->belongsTo(CarteBancaire::class, 'idcb');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
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

    // Méthodes
    public function scopeLivraison($query)
    {
        return $query->where('estlivraison', true);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statutcommande', 'En attente de paiement');
    }

    public function scopeParClient($query, $clientId)
    {
        return $query->whereHas('panier', function ($q) use ($clientId) {
            $q->where('idclient', $clientId);
        });
    }
}

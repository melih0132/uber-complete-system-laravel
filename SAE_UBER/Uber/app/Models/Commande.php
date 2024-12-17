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
}

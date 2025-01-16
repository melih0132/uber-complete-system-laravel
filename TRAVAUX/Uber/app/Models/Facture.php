<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $table = 'facture';
    protected $primaryKey = 'idfacture';
    public $timestamps = false;

    protected $fillable = [
        'idfacture',
        'idcommande',
        'idpays',
        'idclient',
        'montantreglement',
        'datefacture',
        'quantite',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'idcommande');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient');
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'idpays');
    }
}

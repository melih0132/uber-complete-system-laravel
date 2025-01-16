<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;
    protected $table = 'vehicule';
    protected $primaryKey = 'idvehicule';
    public $timestamps = false;

    protected $fillable = [
        'idcoursier',
        'immatriculation',
        'marque',
        'modele',
        'capacite',
        'accepteanimaux',
        'estelectrique',
        'estconfortable',
        'estrecent',
        'estluxueux',
        'couleur',
        'statusprocessuslogistique',
        'demandemodification',
        'demandemodificationeffectue',
    ];

    protected $casts = [
        'accepteanimaux' => 'boolean',
        'estelectrique' => 'boolean',
        'estconfortable' => 'boolean',
        'estrecent' => 'boolean',
        'estluxueux' => 'boolean',
        'demandemodificationeffectue' => 'boolean',
    ];

    public function coursier()
    {
        return $this->belongsTo(Coursier::class, 'idcoursier', 'idcoursier');
    }

    public function prestations()
    {
        return $this->belongsToMany(TypePrestation::class, 'a_comme_type', 'idvehicule', 'idprestation');
    }
}

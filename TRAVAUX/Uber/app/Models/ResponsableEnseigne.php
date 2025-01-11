<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsableEnseigne extends Model
{
    use HasFactory;

    protected $table = 'responsable_enseigne';
    protected $primaryKey = 'idresponsable';
    public $timestamps = false;

    protected $fillable = [
        'nomuser',
        'prenomuser',
        'telephone',
        'emailuser',
        'motdepasseuser',
    ];

    public function etablissements()
    {
        return $this->belongsToMany(
            Etablissement::class,
            'gestion_etablissement',
            'idresponsable',
            'idetablissement'
        );
    }
}

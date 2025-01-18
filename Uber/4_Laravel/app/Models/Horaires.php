<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horaires extends Model
{
    use HasFactory;

    protected $table = 'horaires';
    protected $primaryKey = 'idhoraires';
    public $timestamps = false;

    protected $fillable = [
        'idetablissement',
        'idcoursier',
        'idlivreur',
        'joursemaine',
        'heuredebut',
        'heurefin',
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'idetablissement');
    }

    public function coursier()
    {
        return $this->belongsTo(Coursier::class, 'idcoursier');
    }

    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'idlivreur');
    }
}

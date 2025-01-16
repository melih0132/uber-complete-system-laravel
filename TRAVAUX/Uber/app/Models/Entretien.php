<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entretien extends Model
{
    use HasFactory;
    protected $table = 'entretien';
    protected $primaryKey = 'identretien';
    public $timestamps = false;

    protected $fillable = [
        'idcoursier',
        'dateentretien',
        'status',
        'resultat',
        'rdvlogistiquedate',
        'rdvlogistiquelieu',
    ];

    protected $casts = [
        'dateentretien' => 'datetime',
        'rdvlogistiquedate' => 'datetime',
    ];

    public function coursier()
    {
        return $this->belongsTo(Coursier::class, 'idcoursier', 'idcoursier');
    }
}

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

    protected $casts = [
        'dateentretien' => 'datetime',
        'rdvlogistiquedate' => 'datetime',
    ];

    protected $fillable = ['idcourier', 'dateentretien', 'status'];

    public function coursier()
    {
        return $this->belongsTo(Coursier::class, 'idcoursier', 'idcoursier');
    }
}

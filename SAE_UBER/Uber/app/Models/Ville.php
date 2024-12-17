<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    use HasFactory;

    protected $table = 'ville';
    protected $primaryKey = 'idville';
    public $timestamps = false;

    protected $fillable = ['idville', 'nomville', 'idpays', 'idcodepostal'];

    public function codePostal()
    {
        return $this->belongsTo(Code_postal::class, 'idcodepostal', 'idcodepostal');
    }

    public function adresses()
    {
        return $this->hasMany(Adresse::class, 'idville', 'idville');
    }
}

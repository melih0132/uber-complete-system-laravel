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

    protected $fillable = [
        'nomville',
        'idpays',
        'idcodepostal',
    ];

    public function codePostal()
    {
        return $this->belongsTo(CodePostal::class, 'idcodepostal', 'idcodepostal');
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'idpays', 'idpays');
    }

    public function adresses()
    {
        return $this->hasMany(Adresse::class, 'idville', 'idville');
    }
}

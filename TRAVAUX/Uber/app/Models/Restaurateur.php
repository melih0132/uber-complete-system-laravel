<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurateur extends Model
{
    use HasFactory;

    protected $table = 'restaurateur';
    protected $primaryKey = 'idrestaurateur';
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
        return $this->hasMany(Etablissement::class, 'idrestaurateur', 'idrestaurateur');
    }
}

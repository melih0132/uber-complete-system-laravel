<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorairesLivreur extends Model
{
    use HasFactory;

    protected $table = 'horaires_livreur';
    protected $primaryKey = 'idhoraires_livreur';
    public $timestamps = false;

    protected $fillable = [
        'idlivreur',
        'joursemaine',
        'heuredebut',
        'heurefin',
    ];

    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'idlivreur');
    }
}

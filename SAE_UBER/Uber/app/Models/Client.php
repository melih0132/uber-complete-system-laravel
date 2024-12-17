<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'client';
    protected $primaryKey = 'idclient';
    public $timestamps = false;

    protected $fillable = [
        'idclient', 'identreprise', 'idadresse', 'nomuser', 'prenomuser', 'genreuser', 'datenaissance',
        'telephone', 'emailuser', 'motdepasseuser'
    ];

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }
}

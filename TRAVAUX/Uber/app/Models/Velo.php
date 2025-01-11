<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Velo extends Model
{
    use HasFactory;

    protected $table = 'velo';
    protected $primaryKey = 'idvelo';
    public $timestamps = false;

    protected $fillable = [
        'idvelo',
        'idadresse',
        'numerovelo',
        'estdisponible',
    ];

    public function startAddress()
    {
        return $this->belongsTo(Adresse::class, 'adr_idadresse', 'idadresse');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
    }
}

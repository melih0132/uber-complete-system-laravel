<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LieuFavori extends Model
{
    use HasFactory;

    protected $table = 'lieu_favori';
    protected $primaryKey = 'idlieufavori';
    public $timestamps = false;

    protected $fillable = [
        'idclient',
        'idadresse',
        'nomlieu'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient');
    }

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
    }
}

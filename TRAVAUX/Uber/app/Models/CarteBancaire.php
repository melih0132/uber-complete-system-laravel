<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarteBancaire extends Model
{
    use HasFactory;
    protected $table = 'carte_bancaire';
    protected $primaryKey = 'idcb';
    public $timestamps = false;

    protected $fillable = [
        'numerocb',
        'dateexpirecb',
        'cryptogramme',
        'typecarte',
        'typereseaux',
    ];

    protected $casts = [
        'numerocb' => 'string',
        'dateexpirecb' => 'date:Y-m-d',
        'cryptogramme' => 'integer',
    ];

    public function isExpired(): bool
    {
        return $this->dateexpirecb < now();
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'appartient_2', 'idcb', 'idclient');
    }
}

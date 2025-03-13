<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

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
        'cryptogramme' => 'string',
    ];

    public function isExpired(): bool
    {
        return $this->dateexpirecb < now();
    }

    public function clients()
    {
        return $this->belongsToMany(
            Client::class,
            'client_carte',
            'idcb',
            'idclient'
        );
    }

    public function getNumerocbAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function getCryptogrammeAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function setNumerocbAttribute($value)
    {
        $this->attributes['numerocb'] = Crypt::encryptString($value);
    }

    public function setCryptogrammeAttribute($value)
    {
        $this->attributes['cryptogramme'] = Crypt::encryptString($value);
    }
}

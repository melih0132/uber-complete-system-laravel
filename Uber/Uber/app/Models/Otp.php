<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otp';
    protected $primaryKey = 'idotp';
    public $timestamps = false;

    protected $fillable = [
        'idclient',
        'codeotp',
        'dategeneration',
        'dateexpiration',
        'utilise',
    ];

    protected $casts = [
        'utilise' => 'boolean',
        'dategeneration' => 'datetime',
        'dateexpiration' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient', 'idclient');
    }
}

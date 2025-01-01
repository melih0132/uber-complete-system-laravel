<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    use HasFactory;

    protected $table = 'adresse';
    protected $primaryKey = 'idadresse';
    public $timestamps = false;

    protected $fillable = [
        'idadresse',
        'libelleadresse',
        'idville',
    ];

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'idville', 'idville');
    }
}

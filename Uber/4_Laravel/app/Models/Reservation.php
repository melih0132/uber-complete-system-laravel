<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservation';
    protected $primaryKey = 'idreservation';
    public $timestamps = false;

    protected $fillable = [
        'idclient',
        'datereservation',
        'heurereservation',
        'pourqui',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient');
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'idreservation');
    }

    public function velos()
    {
        return $this->belongsToMany(
            Velo::class,
            'velo_reservation',
            'idreservation',
            'idvelo'
        )->withPivot('dureereservation', 'prixreservation');
    }
}

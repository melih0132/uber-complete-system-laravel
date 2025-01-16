<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationVelo extends Model
{
    use HasFactory;

    protected $table = 'velo_reservation';
    protected $primaryKey = 'idreservation_velo';
    public $timestamps = false;


    protected $fillable = [
        'idreservation_velo',
        'idvelo',
        'dureereservation',
        'prixreservation',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation_velo', 'idreservation');
    }

    public function velo()
    {
        return $this->belongsTo(Velo::class, 'idvelo', 'idvelo');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $table = "reservation";
    protected $primaryKey = "idreservation";
    public $timestamps = false;

    protected $fillable = [
        'idreservation','idclient', 'idplanning', 'pourqui', 'datereservation', 'heurereservation'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningReservation extends Model
{
    use HasFactory;
    protected $table = "planning_reservation";
    protected $primaryKey = "idplanning";
    public $timestamps = false;

    protected $fillable = [
        'idplanning',
        'idclient',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient', 'idclient');
    }
}

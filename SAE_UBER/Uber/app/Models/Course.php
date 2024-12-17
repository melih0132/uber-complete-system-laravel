<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = "course";
    protected $primaryKey = "idcourse";
    public $timestamps = false;

    protected $fillable = [
        'idcoursier',
        'idcb',
        'idadresse',
        'idreservation',
        'adr_idadresse',
        'idprestation',
        'prixcourse',
        'statutcourse',
        'distance',
        'temps',
        'is_validated',
        'notecourse',
        'pourboire',
        'status',
        'datecourse',
        'heurecourse',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient');
    }

    public function coursier()
    {
        return $this->belongsTo(Coursier::class, 'idcoursier');
    }

}

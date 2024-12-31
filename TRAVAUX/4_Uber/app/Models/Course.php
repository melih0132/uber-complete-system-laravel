<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course';
    protected $primaryKey = 'idcourse';
    public $timestamps = false;

    protected $fillable = [
        'idcoursier',
        'idcb',
        'idadresse',
        'idreservation',
        'adr_idadresse',
        'idprestation',
        'datecourse',
        'heurecourse',
        'prixcourse',
        'statutcourse',
        'notecourse',
        'commentairecourse',
        'pourboire',
        'distance',
        'temps'
    ];
}

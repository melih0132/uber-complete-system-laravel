<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

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
        'temps',
    ];

    public function startAddress()
    {
        return $this->belongsTo(Adresse::class, 'adr_idadresse', 'idadresse');
    }

    public function endAddress()
    {
        return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation');
    }
}

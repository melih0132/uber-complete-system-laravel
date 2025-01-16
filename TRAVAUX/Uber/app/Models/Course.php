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

    protected $casts = [
        'datecourse' => 'date:Y-m-d',
        'heurecourse' => 'string',
        'temps' => 'integer',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation');
    }

    public function prestations()
    {
        return $this->belongsTo(TypePrestation::class, 'idprestation');
    }

    public function coursier()
    {
        return $this->belongsTo(Coursier::class, 'idcoursier', 'idcoursier');
    }

    public function carteBancaire()
    {
        return $this->belongsTo(CarteBancaire::class, 'idcb', 'idcb');
    }

    public function startAddress()
    {
        return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
    }

    public function endAddress()
    {
        return $this->belongsTo(Adresse::class, 'adr_idadresse', 'idadresse');
    }
}

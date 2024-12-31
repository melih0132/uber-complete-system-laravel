<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureCourse extends Model
{
    protected $table = 'facture_course';
    protected $primaryKey = 'idfacture';
    public $timestamps = false;

    protected $fillable = [
        'idcourse',
        'idpays',
        'idclient',
        'montantreglement',
        'datefacture',
        'quantite'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'idcourse');
    }
}

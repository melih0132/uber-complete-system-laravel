<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestionEtablissement extends Model
{
    use HasFactory;

    protected $table = 'gestion_etablissement';
    protected $primaryKey = 'idgestion';
    public $timestamps = false;

    protected $fillable = [
        'idetablissement',
        'idresponsable',
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'idetablissement');
    }

    public function responsable()
    {
        return $this->belongsTo(ResponsableEnseigne::class, 'idresponsable');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horaires extends Model
{
    use HasFactory;
    protected $table = 'horaires';
    protected $primaryKey = 'idhoraires';
    public $timestamps = false;

    protected $fillable = [
        'idetablissement',
        'joursemaine',
        'horairesouverture',
        'horairesfermeture',
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'idetablissement');
    }
}

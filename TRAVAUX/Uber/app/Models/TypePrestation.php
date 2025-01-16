<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePrestation extends Model
{
    use HasFactory;

    protected $table = "type_prestation";
    protected $primaryKey = "idprestation";
    public $timestamps = false;

    protected $fillable = [
        'libelleprestation',
        'descriptionprestation',
        'imageprestation',
    ];

    public function vehicules()
    {
        return $this->belongsToMany(
            Vehicule::class,
            'a_comme_type',
            'idprestation',
            'idvehicule'
        );
    }
}

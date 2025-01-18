<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodePostal extends Model
{
    use HasFactory;

    protected $table = 'code_postal';
    protected $primaryKey = 'idcodepostal';
    public $timestamps = false;

    protected $fillable = [
        'idpays',
        'codepostal',
    ];

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'idpays', 'idpays');
    }

    public function villes()
    {
        return $this->hasMany(Ville::class, 'idcodepostal', 'idcodepostal');
    }
}

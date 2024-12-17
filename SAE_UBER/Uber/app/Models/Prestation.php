<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    use HasFactory;

    protected $table = "type_prestation";
    protected $primaryKey = "idprestation";
    public $timestamps = false;
}

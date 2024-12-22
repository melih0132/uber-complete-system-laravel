<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nomuser',
        'prenomuser',
        'genreuser',
        'datenaissance',
        'telephone',
        'emailuser',
        'motdepasseuser',
        // pour mdp check j'imagine :
        'remember_token',
    ];

    protected $hidden = [
        'motdepasseuser',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->motdepasseuser;
    }

    protected $casts = [
        'datenaissance' => 'date',
        'email_verified_at' => 'datetime',
    ];

    // partie role des users
    protected $table = "client";
    public $timestamps = false;
    protected $primaryKey = "idclient";

    public function setRole(string $role)
    {
        switch ($role) {
            case 'coursier':
                $this->table = "coursier";
                $this->primaryKey = "idcoursier";
                break;

            case 'logistique':
                $this->table = "service_logistique";
                $this->primaryKey = "idlogistique";
                break;

            case 'facturation':
                $this->table = "service_facturation";
                $this->primaryKey = "idfacturation";
                break;

            case 'administratif':
                $this->table = "service_administratif";
                $this->primaryKey = "idadministratif";
                break;

            case 'rh':
                $this->table = "service_rh";
                $this->primaryKey = "idrh";
                break;

            case 'support':
                $this->table = "service_support";
                $this->primaryKey = "idsupport";
                break;

            default:
                $this->table = "client";
                $this->primaryKey = "idclient";
                break;
        }
    }
}

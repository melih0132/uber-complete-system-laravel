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
        'remember_token', // pour la gestion de la session/mémorisation
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

    public function setRole(string $role)
    {
        $this->role = $role;

        switch ($role) {
            case 'coursier':
                $this->table = "coursier";
                $this->primaryKey = "idcoursier";
                break;

            case 'logistique':

                break;

            case 'facturation':

                break;

            case 'administratif':

                break;

            case 'rh':

                break;

            case 'support':

                break;

            default:
                $this->table = "client";
                $this->primaryKey = "idclient";
                break;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, TwoFactorAuthenticatable, Notifiable;

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

    protected $casts = [
        'datenaissance' => 'date',
    ];

    public function setRole(string $role)
    {
        switch ($role) {
            case 'coursier':
                $this->table = 'coursier';
                $this->primaryKey = 'idcoursier';
                break;

            case 'livreur':
                $this->table = 'livreur';
                $this->primaryKey = 'idlivreur';
                break;

            case 'restaurateur':
                $this->table = 'restaurateur';
                $this->primaryKey = 'idrestaurateur';
                break;

            case 'responsable':
                $this->table = 'responsable_enseigne';
                $this->primaryKey = 'idresponsable';
                break;

            default:
                $this->table = 'client';
                $this->primaryKey = 'idclient';
                break;
        }
    }

    public function getAuthPassword()
    {
        return $this->motdepasseuser;
    }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomuser',
        'prenomuser',
        'genreuser',
        'datenaissance',
        'telephone',
        'emailuser',
        'motdepasseuser',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'motdepasseuser',
        'remember_token',
    ];

    /**
     * Get the password for authentication.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->motdepasseuser;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'datenaissance' => 'date',
        'email_verified_at' => 'datetime',
    ];

    // Configuration pour la table "client" ou "coursier" dynamiquement selon le rôle
    protected $table = "client"; // Valeur par défaut
    public $timestamps = false;
    protected $primaryKey = "idclient"; // Par défaut pour "client"

    /**
     * Permet de définir dynamiquement la table et la clé primaire si nécessaire.
     * Exemple : Lors d'un enregistrement d'un "coursier"
     * @param string $role
     */
    public function setRole(string $role)
    {
        if ($role === 'coursier') {
            $this->table = "coursier";
            $this->primaryKey = "idcoursier";
        } else {
            $this->table = "client";
            $this->primaryKey = "idclient";
        }
    }
}

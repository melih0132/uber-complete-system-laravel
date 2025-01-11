<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Coursier;
use App\Models\Livreur;
use App\Models\Restaurateur;
use App\Models\ResponsableEnseigne;
use Illuminate\Support\Facades\Hash;

class HashUserPasswords extends Command
{
    protected $signature = 'user:hash-passwords';
    protected $description = 'Hasher les mots de passe des utilisateurs (clients, coursiers, livreurs, restaurateurs et responsables d\'enseignes) dans la base de données';

    public function handle()
    {
        $this->info('Traitement des mots de passe des clients...');
        $this->hashPasswords(Client::all(), 'client');

        $this->info('Traitement des mots de passe des coursiers...');
        $this->hashPasswords(Coursier::all(), 'coursier');

        $this->info('Traitement des mots de passe des livreurs...');
        $this->hashPasswords(Livreur::all(), 'livreur');

        $this->info('Traitement des mots de passe des restaurateurs...');
        $this->hashPasswords(Restaurateur::all(), 'restaurateur');

        $this->info('Traitement des mots de passe des responsables d\'enseignes...');
        $this->hashPasswords(ResponsableEnseigne::all(), 'responsable');

        $this->info('Tous les mots de passe des utilisateurs ont été traités.');
        return 0;
    }

    private function hashPasswords($users, $role)
    {
        foreach ($users as $user) {
            // Vérifie si le mot de passe est déjà hashé
            if (strlen($user->motdepasseuser) !== 60) {
                // Hachage du mot de passe
                $user->motdepasseuser = Hash::make($user->motdepasseuser);
                $user->save();

                $this->info("Le mot de passe du {$role} {$user->emailuser} a été hashé.");
            } else {
                $this->info("Le mot de passe du {$role} {$user->emailuser} est déjà hashé.");
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Coursier;
use App\Models\Livreur;
use App\Models\Restaurateur;
use App\Models\ResponsableEnseigne;
use App\Models\CarteBancaire;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;

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

        $this->info('Chiffrement des numéros de cartes bancaires des clients...');
        $this->encryptCarteBancaire();

        $this->info('Tous les mots de passe des utilisateurs ont été traités.');
        return 0;
    }

    private function hashPasswords($users, $role)
    {
        foreach ($users as $user) {
            if (strlen($user->motdepasseuser) !== 60) {
                $user->motdepasseuser = Hash::make($user->motdepasseuser);
                $user->save();

                $this->info("Le mot de passe du {$role} {$user->emailuser} a été hashé.");
            } else {
                $this->info("Le mot de passe du {$role} {$user->emailuser} est déjà hashé.");
            }
        }
    }

    private function encryptCarteBancaire()
    {
        $cartes = CarteBancaire::all();

        foreach ($cartes as $carte) {
            if (!$this->isEncrypted($carte->numerocb)) {
                $carte->numerocb = Crypt::encryptString($carte->numerocb);
            }

            if (!$this->isEncrypted($carte->cryptogramme)) {
                $carte->cryptogramme = Crypt::encryptString($carte->cryptogramme);
            }

            $carte->save();
            $this->info("Carte ID: {$carte->idcb} chiffrée avec succès.");
        }

        $this->info('Chiffrement des cartes terminé.');
    }

    private function isEncrypted($value)
    {
        try {
            Crypt::decryptString($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

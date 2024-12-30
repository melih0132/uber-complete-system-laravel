<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Coursier;
use Illuminate\Support\Facades\Hash;

class HashUserPasswords extends Command
{
    protected $signature = 'user:hash-passwords';
    protected $description = 'Hasher les mots de passe des clients et des coursiers dans la base de données';

    public function handle()
    {
        $this->info('Traitement des mots de passe des clients...');
        $this->hashPasswords(Client::all(), 'client');

        $this->info('Traitement des mots de passe des coursiers...');
        $this->hashPasswords(Coursier::all(), 'coursier');

        $this->info('Tous les mots de passe des clients et des coursiers ont été traités.');
        return 0;
    }

    private function hashPasswords($users, $role)
    {
        foreach ($users as $user) {
            // Vérifie si le mot de passe est déjà hashé
            if (strlen($user->motdepasseuser) !== 60) {
                $user->motdepasseuser = Hash::make($user->motdepasseuser);
                $user->save();

                $this->info("Le mot de passe du {$role} {$user->emailuser} a été hashé.");
            } else {
                $this->info("Le mot de passe du {$role} {$user->emailuser} est déjà hashé.");
            }
        }
    }
}

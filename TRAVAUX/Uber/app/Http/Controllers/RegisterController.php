<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Panier;
use App\Models\Planning_reservation;
use App\Models\Coursier;
use App\Models\Adresse;
use App\Models\Code_postal;
use App\Models\Ville;
use App\Models\Entreprise;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showDriverRegistrationForm()
    {
        return view('auth.register-driver');
    }
    public function showPassengerRegistrationForm()
    {
        return view('auth.register-passenger');
    }
    public function showEatsRegistrationForm()
    {
        return view('auth.register-eats');
    }
    public function showServicesRegistrationForm()
    {
        return view('auth.register-services');
    }

    public function register(Request $request)
    {
        $adresse = $this->getOrCreateAdresse($request);

        DB::transaction(function () use ($request, $adresse) {
            if ($request->role === 'client') {
                $this->createClient($request, $adresse->idadresse);
            } elseif ($request->role === 'coursier') {
                $this->createCoursier($request, $adresse->idadresse);
            }
        });

        return redirect()->route('login')->with('success', 'Votre compte a été créé avec succès.');
    }

    private function createClient(Request $request, $idadresse)
    {
        $identreprise = null;

        if (!empty($request->nomentreprise)) {
            $entreprise = DB::table('entreprise')
                ->where('nomentreprise', $request->nomentreprise)
                ->orWhere('siretentreprise', $request->siretentreprise)
                ->first();

            if ($entreprise) {
                $identreprise = $entreprise->identreprise;

                if ($entreprise->idadresse !== $idadresse) {
                    DB::table('entreprise')
                        ->where('identreprise', $identreprise)
                        ->update(['idadresse' => $idadresse]);
                }

                if ($entreprise->taille !== $request->taille) {
                    DB::table('entreprise')
                        ->where('identreprise', $identreprise)
                        ->update(['taille' => $request->taille]);
                }
            } else {
                $entreprise = Entreprise::create([
                    'idadresse' => $idadresse,
                    'siretentreprise' => $request->siretentreprise,
                    'nomentreprise' => $request->nomentreprise,
                    'taille' => $request->taille,
                ]);
                $identreprise = $entreprise->identreprise;
            }
        }

        $client = Client::create([
            'identreprise' => $identreprise,
            'idadresse' => $idadresse,
            'nomuser' => $request->nomuser,
            'prenomuser' => $request->prenomuser,
            'genreuser' => $request->genreuser,
            'datenaissance' => $request->datenaissance,
            'telephone' => $request->telephone,
            'emailuser' => $request->emailuser,
            'motdepasseuser' => Hash::make($request->motdepasseuser),
            'souhaiterecevoirbonplan' => $request->souhaiterecevoirbonplan ?? false,
        ]);

        Panier::create([
            'idclient' => $client->idclient,
            'prix' => 0,
        ]);

        Planning_reservation::create([
            'idclient' => $client->idclient,
        ]);
    }

    private function createCoursier(Request $request)
    {
        $adresse = $this->getOrCreateAdresse($request);

        $identreprise = null;

        if (!empty($request->nomentreprise)) {
            $entreprise = DB::table('entreprise')
                ->where('nomentreprise', $request->nomentreprise)
                ->orWhere('siretentreprise', $request->siretentreprise)
                ->first();

            if ($entreprise) {
                $identreprise = $entreprise->identreprise;

                if ($entreprise->idadresse !== $adresse->idadresse) {
                    DB::table('entreprise')
                        ->where('identreprise', $identreprise)
                        ->update(['idadresse' => $adresse->idadresse]);
                }

                if ($entreprise->taille !== $request->taille) {
                    DB::table('entreprise')
                        ->where('identreprise', $identreprise)
                        ->update(['taille' => $request->taille]);
                }
            } else {
                $entreprise = Entreprise::create([
                    'idadresse' => $adresse->idadresse,
                    'siretentreprise' => $request->siretentreprise,
                    'nomentreprise' => $request->nomentreprise,
                    'taille' => $request->taille,
                ]);
                $identreprise = $entreprise->identreprise;
            }
        }

        $coursier = Coursier::create([
            'identreprise' => $identreprise,
            'idadresse' => $adresse->idadresse,
            'nomuser' => $request->nomuser,
            'prenomuser' => $request->prenomuser,
            'genreuser' => $request->genreuser,
            'datenaissance' => $request->datenaissance,
            'telephone' => $request->telephone,
            'emailuser' => $request->emailuser,
            'motdepasseuser' => Hash::make($request->motdepasseuser),
            'numerocartevtc' => $request->numerocartevtc,
            // Décommentez si nécessaire
            // 'iban' => $request->iban,
            // 'datedebutactivite' => $request->datedebutactivite,
        ]);

        DB::table('entretien')->insert([
            'idcoursier' => $coursier->idcoursier,
            'dateentretien' => null, // Pas de date d'entretien initialement
            'status' => 'En attente',
        ]);
    }

    public function showAccount()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('account', compact('user'));
    }

    private function getOrCreateAdresse(Request $request)
    {
        $codePostal = Code_postal::where('codepostal', $request->codepostal)
            ->where('idpays', 1)
            ->first();

        if (!$codePostal) {
            $codePostal = Code_postal::create([
                'idpays' => 1,
                'codepostal' => $request->codepostal,
            ]);
        }

        $ville = Ville::where('nomville', $request->nomville)
            ->where('idcodepostal', $codePostal->idcodepostal)
            ->where('idpays', 1)
            ->first();

        if (!$ville) {
            $ville = Ville::create([
                'nomville' => $request->nomville,
            ]);
        }

        $adresse = Adresse::where('idville', $ville->idville)
            ->where('libelleadresse', $request->libelleadresse)
            ->first();

        if (!$adresse) {
            $adresse = Adresse::create([
                'libelleadresse' => $request->libelleadresse,
            ]);
        }

        return $adresse;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Coursier;
use App\Models\Adresse;
use App\Models\Code_postal;
use App\Models\Ville;
use App\Models\Entreprise;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // $request->validate($this->getValidationRules($request->role));

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

    /*     private function getValidationRules($role)
    {
        $rules = [
            'nomuser' => 'required|string|max:50',
            'prenomuser' => 'required|string|max:50',
            'genreuser' => 'required|string|in:Monsieur,Madame',
            'datenaissance' => 'required|date|before:-18 years',
            'telephone' => ['required', 'regex:/^(06|07)[0-9]{8}$/'],
            'emailuser' => 'required|email|unique:client,emailuser',
            'motdepasseuser' => 'required|min:8|confirmed',
            'role' => 'required|in:client,coursier',
            'libelleadresse' => 'required|string|max:100',
            'nomville' => 'required|string|max:50',
            'codepostal' => 'required|string|size:5',
        ];

        if ($role === 'coursier') {
            $rules = array_merge($rules, [
                'numerocartevtc' => 'required|string|max:13|unique:coursier,numerocartevtc',
                'iban' => 'required|string|max:34|unique:coursier,iban',
                'datedebutactivite' => 'required|date|before_or_equal:today',
                'siretentreprise' => 'required|regex:/^[0-9]{14}$/|unique:entreprise,siretentreprise',
                'nomentreprise' => 'required|string|max:50',
                'taille' => 'required|in:PME,ETI,GE',
            ]);
        }

        return $rules;
    } */

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

    private function createClient(Request $request)
    {
        // Récupérer ou créer l'adresse
        $adresse = $this->getOrCreateAdresse($request);

        $identreprise = null;

        if (!empty($request->nomentreprise)) {
            // Recherche de l'entreprise par nom ou SIRET
            $entreprise = DB::table('entreprise')
                ->where('nomentreprise', $request->nomentreprise)
                ->orWhere('siretentreprise', $request->siretentreprise)
                ->first();

            if ($entreprise) {
                // Si l'entreprise existe, récupérer son ID
                $identreprise = $entreprise->identreprise;

                // Vérifier et mettre à jour l'adresse si nécessaire
                if ($entreprise->idadresse !== $adresse->idadresse) {
                    DB::table('entreprise')
                        ->where('identreprise', $identreprise)
                        ->update(['idadresse' => $adresse->idadresse]);
                }

                // Vérifier et mettre à jour la taille si nécessaire
                if ($entreprise->taille !== $request->taille) {
                    DB::table('entreprise')
                        ->where('identreprise', $identreprise)
                        ->update(['taille' => $request->taille]);
                }
            } else {
                // Si l'entreprise n'existe pas, la créer
                $entreprise = Entreprise::create([
                    'idadresse' => $adresse->idadresse,
                    'siretentreprise' => $request->siretentreprise,
                    'nomentreprise' => $request->nomentreprise,
                    'taille' => $request->taille,
                ]);
                $identreprise = $entreprise->identreprise;
            }
        }

        // Créer le client
        Client::create([
            'identreprise' => $identreprise,
            'idadresse' => $adresse->idadresse,
            'nomuser' => $request->nomuser,
            'prenomuser' => $request->prenomuser,
            'genreuser' => $request->genreuser,
            'datenaissance' => $request->datenaissance,
            'telephone' => $request->telephone,
            'emailuser' => $request->emailuser,
            'motdepasseuser' => Hash::make($request->motdepasseuser),
        ]);
    }

    private function createCoursier(Request $request)
    {
        // Récupérer ou créer l'adresse
        $adresse = $this->getOrCreateAdresse($request);

        $identreprise = null;

        if (!empty($request->nomentreprise)) {
            // Recherche de l'entreprise par nom ou SIRET
            $entreprise = DB::table('entreprise')
                ->where('nomentreprise', $request->nomentreprise)
                ->orWhere('siretentreprise', $request->siretentreprise)
                ->first();

            if ($entreprise) {
                // Si l'entreprise existe, récupérer son ID
                $identreprise = $entreprise->identreprise;

                // Vérifier et mettre à jour l'adresse si nécessaire
                if ($entreprise->idadresse !== $adresse->idadresse) {
                    DB::table('entreprise')
                        ->where('identreprise', $identreprise)
                        ->update(['idadresse' => $adresse->idadresse]);
                }

                // Vérifier et mettre à jour la taille si nécessaire
                if ($entreprise->taille !== $request->taille) {
                    DB::table('entreprise')
                        ->where('identreprise', $identreprise)
                        ->update(['taille' => $request->taille]);
                }
            } else {
                // Si l'entreprise n'existe pas, la créer
                $entreprise = Entreprise::create([
                    'idadresse' => $adresse->idadresse,
                    'siretentreprise' => $request->siretentreprise,
                    'nomentreprise' => $request->nomentreprise,
                    'taille' => $request->taille,
                ]);
                $identreprise = $entreprise->identreprise;
            }
        }

        // Créer le coursier
        Coursier::create([
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
            'iban' => $request->iban,
            'datedebutactivite' => $request->datedebutactivite,
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
}

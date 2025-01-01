<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Panier;
use App\Models\Planning_reservation;

use App\Models\Client;
use App\Models\Coursier;
use App\Models\Entreprise;

use App\Models\ResponsableEnseigne;

use App\Models\Adresse;
use App\Models\Code_postal;
use App\Models\Ville;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    public function showManagerRegistrationForm()
    {
        return view('auth.register-manager');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|string',
            'nomuser' => 'required|string|max:255',
            'prenomuser' => 'required|string|max:255',
            'emailuser' => 'required|email|max:255',
            'motdepasseuser' => 'required|string|min:8|confirmed',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (
                Client::where('emailuser', $request->emailuser)->exists() ||
                Coursier::where('emailuser', $request->emailuser)->exists() ||
                ResponsableEnseigne::where('emailuser', $request->emailuser)->exists()
            ) {
                $validator->errors()->add('emailuser', 'Cette adresse email est déjà utilisée.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'L\'adresse email est déjà utilisée.');
        }

        $adresse = null;

        if ($request->role !== 'responsable') {
            $adresse = $this->getOrCreateAdresse($request);
        }

        try {
            $redirectRoute = null;

            DB::transaction(function () use ($request, $adresse, &$redirectRoute) {
                switch ($request->role) {
                    case 'client':
                        $this->createClient($request, $adresse->idadresse);
                        $redirectRoute = 'login';
                        break;
                    case 'coursier':
                        $this->createCoursier($request, $adresse->idadresse);
                        $redirectRoute = 'login-driver';
                        break;
                    case 'responsable':
                        $this->createResponsable($request);
                        $redirectRoute = 'login-manager';
                        break;
                    default:
                        throw new \Exception('Role invalide.');
                }
            });

            return redirect()->route($redirectRoute)->with('success', 'Votre compte a été créé avec succès.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23505') {
                return redirect()->back()->with('error', 'L\'adresse email est déjà utilisée.');
            }

            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur inattendue est survenue : ' . $e->getMessage());
        }
    }

    private function createClient(Request $request, $idadresse)
    {
        $identreprise = $this->handleEntreprise($request, $idadresse);

        /*         if (!empty($request->nomentreprise)) {
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
        } */

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

    private function createCoursier(Request $request, $idadresse)
    {
        $identreprise = $this->handleEntreprise($request, $idadresse);

        /*        if (!empty($request->nomentreprise)) {
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
        } */

        $coursier = Coursier::create([
            'identreprise' => $identreprise,
            'idadresse' => $idadresse,
            'nomuser' => $request->nomuser,
            'prenomuser' => $request->prenomuser,
            'genreuser' => $request->genreuser,
            'datenaissance' => $request->datenaissance,
            'telephone' => $request->telephone,
            'emailuser' => $request->emailuser,
            'motdepasseuser' => Hash::make($request->motdepasseuser),
            'numerocartevtc' => $request->numerocartevtc,
        ]);

        DB::table('entretien')->insert([
            'idcoursier' => $coursier->idcoursier,
            'dateentretien' => null,
            'status' => 'En attente',
        ]);
    }

    private function createResponsable(Request $request)
    {
        ResponsableEnseigne::create([
            'nomuser' => $request->nomuser,
            'prenomuser' => $request->prenomuser,
            'telephone' => $request->telephone,
            'emailuser' => $request->emailuser,
            'motdepasseuser' => Hash::make($request->motdepasseuser),
        ]);
    }

    private function handleEntreprise(Request $request, $idadresse)
    {
        $identreprise = null;

        if (!empty($request->nomentreprise)) {
            $entreprise = Entreprise::where('nomentreprise', $request->nomentreprise)
                ->orWhere('siretentreprise', $request->siretentreprise)
                ->first();

            if ($entreprise) {
                $identreprise = $entreprise->identreprise;

                if ($entreprise->idadresse !== $idadresse) {
                    $entreprise->update(['idadresse' => $idadresse]);
                }

                if ($entreprise->taille !== $request->taille) {
                    $entreprise->update(['taille' => $request->taille]);
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

        return $identreprise;
    }

    private function getOrCreateAdresse(Request $request)
    {
        $codePostal = Code_postal::firstOrCreate([
            'codepostal' => $request->codepostal,
            'idpays' => 1
        ]);

        $ville = Ville::firstOrCreate([
            'nomville' => $request->nomville,
            'idcodepostal' => $codePostal->idcodepostal,
            'idpays' => 1
        ]);

        return Adresse::firstOrCreate([
            'libelleadresse' => $request->libelleadresse,
            'idville' => $ville->idville
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Panier;
use App\Models\PlanningReservation;

use App\Models\Client;

use App\Models\Coursier;
use App\Models\Livreur;
use App\Models\Entreprise;

use App\Models\ResponsableEnseigne;
use App\Models\Restaurateur;

use App\Models\Adresse;
use App\Models\CodePostal;
use App\Models\Ville;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function registerCoursier($role)
    {
        if ($role === 'coursier') {
            return redirect()->route('register.driver');
        } elseif ($role === 'livreur') {
            return redirect()->route('register.deliverer');
        } else {
            abort(404, 'Role not found');
        }
    }

    public function showDriverRegistrationForm()
    {
        return view('auth.register-driver');
    }

    public function showDelivererRegistrationForm()
    {
        return view('auth.register-deliverer');
    }

    public function showPassengerRegistrationForm()
    {
        return view('auth.register-passenger');
    }

    public function showEatsRegistrationForm()
    {
        return view('auth.register-eats');
    }

    public function registerManager($role)
    {
        if ($role === 'restaurateur') {
            return redirect()->route('register.restaurateur');
        } elseif ($role === 'responsable') {
            return redirect()->route('register.brandmanager');
        } else {
            abort(404, 'Role not found');
        }
    }

    public function showBrandManagerRegistrationForm()
    {
        return view('auth.register-brandmanager');
    }

    public function showRestaurateurRegistrationForm()
    {
        return view('auth.register-restaurateur');
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
                Livreur::where('emailuser', $request->emailuser)->exists() ||
                ResponsableEnseigne::where('emailuser', $request->emailuser)->exists() ||
                Restaurateur::where('emailuser', $request->emailuser)->exists()
            ) {
                $validator->errors()->add('emailuser', 'Cette adresse email est déjà utilisée.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
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
                    case 'livreur':
                        $this->createLivreur($request, $adresse->idadresse);
                        $redirectRoute = 'login-driver';
                        break;
                    case 'responsable':
                        $this->createResponsable($request);
                        $redirectRoute = 'login-manager';
                        break;
                    case 'restaurateur':
                        $this->createRestaurateur($request);
                        $redirectRoute = 'login-restaurateur';
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
        $identreprise = $this->handleEntreprise($request);

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
            'typeclient' => $request->input('typeclient') ?? 'Uber',
        ]);

        Panier::create([
            'idclient' => $client->idclient,
            'prix' => 0,
        ]);
    }

    private function createLivreur(Request $request, $idadresse)
    {
        $identreprise = $this->handleEntreprise($request);

        return Livreur::create([
            'identreprise' => $identreprise,
            'idadresse' => $idadresse,
            'genreuser' => $request->genreuser,
            'nomuser' => $request->nomuser,
            'prenomuser' => $request->prenomuser,
            'datenaissance' => $request->datenaissance,
            'telephone' => $request->telephone,
            'emailuser' => $request->emailuser,
            'motdepasseuser' => Hash::make($request->motdepasseuser),
            'iban' => $request->iban ?? null,
            'datedebutactivite' => $request->datedebutactivite ?? null,
            'notemoyenne' => null,
        ]);
    }

    private function createCoursier(Request $request, $idadresse)
    {
        $identreprise = $this->handleEntreprise($request);

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

    private function createRestaurateur(Request $request)
    {
        Restaurateur::create([
            'nomuser' => $request->nomuser,
            'prenomuser' => $request->prenomuser,
            'telephone' => $request->telephone,
            'emailuser' => $request->emailuser,
            'motdepasseuser' => Hash::make($request->motdepasseuser),
            'role' => $request->role ?? 'Gérant',
        ]);
    }

    private function handleEntreprise(Request $request)
    {
        $identreprise = null;

        if (!empty($request->nomentreprise)) {
            $adresseEntreprise = null;

            if (!empty($request->adresseEntreprise) && !empty($request->villeEntreprise) && !empty($request->codepostalEntreprise)) {
                $codePostalEntreprise = CodePostal::firstOrCreate([
                    'codepostal' => $request->codepostalEntreprise,
                    'idpays' => 1 // France
                ]);

                $villeEntreprise = Ville::firstOrCreate([
                    'nomville' => $request->villeEntreprise,
                    'idcodepostal' => $codePostalEntreprise->idcodepostal,
                    'idpays' => 1
                ]);

                $adresseEntreprise = Adresse::firstOrCreate([
                    'libelleadresse' => $request->adresseEntreprise,
                    'idville' => $villeEntreprise->idville
                ]);
            }

            $entreprise = Entreprise::where('nomentreprise', $request->nomentreprise)
                ->orWhere('siretentreprise', $request->siretentreprise)
                ->first();

            if ($entreprise) {
                $identreprise = $entreprise->identreprise;

                if ($adresseEntreprise && $entreprise->idadresse !== $adresseEntreprise->idadresse) {
                    $entreprise->update(['idadresse' => $adresseEntreprise->idadresse]);
                }

                if ($entreprise->taille !== $request->taille) {
                    $entreprise->update(['taille' => $request->taille]);
                }
            } else {
                $entreprise = Entreprise::create([
                    'idadresse' => $adresseEntreprise->idadresse ?? null,
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
        $codePostal = CodePostal::firstOrCreate([
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

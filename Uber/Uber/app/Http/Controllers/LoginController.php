<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Client;

use App\Models\Favoris;

use App\Models\Coursier;
use App\Models\Vehicule;
use App\Models\Entretien;

use App\Models\Livreur;
use App\Models\Commande;

use App\Models\ResponsableEnseigne;
use App\Models\Restaurateur;
use App\Models\GestionEtablissement;

use App\Models\Etablissement;

use App\Models\Adresse;
use App\Models\Ville;
use App\Models\CodePostal;

use App\Models\LieuFavori;

use GPBMetadata\Google\Cloud\Dialogflow\V2\Session;
use Illuminate\Foundation\Auth\ThrottlesLogins;

// use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private $serviceAccounts = [
        'rh' => [
            'email' => 'rh@uber.com',
            'password' => 'ressourceh123',
        ],
        'logistique' => [
            'email' => 'logistique@uber.com',
            'password' => 'logistique123',
        ],
        'administratif' => [
            'email' => 'admin@uber.com',
            'password' => 'admin123',
        ],
        'facturation' => [
            'email' => 'facturation@uber.com',
            'password' => 'facturation123',
        ],
        'course' => [
            'email' => 'course@uber.com',
            'password' => 'course123',
        ],
        'juridique' => [
            'email' => 'juridique@uber.com',
            'password' => 'juridique123',
        ],
        'commande' => [
            'email' => 'commande@uber.com',
            'password' => 'commande123',
        ],
    ];

    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:client,coursier,livreur,responsable,restaurateur,rh,logistique,facturation,administratif,course,commande,juridique'],
        ]);

        if ($credentials['role'] === 'client') {
            $user = Client::where('emailuser', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->motdepasseuser)) {
                return back()->withErrors(['email' => 'Les informations de connexion sont incorrectes.'])
                    ->withInput($request->only('email', 'role'));
            }

            $user->update(['last_connexion' => now()]);

            if ($user->mfa_activee) {
                $request->session()->put('mfa_user', [
                    'id' => $user->idclient,
                    'role' => $credentials['role'],
                ]);

                $otpController = new SecurityController();
                $otpController->sendOtp($request);

                return redirect()->route('otp')->with('success', 'Un code OTP a été envoyé sur votre téléphone.');
            } else {
                $request->session()->put('user', [
                    'id' => $user->idclient,
                    'role' => $credentials['role'],
                    'typeclient' => $user->typeclient,
                ]);
            }

            // redirecton site basé sur le type de client
            if ($user->isUberClient()) {
                return redirect()->route('accueil')->with('success', 'Connexion réussie.');
            } elseif ($user->isUberEatsClient()) {
                return redirect()->route('etablissement.accueilubereats')->with('success', 'Connexion réussie.');
            }

            return redirect('/')->with('success', 'Connexion réussie.');
        }

        return $this->handleOtherRoles($credentials, $request);
    }

    private function handleOtherRoles($credentials, Request $request)
    {
        if (in_array($credentials['role'], ['coursier', 'livreur', 'responsable', 'restaurateur'])) {
            $userModel = match ($credentials['role']) {
                'responsable' => ResponsableEnseigne::class,
                'restaurateur' => Restaurateur::class,
                'coursier' => Coursier::class,
                'livreur' => Livreur::class,
            };

            $user = $userModel::where('emailuser', $credentials['email'])->first();

            if (!$user) {
                return redirect()->back()->with('error', 'Utilisateur introuvable. Vérifiez vos informations.');
            }

            $request->session()->put('user', [
                'id' => match ($credentials['role']) {
                    'responsable' => $user->idresponsable,
                    'restaurateur' => $user->idrestaurateur,
                    'coursier' => $user->idcoursier,
                    'livreur' => $user->idlivreur,
                    default => $user->iduser,
                },
                'role' => $credentials['role'],
            ]);

            return match ($credentials['role']) {
                'coursier' => redirect()->route('coursier.courses.index')->with('success', 'Connexion réussie.'),
                'livreur' => redirect()->route('coursier.livraisons.index')->with('success', 'Connexion réussie.'),
                'responsable' => redirect()->route('myaccount')->with('success', 'Connexion réussie.'),
                'restaurateur' => redirect()->route('myaccount')->with('success', 'Connexion réussie.'),
                default => redirect()->route('accueil')->with('success', 'Connexion réussie.'),
            };
        }

        return $this->handleServiceAccounts($credentials, $request);
    }

    private function handleServiceAccounts($credentials, Request $request)
    {
        if (isset($this->serviceAccounts[$credentials['role']])) {
            $account = $this->serviceAccounts[$credentials['role']];

            if ($credentials['email'] !== $account['email'] || $credentials['password'] !== $account['password']) {
                return back()->withErrors([
                    'email' => 'Les informations de connexion sont incorrectes.',
                ])->withInput($request->only('email', 'role'));
            }

            $request->session()->put('user', [
                'email' => $account['email'],
                'role' => $credentials['role'],
            ]);

            // redirection en fonction du rôle
            switch ($credentials['role']) {
                case 'rh':
                    return redirect()->route('entretiens.index')->with('success', 'Connexion réussie.');
                case 'logistique':
                    return redirect()->route('logistique.vehicules')->with('success', 'Connexion réussie.');
                case 'facturation':
                    return redirect()->route('facturation.index')->with('success', 'Connexion réussie.');
                case 'administratif':
                    return redirect()->route('admin.index')->with('success', 'Connexion réussie.');
                case 'juridique':
                    return redirect()->route('privacy')->with('success', 'Connexion réussie.');
                case 'course':
                    return redirect()->route('serviceCourse.index')->with('success', 'Connexion réussie.');
                case 'commande':
                    return redirect()->route('commandes.index')->with('success', 'Connexion réussie.');
                default:
                    return back()->withErrors(['role' => 'Rôle inconnu.']);
            }
        }

        return back()->withErrors(['role' => 'Rôle invalide.']);
    }

    public function showAccount(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser) {
            return redirect()->route('login')->withErrors(['Vous devez être connecté pour accéder à cette page.']);
        }

        $user = null;
        $etablissements = collect();
        $courses = collect();
        $favorites = collect();
        $villes = collect();
        $canDrive = false;
        $vehicules = collect();
        $entretien = null;
        $iban = null;
        $ibanNotification = null;

        switch ($sessionUser['role']) {
            case 'responsable':
                $user = ResponsableEnseigne::find($sessionUser['id']);
                $etablissements = Etablissement::whereIn(
                    'idetablissement',
                    GestionEtablissement::where('idresponsable', $user->idresponsable)
                        ->pluck('idetablissement')
                )->with('categories')->get();
                break;

            case 'restaurateur':
                $user = Restaurateur::find($sessionUser['id']);
                $etablissements = Etablissement::where('idrestaurateur', $user->idrestaurateur)
                    ->with('categories')
                    ->get();
                break;

            case 'client':
                $user = Client::find($sessionUser['id']);

                if ($user) {
                    $courses = $user->courses()
                        ->with([
                            'startAddress:idadresse,libelleadresse',
                            'endAddress:idadresse,libelleadresse',
                        ])
                        ->whereIn('statutcourse', ['Terminée', 'Annulée'])
                        ->orderBy('datecourse', 'desc')
                        ->orderBy('heurecourse', 'desc')
                        ->get();

                    $favorites = $user->lieuFavoris()
                        ->with([
                            'adresse:idville,idadresse,libelleadresse',
                            'adresse.ville:idville,nomville',
                        ])
                        ->get();

                    $villes = Ville::select(['idville', 'nomville'])
                        ->orderBy('nomville', 'asc')
                        ->get();
                }
                break;

            case 'coursier':
                if (!empty($sessionUser['id'])) {
                    $user = Coursier::find($sessionUser['id']);

                    if ($user) {
                        $iban = $user->iban ?? null;
                        $vehicules = $user->vehicules ?? collect();
                        $entretien = $user->entretien()->orderBy('dateentretien', 'desc')->first() ?? null;

                        $canDrive = $vehicules->where('statusprocessuslogistique', 'Validé')->isNotEmpty() && !empty($iban);
                    } else {
                        $iban = null;
                        $vehicules = collect();
                        $entretien = null;
                        $canDrive = false;
                    }
                }
                break;

            case 'livreur':
                $user = Livreur::find($sessionUser['id']);
                if ($user) {
                    $commandes = Commande::where('idlivreur', $user->idlivreur)
                        ->with([
                            'adresseDestination:idadresse,libelleadresse',
                            'panier.client:idclient,nomuser,prenomuser',
                        ])
                        ->orderBy('heurecommande', 'desc')
                        ->get();
                }
                break;

            case 'logistique':
            case 'facturation':
            case 'commande':
            case 'rh':
            case 'course':
            case 'juridique':
                $user = [
                    'email' => $sessionUser['email'],
                    'role' => $sessionUser['role'],
                ];
                break;

            default:
                return redirect()->route('login')->withErrors(['Rôle utilisateur inconnu.']);
        }

        if (!$user) {
            $request->session()->forget('user');
            return redirect()->route('login')->withErrors(['Utilisateur introuvable. Veuillez vous reconnecter.']);
        }

        $rolesUberEats = ['livreur', 'restaurateur', 'responsable', 'commande'];
        $isUberEatsClient = false;
        $isUberEatsUser = false;
        if (isset($sessionUser['role'])) {
            $role = $sessionUser['role'];

            if ($role === 'client' && isset($sessionUser['typeclient']) && $sessionUser['typeclient'] === 'Uber Eats') {
                $isUberEatsClient = true;
            }

            $isUberEatsUser = in_array($role, $rolesUberEats) || $isUberEatsClient;
        }

        $layout = $isUberEatsUser ? 'layouts.ubereats' : 'layouts.app';

        return view('myaccount', [
            'user' => $user,
            'layout' => $layout,
            'isUberEatsUser' => $isUberEatsUser,
            'role' => $sessionUser['role'],
            'etablissements' => $etablissements,
            'courses' => $courses,
            'favorites' => $favorites,
            'villes' => $villes,
            'iban' => $iban,
            'vehicules' => $vehicules,
            'entretien' => $entretien,
            'canDrive' => $canDrive,
        ]);
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $sessionUser = $request->session()->get('user');

        if (!$sessionUser) {
            return redirect('/login')->withErrors(['Vous devez être connecté pour modifier votre photo de profil.']);
        }

        $model = $sessionUser['role'] === 'client' ? Client::class : Coursier::class;
        $user = $model::find($sessionUser['id']);

        if (!$user) {
            return back()->withErrors(['Utilisateur introuvable.']);
        }

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');

            $user->photoprofile = $path;
            $user->save();
        }

        return back()->with('success', 'Photo de profil mise à jour avec succès.');
    }

    public function addFavoriteAddress(Request $request)
    {
        $validatedData = $request->validate([
            'libelleadresse' => 'required|string|max:100',
            'idville' => 'required|integer|exists:ville,idville',
            'nomlieu' => 'required|string|max:100',
        ]);


        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')->withErrors(['Vous devez être connecté pour gérer vos favoris.']);
        }

        $client = Client::find($sessionUser['id']);
        if (!$client) {
            return redirect()->route('account')->withErrors(['Utilisateur introuvable.']);
        }

        $adresseExistante = DB::table('adresse')
            ->whereRaw('soundex(libelleadresse) = soundex(?)', [$validatedData['libelleadresse']])
            ->where('idville', $validatedData['idville'])
            ->first();

        $adresseId = $adresseExistante ? $adresseExistante->idadresse : null;

        if (!$adresseId) {
            $adresseId = DB::table('adresse')->insertGetId([
                'idville' => $validatedData['idville'],
                'libelleadresse' => $validatedData['libelleadresse'],
            ], 'idadresse');
        }

        if (!$adresseId) {
            return redirect()->back()->withErrors(['Erreur lors de l’ajout de l’adresse.']);
        }

        $lieuFavoriExist = DB::table('lieu_favori')
            ->where('idclient', $client->idclient)
            ->where('idadresse', $adresseId)
            ->exists();

        if ($lieuFavoriExist) {
            return redirect()->back()->withErrors(['Cette adresse est déjà dans vos lieux favoris.']);
        }

        DB::table('lieu_favori')->insert([
            'idclient' => $client->idclient,
            'idadresse' => $adresseId,
            'nomlieu' => $validatedData['nomlieu'],
        ]);

        return redirect()->route('myaccount')->with('success', 'Lieu favori ajouté avec succès.');
    }

    public function deleteFavoriteAddress($id, Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')->withErrors(['Vous devez être connecté pour gérer vos favoris.']);
        }

        $client = Client::find($sessionUser['id']);

        if (!$client) {
            return redirect()->route('myaccount')->withErrors(['Utilisateur introuvable.']);
        }

        $favorite = DB::table('lieu_favori')
            ->where('idlieufavori', $id)
            ->where('idclient', $client->idclient)
            ->first();

        if (!$favorite) {
            return redirect()->route('account')->withErrors(['Lieu favori introuvable ou non autorisé.']);
        }

        DB::table('lieu_favori')->where('idlieufavori', $id)->delete();

        return redirect()->route('myaccount')->with('success', 'Lieu favori supprimé avec succès.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');

        return redirect('/')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}

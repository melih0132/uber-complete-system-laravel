<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use App\Models\Client;

use App\Models\Coursier;
use App\Models\Vehicule;
use App\Models\Entretien;

use App\Models\ResponsableEnseigne;
use App\Models\Etablissement;

use App\Models\Ville;

// use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private $serviceAccounts = [
        'logistique' => [
            'email' => 'logistique@uber.com',
            'password' => 'logistique123',
        ],
        'facturation' => [
            'email' => 'facturation@uber.com',
            'password' => 'facturation123',
        ],
        'administratif' => [
            'email' => 'administratif@uber.com',
            'password' => 'admin123',
        ],
        'rh' => [
            'email' => 'rh@uber.com',
            'password' => 'ressourceh123',
        ],
        'support' => [
            'email' => 'support@uber.com',
            'password' => 'support123',
        ],
    ];

    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:client,coursier,responsable,logistique,facturation,administratif,rh,support'],
        ]);

        if (in_array($credentials['role'], ['client', 'coursier', 'responsable'])) {
            $user = null;

            if ($credentials['role'] === 'responsable') {
                $user = new ResponsableEnseigne();
            } else {
                $user = new User();
                $user->setRole($credentials['role']);
            }

            $userRecord = $user->where('emailuser', $credentials['email'])->first();

            if (!$userRecord || !Hash::check($credentials['password'], $userRecord->motdepasseuser)) {
                return back()->withErrors([
                    'email' => 'Les informations de connexion sont incorrectes.',
                ])->withInput($request->only('email', 'role'));
            }

            $request->session()->put('user', [
                'id' => $userRecord->{$user->getKeyName()},
                'role' => $credentials['role'],
            ]);

            if ($credentials['role'] === 'client') {
                return redirect('/')->with('success', 'Connexion réussie.');
            }

            return redirect()->route('mon-compte')->with('success', 'Connexion réussie.');
        }

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

            return redirect()->route('mon-compte')->with('success', 'Connexion réussie.');
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

        switch ($sessionUser['role']) {
            case 'responsable':
                $user = ResponsableEnseigne::find($sessionUser['id']);
                if ($user) {
                    $etablissements = Etablissement::with('categories')
                        ->where('idresponsable', $user->idresponsable)
                        ->get();
                }
                break;

                case 'client':
                    $user = Client::find($sessionUser['id']);
                    if ($user) {
                        $courses = DB::table('course')
                        ->leftJoin('reservation', 'course.idreservation', '=', 'reservation.idreservation')
                        ->join('adresse as start_address', 'course.idadresse', '=', 'start_address.idadresse')
                        ->join('adresse as end_address', 'course.adr_idadresse', '=', 'end_address.idadresse')
                        ->select(
                            'course.idcourse',
                            'course.datecourse',
                            'course.heurecourse',
                            'course.prixcourse',
                            'course.statutcourse',
                            'course.notecourse',
                            'start_address.libelleadresse as start_address',
                            'end_address.libelleadresse as end_address'
                        )
                        ->where('reservation.idclient', $user->idclient)
                        ->orderBy('course.datecourse', 'desc')
                        ->get();
                    
                
                        // Récupération des lieux favoris du client
                        $favorites = DB::table('lieu_favori')
                            ->join('adresse', 'lieu_favori.idadresse', '=', 'adresse.idadresse')
                            ->select('lieu_favori.idlieufavori', 'lieu_favori.nomlieu', 'adresse.libelleadresse')
                            ->where('lieu_favori.idclient', $user->idclient)
                            ->get();
                
                        // Récupération de toutes les villes
                        $villes = Ville::all();
                    }
                    break;
                

            case 'coursier':
                $user = Coursier::find($sessionUser['id']);
                if ($user) {
                    $canDrive = $user->vehicules()->where('statusprocessuslogistique', 'Validé')->exists();
                    $vehicules = $user->vehicules;
                    $entretien = $user->entretien()->orderBy('dateentretien', 'desc')->first();
                }
                break;

            default:
                return redirect()->route('login')->withErrors(['Rôle utilisateur inconnu.']);
        }

        if (!$user) {
            $request->session()->forget('user');
            return redirect()->route('login')->withErrors(['Utilisateur introuvable. Veuillez vous reconnecter.']);
        }

        /* dd($favorites); */

        /* dd($sessionUser['role']); */

        /* dd($courses); */

        return view('mon-compte', [
            'user' => $user,
            'role' => $sessionUser['role'],
            'etablissements' => $etablissements,
            'courses' => $courses,
            'favorites' => $favorites,
            'villes' => $villes,
            'canDrive' => $canDrive,
            'vehicules' => $vehicules,
            'entretien' => $entretien,
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
        $request->validate([
            'libelleadresse' => 'required|string|max:100',
            'idville' => 'required|exists:ville,idville',
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

        $adresseId = DB::table('adresse')->insertGetId([
            'idville' => $request->idville,
            'libelleadresse' => $request->libelleadresse,
        ]);

        DB::table('lieu_favori')->insert([
            'idclient' => $client->idclient,
            'idadresse' => $adresseId,
            'nomlieu' => $request->nomlieu,
        ]);

        return redirect()->route('account')->with('success', 'Lieu favori ajouté avec succès.');
    }

    public function deleteFavoriteAddress($id, Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')->withErrors(['Vous devez être connecté pour gérer vos favoris.']);
        }

        $client = Client::find($sessionUser['id']);

        if (!$client) {
            return redirect()->route('account')->withErrors(['Utilisateur introuvable.']);
        }

        $favorite = DB::table('lieu_favori')
            ->where('idlieufavori', $id)
            ->where('idclient', $client->idclient)
            ->first();

        if (!$favorite) {
            return redirect()->route('account')->withErrors(['Lieu favori introuvable ou non autorisé.']);
        }

        DB::table('lieu_favori')->where('idlieufavori', $id)->delete();

        return redirect()->route('account')->with('success', 'Lieu favori supprimé avec succès.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');

        return redirect('/')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}

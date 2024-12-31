<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use App\Models\Client;

use App\Models\Coursier;
use App\Models\Vehicule;
use App\Models\Entretien;

//use Illuminate\Support\Facades\Auth;

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
            'role' => ['required', 'in:client,coursier,logistique,facturation,administratif,rh,support'],
        ]);

        if (in_array($credentials['role'], ['client', 'coursier'])) {
            $user = new User();
            $user->setRole($credentials['role']);

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

    public function monCompte(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser) {
            return redirect()->route('interface-connexion')->withErrors(['Vous devez être connecté pour accéder à cette page.']);
        }

        $role = $sessionUser['role'];
        $userId = $sessionUser['id'] ?? null;

        if (in_array($role, ['client', 'coursier'])) {
            $user = new User();
            $user->setRole($role);

            $userRecord = $user->find($userId);

            if (!$userRecord) {
                $request->session()->forget('user');
                return redirect()->route('login')->withErrors(['Utilisateur introuvable. Veuillez vous reconnecter.']);
            }

            $data = [
                'user' => $userRecord,
                'role' => $role,
            ];

            if ($role === 'coursier') {
                $coursier = Coursier::find($userRecord->idcoursier);

                if ($coursier) {
                    $vehicules = $coursier->vehicules;
                    $entretien = $coursier->entretien;
                    $canDrive = $vehicules->contains('statusprocessuslogistique', 'Validé');

                    $data['vehicules'] = $vehicules;
                    $data['entretien'] = $entretien;
                    $data['canDrive'] = $canDrive;
                } else {
                    $data['vehicules'] = [];
                    $data['entretien'] = [];
                    $data['canDrive'] = false;
                }
            }

            return view('mon-compte', $data);
        }

        if (isset($sessionUser['email']) && isset($sessionUser['role'])) {
            return view('mon-compte', [
                'user' => $sessionUser,
                'role' => $role,
            ]);
        }

        return redirect()->route('login')->withErrors(['Rôle ou utilisateur invalide.']);
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

    public function logout(Request $request)
    {
        $request->session()->forget('user');

        return redirect('/')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}

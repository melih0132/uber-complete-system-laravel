<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Coursier;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        // Valider les champs requis
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:client,coursier'],
        ]);

        // Vérification basée sur le rôle
        $user = $credentials['role'] === 'client'
            ? Client::where('email', $credentials['email'])->first()
            : Coursier::where('email', $credentials['email'])->first();


        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Les informations de connexion sont incorrectes.',
            ]);
        }

        // Stocker l'utilisateur dans la session
        $request->session()->put('user', $user);

        dd($user);

        // Redirection après connexion réussie
        return redirect()->intended('/mon-compte')->with('success', 'Connexion réussie.');
    }

    public function monCompte(Request $request)
    {
        // Récupérer les informations utilisateur depuis la session
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser) {
            return redirect('/login')->withErrors(['Vous devez être connecté pour accéder à cette page.']);
        }

        // Charger l'utilisateur selon le rôle
        $user = null;
        if ($sessionUser['role'] === 'client') {
            $user = Client::find($sessionUser['id']);
        } elseif ($sessionUser['role'] === 'coursier') {
            $user = Coursier::find($sessionUser['id']);
        }

        if (!$user) {
            return redirect('/login')->withErrors(['Utilisateur introuvable. Veuillez vous reconnecter.']);
        }

        return view('mon-compte', [
            'user' => $user,
            'role' => $sessionUser['role'],
        ]);
    }

    public function logout(Request $request)
    {
        // Supprimer les informations utilisateur de la session
        $request->session()->forget('user');

        // Redirection après déconnexion
        return redirect('/')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}

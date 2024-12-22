<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Coursier;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:client,coursier'],
        ]);

        if ($credentials['role'] === 'client') {
            $user = Client::where('emailuser', $credentials['email'])->first();
        } elseif ($credentials['role'] === 'coursier') {
            $user = Coursier::where('emailuser', $credentials['email'])->first();
        } else {
            $user = null;
        }

        if (!$user || !Hash::check($credentials['password'], $user->motdepasseuser)) {
            return back()->withErrors([
                'email' => 'Les informations de connexion sont incorrectes.',
            ]);
        }

        $request->session()->put('user', [
            'id' => $credentials['role'] === 'client' ? $user->idclient : $user->idcoursier,
            'role' => $credentials['role'],
        ]);

        return redirect()->intended('/mon-compte')->with('success', 'Connexion réussie.');
    }

    public function monCompte(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser) {
            return redirect('/login')->withErrors(['Vous devez être connecté pour accéder à cette page.']);
        }

        $user = $sessionUser['role'] === 'client'
            ? Client::find($sessionUser['id'])
            : Coursier::find($sessionUser['id']);

        if (!$user) {
            $request->session()->forget('user');
            return redirect('/login')->withErrors(['Utilisateur introuvable. Veuillez vous reconnecter.']);
        }

        return view('mon-compte', [
            'user' => $user,
            'role' => $sessionUser['role'],
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

    public function logout(Request $request)
    {
        $request->session()->forget('user');

        return redirect('/')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}

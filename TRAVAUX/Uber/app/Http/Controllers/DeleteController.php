<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DeleteController extends Controller
{
    public function destroy()
    {
        $user = Auth::user();

        if ($user) {

            $user->delete();

            // Déconnecte l'utilisateur et redirige
            Auth::logout();
            return redirect('/')->with('success', 'Votre compte a été supprimé.');
        }
        return redirect()->back()->with('error', 'Impossible de supprimer le compte.');
    }
}

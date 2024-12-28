<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarteBancaire;

class CarteBancaireController extends Controller
{
    public function create()
    {
        return view('carte-bancaire');
    }

    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'numerocb' => ['required', 'digits:16', 'numeric'],
            'dateexpirecb' => ['required', 'date', 'after:today'],
            'cryptogramme' => ['required', 'digits:3', 'numeric'],
            'typecarte' => ['required', 'string', 'in:Crédit,Débit'],
            'typereseaux' => ['required', 'string', 'in:Visa,MasterCard'],
        ], [
            'numerocb.required' => 'Le numéro de la carte est requis.',
            'numerocb.digits' => 'Le numéro de la carte doit contenir exactement 16 chiffres.',
            'numerocb.numeric' => 'Le numéro de la carte doit être composé uniquement de chiffres.',

            'dateexpirecb.required' => 'La date d\'expiration est requise.',
            'dateexpirecb.after' => 'La date d\'expiration doit être dans le futur.',

            'cryptogramme.required' => 'Le cryptogramme est requis.',
            'cryptogramme.digits' => 'Le cryptogramme doit contenir exactement 3 chiffres.',
            'cryptogramme.numeric' => 'Le cryptogramme doit être composé uniquement de chiffres.',

            'typecarte.required' => 'Le type de carte est requis.',
            'typecarte.in' => 'Le type de carte doit être "Crédit" ou "Débit".',

            'typereseaux.required' => 'Le type de réseau est requis.',
            'typereseaux.in' => 'Le type de réseau doit être "Visa" ou "MasterCard".',
        ]);

        CarteBancaire::create([
            'numerocb' => $validated['numerocb'],
            'dateexpirecb' => $validated['dateexpirecb'],
            'cryptogramme' => $validated['cryptogramme'],
            'typecarte' => $validated['typecarte'],
            'typereseaux' => $validated['typereseaux'],
        ]);

        return redirect()->back()->with('success', 'La carte a été ajoutée avec succès.');
    }
}

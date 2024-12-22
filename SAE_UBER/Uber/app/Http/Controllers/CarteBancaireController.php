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
    // Валидация данных
    $validated = $request->validate([
        'numerocb' => ['required', 'digits:16', 'numeric'], // Exactement 16 chiffres
        'dateexpirecb' => ['required', 'date', 'after:today'], // La date ne doit pas être dans le passé
        'cryptogramme' => ['required', 'digits:3', 'numeric'], // Exactement 3 chiffres
        'typecarte' => ['required', 'string', 'in:Visa,mastercard,americanexpress'], // Doit être l'une des valeurs spécifiées
        'typereseaux' => ['required', 'string', 'in:CB'], // Doit être CB
    ], [
        // Messages d'erreur
        'numerocb.required' => 'Le numéro de la carte est requis.',
        'numerocb.digits' => 'Le numéro de la carte doit contenir exactement 16 chiffres.',
        'numerocb.numeric' => 'Le numéro de la carte doit être composé uniquement de chiffres.',

        'dateexpirecb.required' => 'La date d\'expiration est requise.',
        'dateexpirecb.after' => 'La date d\'expiration doit être dans le futur.',

        'cryptogramme.required' => 'Le cryptogramme est requis.',
        'cryptogramme.digits' => 'Le cryptogramme doit contenir exactement 3 chiffres.',
        'cryptogramme.numeric' => 'Le cryptogramme doit être composé uniquement de chiffres.',

        'typecarte.required' => 'Le type de carte est requis.',
        'typecarte.in' => 'Le type de carte doit être Visa, MasterCard ou American Express.',

        'typereseaux.required' => 'Le type de réseau est requis.',
        'typereseaux.in' => 'Le type de réseau doit être CB.',
    ]);

    // Sauvegarde de la carte dans la base de données
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


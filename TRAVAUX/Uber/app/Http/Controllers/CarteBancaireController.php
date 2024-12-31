<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarteBancaire;

class CarteBancaireController extends Controller
{
    public function index(Request $request)
    {
        $userSession = $request->session()->get('user');

        if ($userSession['role'] !== 'client') {
            abort(403, 'Accès non autorisé');
        }

        $cartes = CarteBancaire::whereHas('clients', function ($query) use ($userSession) {
            $query->where('client.idclient', $userSession['id']);
        })->get();

        return view('cartes.index', compact('cartes'));
    }

    public function create()
    {
        return view('carte-bancaire.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numerocb' => ['required', 'digits:16', 'numeric'],
            'dateexpirecb' => ['required', 'date_format:Y-m', 'after:today'],
            'cryptogramme' => ['required', 'digits:3', 'numeric'],
            'typecarte' => ['required', 'string', 'in:Crédit,Débit'],
            'typereseaux' => ['required', 'string', 'in:Visa,MasterCard'],
        ], [
            'numerocb.required' => 'Le numéro de la carte est requis.',
            'numerocb.digits' => 'Le numéro de la carte doit contenir exactement 16 chiffres.',
            'numerocb.numeric' => 'Le numéro de la carte doit être composé uniquement de chiffres.',

            'dateexpirecb.required' => 'La date d\'expiration est requise.',
            'dateexpirecb.date_format' => 'La date d\'expiration doit être au format AAAA-MM.',
            'dateexpirecb.after' => 'La date d\'expiration doit être dans le futur.',

            'cryptogramme.required' => 'Le cryptogramme est requis.',
            'cryptogramme.digits' => 'Le cryptogramme doit contenir exactement 3 chiffres.',
            'cryptogramme.numeric' => 'Le cryptogramme doit être composé uniquement de chiffres.',

            'typecarte.required' => 'Le type de carte est requis.',
            'typecarte.in' => 'Le type de carte doit être "Crédit" ou "Débit".',

            'typereseaux.required' => 'Le type de réseau est requis.',
            'typereseaux.in' => 'Le type de réseau doit être "Visa" ou "MasterCard".',
        ]);

        $validated['dateexpirecb'] = $validated['dateexpirecb'] . '-01';

        $userSession = $request->session()->get('user');

        $carte = CarteBancaire::create($validated);
        $carte->clients()->attach($userSession['id']);

        return redirect()->route('carte-bancaire.index')->with('success', 'La carte a été ajoutée avec succès.');
    }
}

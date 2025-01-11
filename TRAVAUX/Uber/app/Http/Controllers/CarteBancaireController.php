<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarteBancaire;

class CarteBancaireController extends Controller
{
    public function index(Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - devenez client');
        }

        if ($userSession['role'] !== 'client') {
            abort(403, 'Accès non autorisé');
        }

        $cartes = CarteBancaire::whereHas('clients', function ($query) use ($userSession) {
            $query->where('client.idclient', $userSession['id']);
        })->get();

        return view('carte-bancaire.index', compact('cartes'));
    }

    public function create()
    {
        return view('carte-bancaire.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'numerocb' => str_replace(' ', '', $request->input('numerocb')),
        ]);

        $numerocb = $request->input('numerocb');
        $typereseaux = $this->detectNetwork($numerocb);

        if (!$typereseaux) {
            return back()->withErrors(['numerocb' => 'Le type de réseau ne peut pas être déterminé.'])->withInput();
        }

        $request->merge(['typereseaux' => $typereseaux]);

        $validated = $request->validate([
            'numerocb' => ['required', 'digits:16', 'numeric'],
            'dateexpirecb' => ['required', 'date_format:Y-m', 'after:today'],
            'cryptogramme' => ['required', 'digits:3', 'numeric'],
            'typecarte' => ['required', 'string', 'in:Crédit,Débit'],
            'typereseaux' => ['required', 'string', 'in:Visa,MasterCard'], // Valider le type de réseau
        ]);

        $validated['dateexpirecb'] = $validated['dateexpirecb'] . '-01';

        $userSession = $request->session()->get('user');

        $carte = CarteBancaire::create($validated);
        $carte->clients()->attach($userSession['id']);

        return redirect()->route('carte-bancaire.index')->with('success', 'La carte a été ajoutée avec succès.');
    }

    public function destroy($idcb, Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - devenez client');
        }

        if ($userSession['role'] !== 'client') {
            abort(403, 'Accès non autorisé');
        }

        $carte = CarteBancaire::whereHas('clients', function ($query) use ($userSession) {
            $query->where('client.idclient', $userSession['id']);
        })->findOrFail($idcb);

        $carte->clients()->detach($userSession['id']);
        $carte->delete();

        return redirect()->route('carte-bancaire.index')->with('success', 'La carte a été supprimée avec succès.');
    }

    private function detectNetwork($numerocb)
    {
        $bin = (int) substr($numerocb, 0, 6);

        if (substr($numerocb, 0, 1) == '4') {
            return 'Visa'; // Commence par 4
        } elseif ($bin >= 222100 && $bin <= 272099 || ($bin >= 510000 && $bin <= 559999)) {
            return 'MasterCard'; // MasterCard (BIN 222100-272099 ou 51-55)
        } elseif (substr($numerocb, 0, 2) == '34' || substr($numerocb, 0, 2) == '37') {
            return 'Amex'; // American Express
        } elseif (substr($numerocb, 0, 4) == '6011' || ($bin >= 622126 && $bin <= 622925) || (substr($numerocb, 0, 3) >= '644' && substr($numerocb, 0, 3) <= '649') || substr($numerocb, 0, 2) == '65') {
            return 'Discover'; // Discover
        }

        return null; // Réseau non déterminé
    }
}

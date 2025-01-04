<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Panier;

class CommandeController extends Controller
{
    public function createAndShowCommande(Request $request)
    {
        $user = session('user');
        if (!$user || !isset($user['idclient'])) {
            return redirect('/interface-connexion')->with('error', 'Veuillez vous connecter pour passer une commande.');
        }

        $idclient = $user['idclient'];

        $panier = Panier::where('idclient', $idclient)->with('produits')->first();

        if (!$panier || $panier->produits->isEmpty()) {
            return redirect()->route('panier.index')->with('error', 'Votre panier est vide.');
        }

        $prixTotal = $panier->produits->sum(function ($produit, $_) {
            return $produit->pivot->quantite * $produit->prixproduit;
        });

        $commande = Commande::create([
            'idpanier' => $panier->idpanier,
            'idadresse' => $request->input('idadresse'),
            'adr_idadresse' => $request->input('adr_idadresse'),
            'prixcommande' => $prixTotal,
            'statutcommande' => 'En attente',
            'tempscommande' => now(),
        ]);

        $panier->produits()->detach();

        return redirect()->route('commande.show', ['idcommande' => $commande->idcommande])
            ->with('success', 'Votre commande a été créée avec succès.');
    }

    public function show($idcommande)
    {
        $commande = Commande::with(['panier.client', 'adresse', 'ville', 'codePostal'])
            ->where('idcommande', $idcommande)
            ->first();

        if (!$commande) {
            return redirect()->route('commande.index')->with('error', 'Commande introuvable.');
        }

        $user = session('user');

        $commandes = collect([$commande]);

        return view('commande', compact('commandes', 'user'));
    }
    public function choixLivraison(Request $request)
    {
        session(['mode' => $request->mode]);

        return redirect()->back();
    }
}

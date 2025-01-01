<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanierController extends Controller
{
    public function index()
    {
        $panier = Session::get('panier', []);
        $idProduits = array_filter(array_keys($panier), 'is_numeric');

        $produits = empty($idProduits)
            ? collect([])
            : Produit::whereIn('idproduit', $idProduits)->get();

        return view('panier', [
            'produits' => $produits,
            'quantites' => $panier
        ]);
    }

    public function ajouterAuPanier(Request $request)
    {
        $request->validate([
            'product' => 'required|integer|exists:produit,idproduit',
        ]);

        $idProduit = $request->input('product');
        $quantite = 1;

        $produit = Produit::find($idProduit);
        if (!$produit) {
            return redirect()->back()->with('error', 'Produit non trouvé.');
        }

        $panier = Session::get('panier', []);
        $panier[$idProduit] = ($panier[$idProduit] ?? 0) + $quantite;
        Session::put('panier', $panier);

        return redirect()->route('panier.index')->with('success', 'Produit ajouté au panier avec succès!');
    }

    public function mettreAJour(Request $request, $idProduit)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1|max:100',
        ]);

        $panier = Session::get('panier', []);

        if (isset($panier[$idProduit])) {
            $panier[$idProduit] = $request->input('quantite');
            Session::put('panier', $panier);

            return redirect()->route('panier.index')->with('success', 'Quantité mise à jour avec succès.');
        }

        return redirect()->route('panier.index')->with('error', 'Produit non trouvé dans le panier.');
    }


    public function supprimerDuPanier($idProduit)
    {
        $panier = Session::get('panier', []);

        if (isset($panier[$idProduit])) {
            unset($panier[$idProduit]);
            Session::put('panier', $panier);
        }

        return redirect()->route('panier.index')->with('success', 'Produit supprimé du panier avec succès!');
    }

    public function viderPanier()
    {
        Session::forget('panier');

        return redirect()->route('panier.index')->with('success', 'Le panier a été vidé!');
    }

    public function passerCommande(Request $request)
    {
        $request->validate([
            'idadresse' => 'required|integer|exists:adresse,idadresse',
            'adr_idadresse' => 'required|integer|exists:adresse,idadresse',
            'estlivraison' => 'required|boolean',
        ]);

        $user = Auth::user();
        dd(($user));
        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour passer une commande.');
        }

        $idclient = $user->idclient;
        $panierSession = Session::get('panier', []);

        if (empty($panierSession)) {
            return redirect()->route('panier.index')->with('error', 'Votre panier est vide.');
        }

        try {
            $produits = Produit::whereIn('idproduit', array_keys($panierSession))->get()->keyBy('idproduit');

            $totalPrix = 0;
            foreach ($panierSession as $idProduit => $quantite) {
                if (isset($produits[$idProduit])) {
                    $totalPrix += $produits[$idProduit]->prixproduit * $quantite;
                }
            }

            $panier = Panier::create([
                'idclient' => $idclient,
                'prix' => $totalPrix,
            ]);

            foreach ($panierSession as $idProduit => $quantite) {
                if (isset($produits[$idProduit])) {
                    $panier->produits()->attach($idProduit, ['quantite' => $quantite]);
                }
            }

            $commande = Commande::create([
                'idpanier' => $panier->idpanier,
                'idadresse' => $request->input('idadresse'),
                'adr_idadresse' => $request->input('adr_idadresse'),
                'prixcommande' => $panier->prix,
                'statutcommande' => 'En attente',
                'tempscommande' => 30, // Durée estimée en minutes (à ajuster selon vos besoins)
                'estlivraison' => $request->input('estlivraison'),
            ]);

            // Vider le panier de la session
            // Session::forget('panier');

            return redirect()->route('commande.show', ['idcommande' => $commande->idcommande])
                ->with('success', 'Votre commande a été créée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('panier.index')->with('error', 'Une erreur est survenue lors du passage de la commande.');
        }
    }
}

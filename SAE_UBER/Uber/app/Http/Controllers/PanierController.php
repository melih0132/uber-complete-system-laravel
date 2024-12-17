<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use Illuminate\Support\Facades\Session;

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
        $idProduit = $request->input('product');
        $quantite = 1;

        // Vérifier si le produit existe dans la base de données
        $produit = Produit::find($idProduit);
        if (!$produit) {
            return redirect()->back()->with('error', 'Produit non trouvé.');
        }

        // Récupérer le panier de la session ou initialiser un nouveau panier
        $panier = Session::get('panier', []);

        // Ajouter ou mettre à jour la quantité du produit dans le panier
        $panier[$idProduit] = ($panier[$idProduit] ?? 0) + $quantite;

        // Sauvegarder le panier dans la session
        Session::put('panier', $panier);

        return redirect()->route('panier.index')->with('success', 'Produit ajouté au panier avec succès!');
    }

    public function mettreAJour(Request $request, $idProduit)
    {
        // Valider la quantité reçue
        $request->validate([
            'quantite' => 'required|integer|min:1|max:100',
        ]);

        // Récupérer le panier de la session
        $panier = Session::get('panier', []);

        // Vérifier si le produit est présent dans le panier
        if (isset($panier[$idProduit])) {
            $panier[$idProduit] = $request->input('quantite');
            Session::put('panier', $panier);

            return redirect()->route('panier.index')->with('success', 'Quantité mise à jour avec succès.');
        }

        return redirect()->route('panier.index')->with('error', 'Produit non trouvé dans le panier.');
    }

    public function supprimerDuPanier($idProduit)
    {
        // Récupérer le panier de la session
        $panier = Session::get('panier', []);

        // Supprimer le produit si présent
        if (isset($panier[$idProduit])) {
            unset($panier[$idProduit]);
            Session::put('panier', $panier);
        }

        return redirect()->route('panier.index')->with('success', 'Produit supprimé du panier avec succès!');
    }

    public function viderPanier()
    {
        // Supprimer le panier de la session
        Session::forget('panier');

        return redirect()->route('panier.index')->with('success', 'Le panier a été vidé!');
    }

    public function afficherPanier()
    {
        $previousUrl = session('last_valid_url', route('produits.liste'));
        return view('panier', compact('previousUrl'));
    }
}

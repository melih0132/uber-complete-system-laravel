<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Client;
use App\Models\Panier;
use App\Models\Produit;

class PanierController extends Controller
{
    public function index(Request $request)
    {
        $userSession = $request->session()->get('user');

        if ($userSession && $userSession['role'] === 'client') {
            // Récupérer le client connecté
            $client = Client::find($userSession['id']);

            if (!$client) {
                return redirect()->route('login')->withErrors(['Client introuvable. Veuillez vous reconnecter.']);
            }

            // Charger ou créer un panier pour le client
            $panierDb = Panier::firstOrCreate(['idclient' => $client->idclient]);

            // Charger les produits du panier
            $produits = $panierDb->produits;

            return view('panier', [
                'produits' => $produits,
                'quantites' => $produits->pluck('pivot.quantite', 'idproduit')->toArray(),
            ]);
        }

        // Pour les utilisateurs non connectés, utiliser la session
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

        $userSession = $request->session()->get('user');

        if ($userSession && $userSession['role'] === 'client') {
            $client = Client::find($userSession['id']);
            $panierDb = Panier::firstOrCreate(['idclient' => $client->idclient]);

            // Vérifier si le produit existe dans la table pivot
            $exists = DB::table('contient_2')
                ->where('idpanier', $panierDb->idpanier)
                ->where('idproduit', $idProduit)
                ->exists();

            if ($exists) {
                // Incrémenter la quantité
                DB::table('contient_2')
                    ->where('idpanier', $panierDb->idpanier)
                    ->where('idproduit', $idProduit)
                    ->increment('quantite', $quantite);
            } else {
                // Ajouter le produit avec la quantité initiale
                DB::table('contient_2')->insert([
                    'idpanier' => $panierDb->idpanier,
                    'idproduit' => $idProduit,
                    'quantite' => $quantite,
                ]);
            }

            $produits = DB::table('contient_2')
                ->join('produit', 'contient_2.idproduit', '=', 'produit.idproduit')
                ->where('contient_2.idpanier', $panierDb->idpanier)
                ->select(DB::raw('SUM(produit.prixproduit * contient_2.quantite) as total'))
                ->first();

            $montantTotal = $produits->total ?? 0;

            $panierDb->update(['prix' => $montantTotal]);

            return redirect()->back()->with('success', 'Produit ajouté au panier avec succès!');
        }

        $panier = Session::get('panier', []);
        $panier[$idProduit] = ($panier[$idProduit] ?? 0) + $quantite;
        Session::put('panier', $panier);

        return redirect()->back()->with('success', 'Produit ajouté au panier avec succès!');
    }

    public function mettreAJour(Request $request, $idProduit)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1|max:100',
        ]);

        $quantite = $request->input('quantite');
        $userSession = $request->session()->get('user');

        if ($userSession && $userSession['role'] === 'client') {
            $client = Client::find($userSession['id']);
            $panierDb = Panier::firstOrCreate(['idclient' => $client->idclient]);

            $panierDb->produits()->updateExistingPivot($idProduit, ['quantite' => $quantite]);

            return redirect()->route('panier.index')->with('success', 'Quantité mise à jour avec succès.');
        }

        // Utilisateur non connecté : mise à jour du panier en session
        $panier = Session::get('panier', []);

        if (isset($panier[$idProduit])) {
            $panier[$idProduit] = $quantite;
            Session::put('panier', $panier);

            return redirect()->route('panier.index')->with('success', 'Quantité mise à jour avec succès.');
        }

        return redirect()->route('panier.index')->with('error', 'Produit non trouvé dans le panier.');
    }

    public function supprimerDuPanier($idProduit, Request $request)
    {
        $userSession = $request->session()->get('user');

        if ($userSession && $userSession['role'] === 'client') {
            // Client connecté : suppression en base de données
            $client = Client::find($userSession['id']);
            $panierDb = Panier::firstOrCreate(['idclient' => $client->idclient]);

            $panierDb->produits()->detach($idProduit);

            return redirect()->route('panier.index')->with('success', 'Produit supprimé du panier avec succès!');
        }

        // Utilisateur non connecté : suppression en session
        $panier = Session::get('panier', []);

        if (isset($panier[$idProduit])) {
            unset($panier[$idProduit]);
            Session::put('panier', $panier);
        }

        return redirect()->route('panier.index')->with('success', 'Produit supprimé du panier avec succès!');
    }

    public function viderPanier(Request $request)
    {
        $userSession = $request->session()->get('user');

        if ($userSession && $userSession['role'] === 'client') {
            // Client connecté : suppression du panier en base de données
            $client = Client::find($userSession['id']);
            $panierDb = Panier::firstOrCreate(['idclient' => $client->idclient]);

            $panierDb->produits()->detach();

            return redirect()->route('panier.index')->with('success', 'Le panier a été vidé!');
        }

        // Utilisateur non connecté : suppression en session
        Session::forget('panier');

        return redirect()->route('panier.index')->with('success', 'Le panier a été vidé!');
    }
}

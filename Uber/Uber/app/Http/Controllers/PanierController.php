<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\Client;
use App\Models\Panier;
use App\Models\Produit;

class PanierController extends Controller
{
    public function index(Request $request)
    {
        $userSession = $request->session()->get('user');

        if ($userSession && $userSession['role'] === 'client') {

            $client = Client::find($userSession['id']);

            if (!$client) {
                return redirect()->route('login')->withErrors(['Client introuvable. Veuillez vous reconnecter.']);
            }

            $panierDb = Panier::firstOrCreate(['idclient' => $client->idclient]);
            $produits = $panierDb->produits;

            return view('panier', [
                'produits' => $produits,
                'quantites' => $produits->pluck('pivot.quantite', 'idproduit')->toArray(),
            ]);
        }

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

            $idetablissement = DB::table('est_situe_a_2')
                ->where('idproduit', $produit->idproduit)
                ->value('idetablissement');

            if (!$idetablissement) {
                return redirect()->back()->withErrors(['error' => 'Le produit n\'est pas associé à un établissement.']);
            }

            $exists = DB::table('contient_2')
                ->where('idpanier', $panierDb->idpanier)
                ->where('idproduit', $idProduit)
                ->where('idetablissement', $idetablissement)
                ->exists();

            if ($exists) {
                DB::table('contient_2')
                    ->where('idpanier', $panierDb->idpanier)
                    ->where('idproduit', $idProduit)
                    ->where('idetablissement', $idetablissement)
                    ->increment('quantite', $quantite);
            } else {
                DB::table('contient_2')->insert([
                    'idpanier' => $panierDb->idpanier,
                    'idproduit' => $idProduit,
                    'idetablissement' => $idetablissement,
                    'quantite' => $quantite,
                ]);
            }

            $montantTotal = DB::table('contient_2')
                ->join('produit', 'contient_2.idproduit', '=', 'produit.idproduit')
                ->where('contient_2.idpanier', $panierDb->idpanier)
                ->sum(DB::raw('produit.prixproduit * contient_2.quantite'));

            $panierDb->update(['prix' => $montantTotal]);

            return redirect()->back()->with('success', 'Produit ajouté au panier avec succès !');
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

        if ($quantite > 99) {
            return redirect()->route('panier.index')->with('error', 'Pas assez de stock.');
        }

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
            $client = Client::find($userSession['id']);
            $panierDb = Panier::firstOrCreate(['idclient' => $client->idclient]);

            $panierDb->produits()->detach($idProduit);

            return redirect()->route('panier.index')->with('success', 'Produit supprimé du panier avec succès!');
        }

        $panier = Session::get('panier', []);

        if (isset($panier[$idProduit])) {
            unset($panier[$idProduit]);
            Session::put('panier', $panier);
        }

        return redirect()->route('panier.index')->with('success', 'Produit supprimé du panier avec succès!');
    }

    public function viderPanier()
    {
        $sessionUser = session('user');
        if (!$sessionUser) {
            return redirect()->route('login')->withErrors(['message' => 'Vous devez être connecté pour continuer.']);
        }

        $panier = Panier::where('idclient', $sessionUser['id'])->first();
        if ($panier) {
            DB::table('contient_2')
                ->where('idpanier', $panier->idpanier)
                ->delete();
        }

        return redirect()->route('panier.index')->with('success', 'Votre panier a été vidé.');
    }
}

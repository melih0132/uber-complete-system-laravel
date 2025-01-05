<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Client;
use App\Models\CarteBancaire;
use App\Models\Panier;
use App\Models\Produit;
use App\Models\Commande;

class CommandeController extends Controller
{
    public function choisirModeLivraison(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')->withErrors([
                'message' => 'Vous devez être connecté en tant que client pour effectuer une commande.',
            ]);
        }

        return view('commande.choix_livraison');
    }

    public function choisirModeLivraisonStore(Request $request)
    {
        $validatedData = $request->validate([
            'modeLivraison' => 'required|in:livraison,retrait',
        ]);

        Session::put('modeLivraison', $validatedData['modeLivraison']);

        return redirect()->route('commande.choisirCarteBancaire');
    }

    public function choisirCarteBancaire(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')->withErrors([
                'message' => 'Vous devez être connecté en tant que client pour continuer.',
            ]);
        }

        try {
            $cartes = CarteBancaire::whereHas('clients', function ($query) use ($sessionUser) {
                $query->where('client.idclient', $sessionUser['id']);
            })->get();

            if ($cartes->isEmpty()) {
                return redirect()->route('client.profile')->withErrors([
                    'message' => 'Aucune carte bancaire associée à votre compte. Veuillez en ajouter une pour continuer.',
                ]);
            }

            return view('commande.choix_carte', compact('cartes'));
        } catch (\Exception $e) {
            return redirect()->route('commande.choixLivraison')->withErrors([
                'message' => 'Une erreur est survenue lors de la récupération des cartes bancaires. Veuillez réessayer plus tard.',
            ]);
        }
    }

    public function paiementCarte(Request $request)
    {
        $validatedData = $request->validate([
            'carte_id' => 'required|exists:carte_bancaire,idcb',
        ]);

        Session::put('carte_id', $validatedData['carte_id']);

        if (!Session::has('modeLivraison')) {
            return redirect()->route('commande.choixLivraison')->withErrors([
                'message' => 'Veuillez sélectionner un mode de livraison.',
            ]);
        }

        return redirect()->route('commande.enregistrer');
    }

    public function enregistrerCommande(Request $request)
    {
        $sessionUser = $request->session()->get('user');
        $modeLivraison = Session::get('modeLivraison');
        $carteId = Session::get('carte_id');

        // Vérifications initiales
        if (!$sessionUser) {
            return redirect()->route('login')->withErrors(['message' => 'Vous devez être connecté pour continuer.']);
        }

        if (!$modeLivraison) {
            return redirect()->route('commande.choixLivraison')->withErrors(['message' => 'Veuillez sélectionner un mode de livraison.']);
        }

        if (!$carteId) {
            return redirect()->route('commande.choisirCarteBancaire')->withErrors(['message' => 'Veuillez sélectionner une carte bancaire.']);
        }

        // Récupérer le client et son panier
        $client = Client::findOrFail($sessionUser['id']);
        $panier = Panier::where('idclient', $client->idclient)
            ->with('produits')
            ->first();

        if (!$panier || $panier->produits->isEmpty()) {
            return redirect()->route('panier.index')->withErrors(['message' => 'Votre panier est vide. Veuillez ajouter des produits avant de continuer.']);
        }

        DB::beginTransaction();

        try {
            $carte = CarteBancaire::findOrFail($carteId);

            // Calculer le prix total
            $prixTotal = $panier->produits->reduce(function ($total, $produit) {
                return $total + $produit->pivot->quantite * $produit->prixproduit;
            }, 0);

            // Mettre à jour le panier avec le prix total
            $panier->update(['prix' => $prixTotal]);

            // Créer la commande
            $commande = Commande::create([
                'idpanier' => $panier->idpanier,
                'idadresse' => $client->idadresse,
                'prixcommande' => $prixTotal,
                'tempscommande' => 30,
                'heurecommande' => now(),
                'estlivraison' => $modeLivraison === 'livraison',
                'statutcommande' => 'En attente',
            ]);

            Session::forget(['modeLivraison', 'carte_id']);

            DB::commit();

            return redirect()->route('commande.confirmation', ['id' => $commande->idcommande]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('commande.choisirCarteBancaire')->withErrors([
                'message' => 'Une erreur s\'est produite lors de la création de la commande. Veuillez réessayer.',
            ]);
        }
    }

    public function confirmation($id)
    {
        $commande = Commande::with(['panier.produits', 'client', 'adresseDestination'])->findOrFail($id);

        return view('commande.confirmation', [
            'commande' => $commande,
            'produits' => $commande->panier->produits,
            'adresse' => $commande->adresseDestination,
            'client' => $commande->client,
        ]);
    }
}

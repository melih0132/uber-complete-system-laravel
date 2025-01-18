<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Stripe\Stripe;
use Stripe\Checkout\Session  as StripeSession;

use App\Models\Client;
use App\Models\CarteBancaire;
use App\Models\Facture;

use App\Models\Etablissement;

use App\Models\Panier;
use App\Models\Produit;
use App\Models\Commande;

use App\Models\Adresse;
use App\Models\CodePostal;
use App\Models\Ville;

class CommandeController extends Controller
{
    public function mesCommandes(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || !isset($sessionUser['id'])) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour voir vos commandes.');
        }

        $userId = $sessionUser['id'];
        $client = Client::find($userId);

        if (!$client) {
            return redirect()->route('login')->with('error', 'Utilisateur introuvable.');
        }

        $commandes = Commande::parClient($userId)
            ->orderBy('heurecreation', 'desc')
            ->orderBy('idcommande', 'desc')
            ->paginate(10);

        return view('commande.mes-commandes', compact('commandes', 'client'));
    }

    public function informerRefus(Request $request, $idCommande)
    {
        $sessionUser = $request->session()->get('user');
        $userId = $sessionUser['id'];

        $commande = Commande::where('id', $idCommande)->first();

        if (!$commande) {
            return redirect()->back()->with('error', 'Commande introuvable.');
        }

        if (!$commande->panier || $commande->panier->idclient != $userId) {
            return redirect()->back()->with('error', 'Cette commande ne vous appartient pas.');
        }

        if ($commande->refus_demandee) {
            return redirect()->back()->with('warning', 'Vous avez déjà demandé un refus pour cette commande.');
        }

        $commande->refus_demandee = true;
        $commande->save();

        return redirect()->route('commande.mesCommandes')->with('success', 'Votre souhait de refus a été envoyé au service commande.');
    }
































    public function index(Request $request)
    {
        $commandes = Commande::orderBy('statutcommande', 'desc')->paginate(10);
        return view('commandes.index', compact('commandes'));
    }

    public function enregistrerRefus(Request $request, $idCommande)
    {
        $commande = Commande::where('id', $idCommande)->first();

        if (!$commande) {
            return redirect()->back()->with('error', 'Commande introuvable.');
        }

        if (!$commande->refus_demandee) {
            return redirect()->back()->with('warning', 'Aucune demande de refus pour cette commande.');
        }

        $commande->statutcommande = 'Refusée';
        $commande->refus_demandee = false;
        $commande->save();

        return redirect()->route('commandes.index')->with('success', 'Le refus de la commande a été enregistré.');
    }

    public function rembourserCommande(Request $request, $idCommande)
    {
        $commande = Commande::where('id', $idCommande)->first();

        if (!$commande) {
            return redirect()->back()->with('error', 'Commande introuvable.');
        }

        if ($commande->statutcommande !== 'Refusée') {
            return redirect()->back()->with('error', 'La commande doit être refusée avant de procéder au remboursement.');
        }

        if ($commande->remboursement_effectue) {
            return redirect()->back()->with('warning', 'Le remboursement a déjà été effectué.');
        }

        try {
            // simuler un remboursement car pas effectué de notre part
            $remboursementReussi = true;

            if ($remboursementReussi) {
                $commande->remboursement_effectue = true;
                $commande->statutcommande = 'Remboursée';
                $commande->save();

                return redirect()->route('commandes.index')->with('success', 'Le remboursement a été effectué avec succès.');
            } else {
                throw new \Exception('Échec du remboursement.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors du remboursement. Veuillez réessayer.');
        }
    }

    public function mettreAJourStatut(Request $request, $idCommande)
    {
        $request->validate([
            'statutcommande' => 'required|string|in:En attente de paiement,Paiement validé,En cours,Livrée,Annulée,Refusée,Remboursée'
        ]);

        $commande = Commande::where('id', $idCommande)->first();

        if (!$commande) {
            return redirect()->back()->with('error', 'Commande introuvable.');
        }

        $commande->statutcommande = $request->input('statutcommande');
        $commande->save();

        return redirect()->route('commandes.index')->with('success', 'Le statut de la commande a été mis à jour avec succès.');
    }





















    public function choisirModeLivraison(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')->withErrors([
                'error' => 'Vous devez être connecté en tant que client pour effectuer une commande.',
            ]);
        }

        return view('commande.choix_livraison');
    }

    public function choisirModeLivraisonStore(Request $request)
    {
        $validatedData = $request->validate([
            'modeLivraison' => 'required|in:livraison,retrait',
            'adresse_livraison' => 'nullable|required_if:modeLivraison,livraison|string|max:255',
            'ville' => 'nullable|required_if:modeLivraison,livraison|string|max:255',
            'code_postal' => 'nullable|required_if:modeLivraison,livraison|string|max:10',
        ]);

        Session::put('modeLivraison', $validatedData['modeLivraison']);

        if ($validatedData['modeLivraison'] === 'livraison') {
            $adresseLivraison = [
                'adresse' => $validatedData['adresse_livraison'],
                'ville' => $validatedData['ville'],
                'code_postal' => $validatedData['code_postal'],
            ];
            Session::put('adresseLivraison', $adresseLivraison);
        } else {
            Session::forget('adresseLivraison');
        }

        return redirect()->route('commande.choisirCarteBancaire');
    }

    public function choisirCarteBancaire(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')->withErrors([
                'error' => 'Vous devez être connecté en tant que client pour continuer.',
            ]);
        }

        try {
            $cartes = CarteBancaire::whereHas('clients', function ($query) use ($sessionUser) {
                $query->where('client.idclient', $sessionUser['id']);
            })->get();

            if ($cartes->isEmpty()) {
                return redirect()->route('myaccount')->withErrors([
                    'error' => 'Aucune carte bancaire associée à votre compte. Veuillez en ajouter une pour continuer.',
                ]);
            }

            return view('commande.choix_carte', compact('cartes'));
        } catch (\Exception $e) {
            return redirect()->route('commande.choixLivraison')->withErrors([
                'error' => 'Une erreur est survenue lors de la récupération des cartes bancaires. Veuillez réessayer plus tard.',
            ]);
        }
    }

    public function enregistrerCommande(Request $request)
    {
        $sessionUser = $request->session()->get('user');
        if (!$sessionUser) {
            return redirect()->route('login')->withErrors(['error' => 'Vous devez être connecté pour continuer.']);
        }

        $modeLivraison = Session::get('modeLivraison');
        $adresseLivraison = Session::get('adresseLivraison');
        $idcb = $request->input('carte_id');

        Session::put('carte_id', $request->input('carte_id'));

        if ($modeLivraison === 'livraison' && (!$adresseLivraison || !is_array($adresseLivraison))) {
            return redirect()->route('commande.choixLivraison')->withErrors([
                'error' => 'Veuillez fournir une adresse de livraison.',
            ]);
        }

        if (!$idcb) {
            return redirect()->route('commande.choisirCarteBancaire')->withErrors(['error' => 'Veuillez sélectionner une carte bancaire.']);
        }

        $client = Client::findOrFail($sessionUser['id']);
        $panier = Panier::where('idclient', $client->idclient)
            ->with('produits')
            ->first();

        if (!$panier || $panier->produits->isEmpty()) {
            return redirect()->route('panier.index')->withErrors(['error' => 'Votre panier est vide.']);
        }

        $estLivraison = $modeLivraison === 'livraison';
        $fraisLivraison = $estLivraison ? 3.00 : 0;

        DB::beginTransaction();

        try {
            $produitsParEtablissement = $panier->produits->groupBy(fn($produit) => $produit->pivot->idetablissement);

            $commandes = [];
            foreach ($produitsParEtablissement as $idetablissement => $produits) {
                $etablissement = Etablissement::findOrFail($idetablissement);

                $adresseId = $estLivraison
                    ? $this->getOrCreateAdresse($adresseLivraison)->idadresse
                    : $etablissement->idadresse;

                $prixCommande = $produits->sum(function ($produit) {
                    $quantite = $produit->pivot->quantite ?? 1;
                    $prixProduit = $produit->prixproduit ?? 0;
                    return $quantite * $prixProduit;
                });

                if ($estLivraison) {
                    $prixCommande += $fraisLivraison;
                }

                $heureCreation = now();
                $heureLivraison = (clone $heureCreation)->addMinutes(30);

                $commande = Commande::create([
                    'idpanier' => $panier->idpanier,
                    'idlivreur' => null,
                    'idcb' => $idcb,
                    'idadresse' => $adresseId,
                    'prixcommande' => $prixCommande,
                    'tempscommande' => 30,
                    'heurecreation' => $heureCreation,
                    'heurecommande' => $heureLivraison,
                    'estlivraison' => $estLivraison,
                    'statutcommande' => 'En attente de paiement',
                ]);

                $commandes[] = $commande;
            }

            DB::commit();

            Session::put('commandes', $commandes);

            return $this->paiementCarte($commandes[0]->idcommande);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => 'Une erreur est survenue lors de la création de la commande.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function paiementCarte($idCommande)
    {
        $commandes = Session::get('commandes');
        if (!$commandes) {
            return redirect()->route('panier.index')->withErrors(['error' => 'Aucune commande en attente de paiement.']);
        }

        $total = collect($commandes)->sum(function ($commande) {
            return is_numeric($commande['prixcommande']) ? $commande['prixcommande'] : 0;
        });

        if ($total <= 0) {
            return redirect()->route('panier.index')
                ->withErrors(['error' => 'Le montant total de la commande est invalide.']);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $stripeSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Commande UberEats',
                        ],
                        'unit_amount' => $total * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('commande.confirmation', ['id' => $idCommande]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('panier.index'),
            ]);

            return redirect($stripeSession->url);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur Stripe : ' . $e->getMessage()]);
        }
    }

    public function confirmation(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('panier.index')->withErrors(['error' => 'Session Stripe invalide.']);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $stripeSession = StripeSession::retrieve($sessionId);
            if ($stripeSession->payment_status !== 'paid') {
                return redirect()->route('panier.index')->withErrors(['error' => 'Paiement non validé.']);
            }

            $commandesData = Session::get('commandes');
            if (empty($commandesData)) {
                return redirect()->route('panier.index')->withErrors(['error' => 'Aucune commande trouvée en session.']);
            }

            $carteId = intval(Session::get('carte_id'));
            if (!$carteId) {
                return redirect()->route('commande.choisirCarteBancaire')->withErrors(['error' => 'Carte bancaire non spécifiée.']);
            }

            foreach ($commandesData as $commandeData) {
                $commande = Commande::find($commandeData['idcommande']);
                if ($commande) {
                    $commande->update([
                        'idcb' => $carteId,
                        'statutcommande' => 'Paiement validé',
                    ]);

                    if ($commande->panier) {
                        $commande->panier->update(['prix' => $commande->prixcommande]);
                    }
                }
            }

            $clientId = Session::get('user')['id'] ?? null;
            if (!$clientId) {
                return redirect()->route('panier.index')->withErrors(['error' => 'Utilisateur non connecté.']);
            }

            $client = Client::find($clientId);
            if (!$client) {
                return redirect()->route('panier.index')->withErrors(['error' => 'Client introuvable.']);
            }

            $commande = Commande::with('panier.produits')->find($commandesData[0]['idcommande']);
            if (!$commande) {
                return redirect()->route('panier.index')->withErrors(['error' => 'Commande introuvable.']);
            }

            $produits = $commande->panier->produits;

            Session::forget(['commandes', 'carte_id']);

            return view('commande.confirmation', compact('client', 'commande', 'produits'))->with([
                'success' => 'Votre paiement a été effectué avec succès !',
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return redirect()->route('panier.index')->withErrors(['error' => 'Erreur Stripe : ' . $e->getMessage()]);
        } catch (\Exception $e) {
            return redirect()->route('panier.index')->withErrors(['error' => 'Erreur lors de la confirmation : ' . $e->getMessage()]);
        }
    }

    private function getOrCreateAdresse(array $adresseLivraison)
    {
        $codePostal = CodePostal::firstOrCreate([
            'codepostal' => $adresseLivraison['code_postal'],
            'idpays' => 1,
        ]);

        $ville = Ville::firstOrCreate([
            'nomville' => $adresseLivraison['ville'],
            'idcodepostal' => $codePostal->idcodepostal,
            'idpays' => 1,
        ]);
        return Adresse::firstOrCreate([
            'libelleadresse' => $adresseLivraison['adresse'],
            'idville' => $ville->idville,
        ]);
    }
}

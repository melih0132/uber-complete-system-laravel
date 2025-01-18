<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Livreur;
use App\Models\Commande;

class LivreurController extends Controller
{
    public function index(Request $request)
    {
        $user = session('user');

        if (!$user || $user['role'] !== 'livreur') {
            return redirect()->route('login-coursier')->with('error', 'Accès refusé.');
        }

        $livreur = Livreur::with('adresse.ville')->findOrFail($user['id']);
        $villeLivreur = $livreur->adresse->ville->nomville ?? null;

        $tasks = Commande::with(['adresse.ville', 'panier.client'])
            ->whereNull('idlivreur')
            ->where('statutcommande', 'Paiement validé')
            ->livraison()
            ->when($villeLivreur, function ($query) use ($villeLivreur) {
                $query->whereHas('adresse.ville', function ($q) use ($villeLivreur) {
                    $q->where('nomville', $villeLivreur);
                });
            })
            ->orderBy('heurecreation', 'asc')
            ->get();

        return view('conducteurs.course-en-attente', [
            'layout' => 'layouts.ubereats',
            'tasks' => $tasks,
            'type' => 'livraisons'
        ]);
    }

    public function acceptTaskLivreur($idcommande)
    {
        $user = session('user');

        if (!$user || $user['role'] !== 'livreur') {
            return redirect()->route('login-coursier')->with('error', 'Accès refusé.');
        }

        try {
            Commande::where('idcommande', $idcommande)
                ->whereNull('idlivreur')
                ->where('statutcommande', 'Paiement validé')
                ->lockForUpdate()
                ->firstOrFail()
                ->update([
                    'idlivreur' => $user['id'],
                    'statutcommande' => 'En cours',
                ]);

            return redirect()->route('livreur.livraisons.index')->with('success', 'Livraison acceptée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('livreur.livraisons.index')->withErrors(['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    public function cancelTaskLivreur($idcommande)
    {
        $user = session('user');

        if (!$user || $user['role'] !== 'livreur') {
            return redirect()->route('login-coursier')->withErrors(['message' => 'Accès refusé.']);
        }

        try {
            DB::transaction(function () use ($idcommande, $user) {
                $commande = Commande::where('idcommande', $idcommande)
                    ->where('idlivreur', $user['id'])
                    ->where('statutcommande', 'En cours')
                    ->lockForUpdate()
                    ->firstOrFail();

                $commande->update([
                    'statutcommande' => 'En attente',
                    'idlivreur' => null
                ]);
            });

            return redirect()->route('livreur.livraisons.index')->with('success', 'Livraison annulée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('livreur.livraisons.index')->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function finishTaskLivreur(Request $request, $idcommande)
    {
        $user = session('user');

        if (!$user || $user['role'] !== 'livreur') {
            return redirect()->route('login-coursier')->withErrors(['message' => 'Accès refusé.']);
        }

        try {
            Commande::where('idcommande', $idcommande)
                ->where('idlivreur', $user['id'])
                ->where('statutcommande', 'En cours')
                ->lockForUpdate()
                ->firstOrFail()
                ->update(['statutcommande' => 'Livrée']);

            return redirect()->route('livreur.livraisons.index')->with('success', 'Livraison marquée comme terminée.');
        } catch (\Exception $e) {
            return redirect()->route('livreur.livraisons.index')->withErrors(['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    public function livraisonsEnCours(Request $request)
    {
        $user = session('user');

        if (!$user || $user['role'] !== 'livreur') {
            return redirect()->route('myaccount')->withErrors(['message' => 'Accès refusé.']);
        }

        $livraison = Commande::with('adresse.ville')
            ->where('idlivreur', $user['id'])
            ->where('statutcommande', 'En cours')
            ->first();

        return view('conducteurs.livraisons-en-cours', compact('livraison'));
    }

    public function marquerLivree($idcommande)
    {
        $user = session('user');

        if (!$user || $user['role'] !== 'livreur') {
            return redirect()->route('myaccount')->withErrors(['message' => 'Accès refusé.']);
        }

        try {
            $commande = Commande::where('idcommande', $idcommande)
                ->where('idlivreur', $user['id'])
                ->where('statutcommande', 'En cours')
                ->firstOrFail();

            $commande->update(['statutcommande' => 'Livrée']);

            return redirect()->route('livreur.livraisons.encours')->with('success', 'Livraison marquée comme terminée.');
        } catch (\Exception $e) {
            return redirect()->route('livreur.livraisons.encours')->withErrors(['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }
}

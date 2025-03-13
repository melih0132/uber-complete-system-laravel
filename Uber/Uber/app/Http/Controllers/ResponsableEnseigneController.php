<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Livreur;
use App\Models\Etablissement;
use App\Models\Horaires;
use App\Models\CategoriePrestation;
use App\Models\CategorieProduit;
use App\Models\Commande;
use App\Models\Produit;

use App\Models\Adresse;
use App\Models\Ville;
use App\Models\CodePostal;

use App\Models\ResponsableEnseigne;
use App\Models\GestionEtablissement;
use Carbon\Carbon;

class ResponsableEnseigneController extends Controller
{
    /* PARTIE RESTAURATEUR */
    public function add()
    {
        $categories = CategoriePrestation::all();
        return view('etablissements.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'typeetablissement' => 'required|string|max:255',
                'nometablissement' => 'required|string|max:255',
                'description' => 'nullable|string',
                'livraison' => 'required|boolean',
                'aemporter' => 'required|boolean',
                'codepostal' => 'required|string',
                'nomville' => 'required|string',
                'libelleadresse' => 'required|string',
                'categories' => 'required|array',
                'categories.*' => 'exists:categorie_prestation,idcategorieprestation',
                'horairesouverture.*' => 'nullable|string',
                'horairesfermeture.*' => 'nullable|string',
                'ferme.*' => 'nullable|boolean',
            ]);

            $idrestaurateur = session('user.id');

            if (!$idrestaurateur) {
                return back()->withErrors(['error' => 'Identifiant du restaurateur introuvable. Veuillez vous reconnecter.']);
            }

            $idadresse = $this->getOrCreateAdresse($request);

            $etablissement = Etablissement::create([
                'idrestaurateur' => $idrestaurateur,
                'idadresse' => $idadresse->idadresse,
                'typeetablissement' => $validatedData['typeetablissement'],
                'nometablissement' => $validatedData['nometablissement'],
                'description' => $validatedData['description'],
                'livraison' => $validatedData['livraison'],
                'aemporter' => $validatedData['aemporter'],
            ]);

            $etablissement->categories()->sync($validatedData['categories']);

            foreach (['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $jour) {
                $horairesOuverture = $request->input("horairesouverture.$jour");
                $horairesFermeture = $request->input("horairesfermeture.$jour");
                $ferme = $request->input("ferme.$jour", false);

                Horaires::create([
                    'idetablissement' => $etablissement->idetablissement,
                    'joursemaine' => $jour,
                    'horairesouverture' => $ferme ? null : ($horairesOuverture ? $horairesOuverture . '+01' : null),
                    'horairesfermeture' => $ferme ? null : ($horairesFermeture ? $horairesFermeture . '+01' : null),
                ]);
            }

            return redirect()->route('etablissement.banner.create', ['id' => $etablissement->idetablissement])
                ->with('success', 'Établissement créé avec succès. Vous pouvez maintenant ajouter une bannière.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l’établissement : ' . $e->getMessage()
            ], 500);
        }
    }

    public function addBanner($id)
    {
        $etablissement = Etablissement::findOrFail($id);
        return view('etablissements.banner.create', ['etablissement' => $etablissement]);
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'banner_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'etablissement_id' => 'required|exists:etablissement,idetablissement',
        ]);

        try {
            $etablissement = Etablissement::findOrFail($request->etablissement_id);

            if ($request->hasFile('banner_image')) {
                $path = $request->file('banner_image')->store('etablissements/banners', 'public');

                $etablissement->update(['imageetablissement' => $path]);

                return redirect()->route('etablissement.accueilubereats')
                    ->with('success', 'Bannière ajoutée avec succès.');
            }

            return back()->withErrors(['error' => 'Aucun fichier n’a été téléchargé.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour de la bannière : ' . $e->getMessage()]);
        }
    }

    public function createProduit()
    {
        $categories = CategorieProduit::all();
        $etablissements = Etablissement::getByRestaurateur(session('user.id'));

        return view('manager.produits.create', compact('categories', 'etablissements'));
    }

    public function storeProduit(Request $request)
    {
        $validatedData = $request->validate([
            'idetablissement' => 'required|exists:etablissement,idetablissement',
            'nomproduit' => 'required|string|max:200',
            'prixproduit' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'imageproduit' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'idcategorie' => 'required|exists:categorie_produit,idcategorie',
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('imageproduit')) {
                $imagePath = $request->file('imageproduit')->store('produits', 'public');
            }

            $produit = Produit::create([
                'nomproduit' => $validatedData['nomproduit'],
                'prixproduit' => $validatedData['prixproduit'],
                'description' => $validatedData['description'],
                'imageproduit' => $imagePath,
            ]);

            DB::table('a_3')->insert([
                'idproduit' => $produit->idproduit,
                'idcategorie' => $validatedData['idcategorie'],
            ]);

            DB::table('est_situe_a_2')->insert([
                'idproduit' => $produit->idproduit,
                'idetablissement' => $validatedData['idetablissement'],
            ]);

            return redirect()->route('manager.produits.index')
                ->with('success', 'Produit ajouté avec succès.');
        } catch (\Exception $e) {
            report($e);
            return back()->withErrors(['error' => 'Erreur lors de l’ajout du produit : ' . $e->getMessage()]);
        }
    }

    public function indexProduits()
    {
        $etablissements = Etablissement::getByRestaurateur(session('user.id'));

        $produits = Produit::whereExists(function ($query) use ($etablissements) {
            $query->select(DB::raw(1))
                ->from('est_situe_a_2')
                ->whereRaw('produit.idproduit = est_situe_a_2.idproduit')
                ->whereIn('est_situe_a_2.idetablissement', $etablissements->pluck('idetablissement'));
        })->get();

        return view('manager.produits.index', compact('produits', 'etablissements'));
    }




    /* PARTIE RESPONSABLE ENSEIGNE */
    public function commandes(Request $request)
    {
        $sessionUser = session('user');

        if (!$sessionUser) {
            return redirect()->route('login-manager')->with('error', 'Accès refusé.');
        }

        if ($sessionUser['role'] === 'responsable') {
            $responsable = ResponsableEnseigne::find($sessionUser['id']);
            if (!$responsable) {
                return redirect()->route('login-manager')->with('error', 'Données du responsable introuvables.');
            }

            $etablissements = GestionEtablissement::where('idresponsable', $responsable->idresponsable)
                ->pluck('idetablissement');

            if ($etablissements->isEmpty()) {
                return redirect()->route('login-manager')->with('error', 'Aucun établissement associé.');
            }

            return redirect()->route('responsable.ordernextHour', ['id' => $etablissements->first()]);
        }

        return redirect()->route('login-manager')->with('error', 'Rôle utilisateur inconnu.');
    }

    public function commandesProchaineHeure($idetablissement)
    {
        try {
            $etablissement = Etablissement::findOrFail($idetablissement);

            $commandes = Commande::query()
                ->where('statutcommande', 'Paiement validé')
                ->whereNull('idlivreur')
                ->where('heurecommande', '>=', Carbon::now()->subHour())
                ->whereHas('panier.produits.etablissements', function ($query) use ($idetablissement) {
                    $query->where('est_situe_a_2.idetablissement', $idetablissement);
                })
                ->with(['panier.client', 'panier.produits'])
                ->get()
                ->map(function ($commande) {
                    $heureCommande = Carbon::parse($commande->heurecommande);
                    $tempsCommande = $commande->tempscommande ?? 0;
                    $heurePrev = $heureCommande->addMinutes($tempsCommande)->format('H:i');

                    return [
                        'id_commande' => $commande->idcommande,
                        'prix' => number_format($commande->prixcommande, 2, ',', ' ') . ' €',
                        'nom_client' => optional($commande->panier->client)->nomuser ?? 'Inconnu',
                        'telephone' => optional($commande->panier->client)->telephone ?? 'Inconnu',
                        'heure_prev' => $heurePrev ?? '00:00',
                    ];
                });

            return view('responsable.ordernexthour', compact('commandes'));
        } catch (\Exception $e) {
            report($e);
            return back()->withErrors(['error' => 'Erreur lors de la récupération des commandes : ' . $e->getMessage()]);
        }
    }

    public function assignerLivreur(Request $request, $idcommande)
    {
        $validatedData = $request->validate([
            'idlivreur' => 'required|exists:livreur,idlivreur',
        ]);

        try {
            $commande = Commande::findOrFail($idcommande);

            if (!is_null($commande->idlivreur)) {
                return back()->with('info', 'Un livreur est déjà assigné à cette commande.');
            }

            if (!$commande->estlivraison) {
                return back()->with('error', 'La commande ne peut pas être assignée à un livreur');
            }

            $commande->idlivreur = $validatedData['idlivreur'];
            $commande->statutcommande = 'En cours';
            $commande->save();

            return back()->with('success', 'Livreur assigné avec succès à la commande ID ' . $idcommande);
        } catch (\Exception $e) {
            report($e);
            return back()->withErrors(['error' => 'Erreur lors de l’affectation du livreur : ' . $e->getMessage()]);
        }
    }


    public function searchLivreurs(Request $request)
    {
        $query = $request->query('query');

        if (strlen($query) < 3) {
            return response()->json(['message' => 'Veuillez entrer au moins 3 caractères pour effectuer une recherche.'], 400);
        }

        $livreurs = Livreur::query()
            ->where('nomuser', 'like', "%{$query}%")
            ->orWhere('prenomuser', 'like', "%{$query}%")
            ->orWhere('idlivreur', 'like', "%{$query}%")
            ->limit(10) // limite requête pour optimisation
            ->get(['idlivreur', 'nomuser', 'prenomuser']);

        if ($livreurs->isEmpty()) {
            return response()->json(['message' => 'Aucun livreur trouvé pour votre recherche.'], 404);
        }

        return response()->json($livreurs, 200);
    }
























    private function getOrCreateAdresse(Request $request)
    {
        $codePostal = CodePostal::where('codepostal', $request->codepostal)
            ->where('idpays', 1)
            ->first();

        if (!$codePostal) {
            $codePostal = CodePostal::create([
                'idpays' => 1,
                'codepostal' => $request->codepostal
            ]);
        }

        $ville = Ville::where('nomville', $request->nomville)
            ->where('idcodepostal', $codePostal->idcodepostal)
            ->where('idpays', 1)
            ->first();

        if (!$ville) {
            $ville = Ville::create([
                'nomville' => $request->nomville,
                'idcodepostal' => $codePostal->idcodepostal,
                'idpays' => 1
            ]);
        }

        $adresse = Adresse::where('libelleadresse', $request->libelleadresse)
            ->where('idville', $ville->idville)
            ->first();

        if (!$adresse) {
            $adresse = Adresse::create([
                'libelleadresse' => $request->libelleadresse,
                'idville' => $ville->idville
            ]);
        }

        return $adresse;
    }
}

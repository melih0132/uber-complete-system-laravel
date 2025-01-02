<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Coursier;

use App\Models\Etablissement;
use App\Models\Horaires;
use App\Models\CategoriePrestation;
use App\Models\Commande;

use App\Models\Adresse;
use App\Models\Ville;
use App\Models\Code_postal;

use Carbon\Carbon;
// use Illuminate\Support\Facades\DB;

class ResponsableEnseigneController extends Controller
{
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

            $idresponsable = session('user.id');
            if (!$idresponsable) {
                return back()->withErrors(['error' => 'Identifiant du responsable introuvable. Veuillez vous reconnecter.']);
            }

            $idadresse = $this->getOrCreateAdresse($request);

            $etablissement = Etablissement::create([
                'idresponsable' => $idresponsable,
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
            return back()->withErrors(['error' => 'Erreur lors de la création de l’établissement : ' . $e->getMessage()]);
        }
    }

    public function addBanner($id)
    {
        if (!is_numeric($id)) {
            abort(400, "Invalid ID provided: $id");
        }

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

    public function commandesProchaineHeure($idetablissement)
    {
        try {
            $commandes = Commande::query()
                ->livraison()
                ->enAttente()
                ->whereNull('idcoursier')
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

            $coursiers = Coursier::whereHas('entretien', function ($query) {
                $query->where('resultat', 'Retenu');
            })
                ->whereHas('vehicules', function ($query) {
                    $query->where('statusprocessuslogistique', 'Validé');
                })
                ->get(['idcoursier', 'nomuser', 'prenomuser']);

            if ($coursiers->isEmpty()) {
                session()->flash('info', 'Aucun coursier valide trouvé.');
            }

            return view('manager.ordernexthour', compact('commandes', 'coursiers'));
        } catch (\Exception $e) {
            report($e);
            return back()->withErrors(['error' => 'Erreur lors de la récupération des commandes : ' . $e->getMessage()]);
        }
    }

    public function assignerLivreur(Request $request, $idcommande)
    {
        $request->validate([
            'idcoursier' => 'required|exists:coursier,idcoursier',
        ]);

        try {
            $commande = Commande::findOrFail($idcommande);

            $commande->update([
                'idcoursier' => $request->idcoursier,
                'statutcommande' => 'En cours',
            ]);

            return back()->with('success', 'Coursier assigné avec succès à la commande ID ' . $idcommande);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l’affectation du coursier : ' . $e->getMessage()]);
        }
    }

    public function searchCoursiers(Request $request)
    {
        $query = $request->query('query');

        if (strlen($query) < 3) {
            return response()->json([]);
        }

        $coursiers = Coursier::where('nomuser', 'like', "%{$query}%")
            ->orWhere('prenomuser', 'like', "%{$query}%")
            ->orWhere('idcoursier', 'like', "%{$query}%")
            ->get(['idcoursier', 'nomuser', 'prenomuser']);

        return response()->json($coursiers);
    }

    private function getOrCreateAdresse(Request $request)
    {
        $codePostal = Code_postal::where('codepostal', $request->codepostal)
            ->where('idpays', 1)
            ->first();

        if (!$codePostal) {
            $codePostal = Code_postal::create([
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

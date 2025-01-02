<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etablissement;

use App\Models\Ville;
use App\Models\Adresse;
use App\Models\Code_postal;

use App\Models\CategoriePrestation;
use App\Models\CategorieProduit;
use App\Models\Horaires;

// use App\Models\Produit;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EtablissementController extends Controller
{
    public function index(Request $request)
    {
        $searchVille = $request->input('recherche_ville');
        $selectedJour = $request->input('selected_jour');
        $selectedHoraire = $request->input('selected_horaires');

        $searchTexte = $request->input('recherche_produit');
        $selectedTypeAffichage = $request->input('type_affichage', 'all');
        $selectedTypeEtablissement = $request->input('type_etablissement');
        $selectedTypeLivraison = $request->input('type_livraison');

        $selectedCategoriePrestation = $request->input('categorie_restaurant');
        $selectedCategorieProduit = $request->input('categorie_produit');

        $etablissementsQuery = DB::table('etablissement as e')
            ->join('adresse as a', 'e.idadresse', '=', 'a.idadresse')
            ->join('ville as v', 'a.idville', '=', 'v.idville')
            ->leftJoin('a_comme_categorie as acc', 'e.idetablissement', '=', 'acc.idetablissement')
            ->leftJoin('categorie_prestation as cp', 'acc.idcategorieprestation', '=', 'cp.idcategorieprestation')
            ->select('e.*', 'a.libelleadresse as adresse', 'v.nomville as ville')
            ->distinct();

        if (!empty($searchVille)) {
            $etablissementsQuery->whereRaw("LOWER(v.nomville) LIKE LOWER(?)", ["%{$searchVille}%"]);
        }

        $jourSemaine = $this->getJourSemaine($selectedJour);

        if (!empty($selectedJour) && !empty($selectedHoraire)) {
            try {
                [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);

                $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s+01:00');
                $heureFin = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s+01:00');

                $horairesQuery = DB::table('horaires as h')
                    ->select('h.idetablissement')
                    ->where('h.joursemaine', '=', $jourSemaine)
                    ->where(function ($query) use ($heureDebut, $heureFin) {
                        $query->where('h.horairesouverture', '<=', $heureDebut)
                            ->where('h.horairesfermeture', '>=', $heureFin);
                    })
                    ->pluck('h.idetablissement');

                $etablissementsQuery->whereIn('e.idetablissement', $horairesQuery);
            } catch (\Exception $e) {
                return back()->withErrors(['selected_horaires' => 'Les horaires sélectionnés sont invalides.']);
            }
        }

        $etablissementsQuery
            ->when(!empty($searchTexte), function ($query) use ($searchTexte) {
                return $query->whereRaw("LOWER(e.nometablissement) LIKE LOWER(?)", ["%{$searchTexte}%"]);
            })
            ->when(!empty($selectedTypeEtablissement), function ($query) use ($selectedTypeEtablissement) {
                if ($selectedTypeEtablissement === 'restaurant') {
                    return $query->where('e.typeetablissement', "Restaurant");
                } elseif ($selectedTypeEtablissement === 'epicerie') {
                    return $query->where('e.typeetablissement', "Épicerie");
                }
                return $query;
            })
            ->when(!empty($selectedTypeLivraison), function ($query) use ($selectedTypeLivraison) {
                if ($selectedTypeLivraison === 'retrait') {
                    return $query->where('e.aemporter', true);
                } elseif ($selectedTypeLivraison === 'livraison') {
                    return $query->where('e.livraison', true);
                }
                return $query;
            })
            ->when($selectedCategoriePrestation, function ($query) use ($selectedCategoriePrestation) {
                return $query->where('cp.idcategorieprestation', $selectedCategoriePrestation);
            });

        $etablissements = $selectedTypeAffichage === 'produits' ? collect() : $etablissementsQuery->paginate(6);

        $produitsQuery = DB::table('produit as p')
            ->join('est_situe_a_2 as es', 'p.idproduit', '=', 'es.idproduit')
            ->join('etablissement as e', 'e.idetablissement', '=', 'es.idetablissement')
            ->join('adresse as a', 'e.idadresse', '=', 'a.idadresse')
            ->join('ville as v', 'a.idville', '=', 'v.idville')
            ->join('a_3 as a3', 'a3.idproduit', '=', 'p.idproduit')
            ->join('categorie_produit as cat_prod', 'cat_prod.idcategorie', '=', 'a3.idcategorie')
            ->select('p.*', 'e.nometablissement', 'v.nomville', 'cat_prod.nomcategorie')
            ->distinct();

        if (!empty($searchVille)) {
            $produitsQuery->whereRaw("LOWER(v.nomville) LIKE LOWER(?)", ["%{$searchVille}%"]);
        }

        if (!empty($jourSemaine) && !empty($selectedHoraire)) {
            try {
                [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);

                $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s+01:00');
                $heureFin = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s+01:00');

                $horairesQuery = DB::table('horaires as h')
                    ->select('h.idetablissement')
                    ->where('h.joursemaine', '=', $jourSemaine)
                    ->where(function ($query) use ($heureDebut, $heureFin) {
                        $query->where('h.horairesouverture', '<=', $heureDebut)
                            ->where('h.horairesfermeture', '>=', $heureFin);
                    })
                    ->pluck('h.idetablissement');

                $produitsQuery->whereIn('e.idetablissement', $horairesQuery);
            } catch (\Exception $e) {
                return back()->withErrors(['selected_horaires' => 'Les horaires sélectionnés sont invalides.']);
            }
        }

        $produitsQuery
            ->when(!empty($searchTexte), function ($query) use ($searchTexte) {
                return $query->whereRaw("LOWER(p.nomproduit) LIKE LOWER(?)", ["%{$searchTexte}%"]);
            })
            ->when($selectedCategorieProduit, function ($query) use ($selectedCategorieProduit) {
                return $query->where('cat_prod.idcategorie', $selectedCategorieProduit);
            });

        $produits = $selectedTypeAffichage === 'etablissements' ? collect() : $produitsQuery->paginate(6);

        $categoriesPrestation = CategoriePrestation::all();
        $categoriesProduit = CategorieProduit::all();

        return view('etablissements.etablissement', [
            'etablissements' => $etablissements,
            'produits' => $produits,

            'selectedTypeEtablissement' => $selectedTypeEtablissement,
            'selectedTypeAffichage' => $selectedTypeAffichage,
            'selectedTypeLivraison' => $selectedTypeLivraison,
            'selectedCategoriePrestation' => $selectedCategoriePrestation,
            'selectedCategorieProduit' => $selectedCategorieProduit,

            'searchProduit' => $searchTexte,

            'categoriesPrestation' => $categoriesPrestation,
            'categoriesProduit' => $categoriesProduit
        ]);
    }

    public function accueilubereats(Request $request)
    {
        $searchVille = $request->input('recherche_ville');
        $selectedJour = $request->input('selected_jour') ?: Carbon::now('Europe/Paris')->format('Y-m-d');
        $selectedHoraire = $request->input('selected_horaires');

        $slots = [];
        $start = Carbon::createFromTime(0, 0, 0);
        $end = Carbon::createFromTime(23, 59, 59);

        while ($start->lessThan($end)) {
            $slotStart = $start->format('H:i');
            $start->addMinutes(30);
            $slotEnd = $start->format('H:i');
            $slots[] = "$slotStart - $slotEnd";
        }

        $heureActuelle = Carbon::now('Europe/Paris')->format('H:i');
        $defaultHoraire = null;
        foreach ($slots as $slot) {
            [$slotStart, $slotEnd] = explode(' - ', $slot);
            if ($heureActuelle >= $slotStart && $heureActuelle < $slotEnd) {
                $defaultHoraire = $slot;
                break;
            }
        }

        if (empty($defaultHoraire)) {
            $defaultHoraire = $slots[0] ?? null;
        }

        if (empty($selectedHoraire)) {
            $selectedHoraire = $defaultHoraire;
        }

        return view('accueil-uber-eat', [
            'slots' => $slots,
            'searchVille' => $searchVille,
            'selectedJour' => $selectedJour,
            'selectedHoraire' => $selectedHoraire,
            'defaultHoraire' => $defaultHoraire,
        ]);
    }

    public function detail($idetablissement)
    {
        $etablissement = DB::table('etablissement as e')
            ->join('adresse as a', 'e.idadresse', '=', 'a.idadresse')
            ->join('ville as v', 'a.idville', '=', 'v.idville')
            ->join('code_postal as cp', 'v.idcodepostal', '=', 'cp.idcodepostal')
            ->where('e.idetablissement', $idetablissement)
            ->select(
                'e.*',
                'a.libelleadresse as adresse',
                'v.nomville as ville',
                'cp.codepostal'
            )
            ->first();

        if (!$etablissement) {
            return abort(404, 'Établissement non trouvé');
        }

        $horaires = DB::table('horaires')
            ->where('idetablissement', $idetablissement)
            ->select('joursemaine', 'horairesouverture', 'horairesfermeture')
            ->get();

        $groupedHoraires = [];
        foreach ($horaires as $horaire) {
            $ouverture = is_null($horaire->horairesouverture) ? 'Fermé' : \Carbon\Carbon::parse($horaire->horairesouverture)->format('H:i');
            $fermeture = is_null($horaire->horairesfermeture) ? 'Fermé' : \Carbon\Carbon::parse($horaire->horairesfermeture)->format('H:i');
            $horaireKey = $ouverture === 'Fermé' && $fermeture === 'Fermé' ? 'Fermé' : "$ouverture - $fermeture";

            if (!isset($groupedHoraires[$horaireKey])) {
                $groupedHoraires[$horaireKey] = [];
            }
            $groupedHoraires[$horaireKey][] = $horaire->joursemaine;
        }

        $produits = DB::table('produit as p')
            ->join('est_situe_a_2 as es', 'p.idproduit', '=', 'es.idproduit')
            ->where('es.idetablissement', $idetablissement)
            ->select('p.idproduit', 'p.nomproduit', 'p.prixproduit', 'p.imageproduit', 'p.description')
            ->get();

        $categoriesPrestations = DB::table('a_comme_categorie as acc')
            ->join('categorie_prestation as cp', 'acc.idcategorieprestation', '=', 'cp.idcategorieprestation')
            ->where('acc.idetablissement', $idetablissement)
            ->select('cp.libellecategorieprestation', 'cp.descriptioncategorieprestation', 'cp.imagecategorieprestation')
            ->get();

        return view('etablissements.detail-etablissement', [
            'etablissement' => $etablissement,
            'produits' => $produits,
            'groupedHoraires' => $groupedHoraires,
            'categoriesPrestations' => $categoriesPrestations,
        ]);
    }

    /*     public function add()
    {
        return view('add-etablissement');
    } */

    /*     public function store(Request $request)
    {
        try {
            $adresse = $this->getOrCreateAdresse($request);

            $etablissement = Etablissement::create([
                'idadresse' => $adresse->idadresse,
                'typeetablissement' => $request->typeetablissement,
                'nometablissement' => $request->nometablissement,
                'description' => $request->description,
                'livraison' => $request->livraison,
                'aemporter' => $request->aemporter,
            ]);

            foreach (['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $jour) {
                $ferme = $request->input("ferme.$jour", false);

                $horairesOuverture = $request->input("horairesouverture.$jour");
                $horairesFermeture = $request->input("horairesfermeture.$jour");

                if ($ferme) {
                    $horairesOuverture = null;
                    $horairesFermeture = null;
                }

                Horaires::create([
                    'idetablissement' => $etablissement->idetablissement,
                    'joursemaine' => $jour,
                    'horairesouverture' => $horairesOuverture ? $horairesOuverture . '+01' : null,
                    'horairesfermeture' => $horairesFermeture ? $horairesFermeture . '+01' : null,
                ]);
            }

            return redirect()->route('etablissement.addBanner', $etablissement->idetablissement)
                ->with('success', 'Établissement créé avec succès. Vous pouvez maintenant ajouter une bannière.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création de l’établissement : ' . $e->getMessage()]);
        }
    } */

    /*     public function addBanner($id)
    {
        $etablissement = Etablissement::findOrFail($id);
        return view('add-banner', ['etablissement' => $etablissement]);
    } */

    /*     public function storeBanner(Request $request)
    {
        $request->validate([
            'banner_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'etablissement_id' => 'required|exists:etablissement,idetablissement',
        ]);

        try {
            $etablissement = Etablissement::findOrFail($request->input('etablissement_id'));

            if ($request->hasFile('banner_image')) {
                $path = $request->file('banner_image')->store('etablissements/banners', 'public');

                $etablissement->imageetablissement = $path;
                $etablissement->save();

                return redirect()->route('etablissement.index', $etablissement->idetablissement)
                    ->with('success', 'Bannière ajoutée avec succès.');
            } else {
                return back()->withErrors(['error' => 'Aucun fichier n’a été téléchargé.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour de la bannière : ' . $e->getMessage()]);
        }
    } */

    /*     private function getOrCreateAdresse(Request $request)
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
                'nomville' => $request->nomville
            ]);
        }

        $adresse = Adresse::where('idville', $ville->idville)
            ->where('libelleadresse', $request->libelleadresse)
            ->first();

        if (!$adresse) {
            $adresse = Adresse::create([
                'libelleadresse' => $request->libelleadresse,
                'idville' => $ville->idville
            ]);
        }

        return $adresse;
    } */

    public function getJourSemaine($dateString)
    {
        $date = new \DateTime($dateString);
        $jourSemaine = $date->format('w');
        $jours = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi'
        ];

        return $jours[$jourSemaine];
    }
}

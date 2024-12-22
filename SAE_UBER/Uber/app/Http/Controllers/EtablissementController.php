<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etablissement;
use App\Models\Ville;
use App\Models\Horaires;
use App\Models\Produit;
use App\Models\Adresse;
use App\Models\Code_postal;
use App\Models\Categorie_prestation;
use App\Models\Categorie_produit;
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

        $categoriesPrestation = Categorie_prestation::all();
        $categoriesProduit = Categorie_produit::all();

        return view('etablissement', [
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
        $selectedJour = $request->input('selected_jour');
        $selectedHoraire = $request->input('selected_horaires');

        $horairesQuery = DB::table('horaires as h')
            ->join('etablissement as et', 'et.idetablissement', '=', 'h.idetablissement');

        $jourSemaine = $this->getJourSemaine($selectedJour);
        $horairesQuery->where('h.joursemaine', '=', $jourSemaine);

        $heureDebut = '06:00:00';
        $heureFin = '23:00:00';

        if (!empty($selectedHoraire)) {
            [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);

            $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s');
            $heureFin = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s');
        }

        if (!empty($selectedHoraire)) {
            $horairesQuery->where(function ($query) use ($heureDebut, $heureFin) {
                $query->whereRaw('?::time BETWEEN h.horairesouverture AND h.horairesfermeture', [$heureDebut])
                    ->whereRaw('?::time BETWEEN h.horairesouverture AND h.horairesfermeture', [$heureFin]);
            });
        }

        $etablissementsFiltreHoraires = $horairesQuery->pluck('et.idetablissement')->unique();

        $slots = [];
        $start = Carbon::createFromFormat('H:i:s', $heureDebut);
        $end = Carbon::createFromFormat('H:i:s', $heureFin);

        while ($start->lessThan($end)) {
            $slotStart = $start->format('H:i');
            $start->addMinutes(30);
            $slotEnd = $start->format('H:i');
            $slots[] = "$slotStart - $slotEnd";
        }

        return view('accueil-uber-eat', [
            'slots' => $slots,
            'searchVille' => $searchVille,
            'selectedJour' => $selectedJour,
            'selectedHoraire' => $selectedHoraire,
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

        $horaires = DB::table('horaires as h')
            ->join('etablissement as e', 'e.idetablissement', '=', 'h.idetablissement')
            ->where('e.idetablissement', $idetablissement)
            ->get();

        $produits = DB::table('produit as p')
            ->join('est_situe_a_2 as es', 'p.idproduit', '=', 'es.idproduit')
            ->where('es.idetablissement', $idetablissement)
            ->select('p.*')
            ->get();

        return view('detail-etablissement', [
            'etablissement' => $etablissement,
            'produits' => $produits,
            'horaires' => $horaires
        ]);
    }

    public function add()
    {
        return view('add-etablissement');
    }

    public function store(Request $request)
    {
        $imagePath = null;
        if ($request->hasFile('imageetablissement') && $request->file('imageetablissement')->isValid()) {
            $imagePath = $request->file('imageetablissement')->store('images/etablissements', 'public');
        }

        $adresse = $this->getOrCreateAdresse($request);

        $etablissement = Etablissement::create([
            'idadresse' => $adresse->idadresse,
            'typeetablissement' => $request->typeetablissement,
            'nometablissement' => $request->nometablissement,
            'description' => $request->description,
            'imageetablissement' => $imagePath,
            'livraison' => $request->livraison,
            'aemporter' => $request->aemporter,
        ]);

        if (!empty($request->categorie_restaurant)) {
            $categories = array_map('trim', explode(',', $request->categorie_restaurant));

            $categoriesPrestationIds = Categorie_prestation::whereIn('libellecategorieprestation', $categories)
                ->pluck('idcategorieprestation')
                ->toArray();

            if (!empty($categoriesPrestationIds)) {
                $etablissement->categories()->sync($categoriesPrestationIds);
            }
        }

        return redirect()->route('etablissement.index')->with('success', 'Établissement créé avec succès.');
    }

    private function getOrCreateAdresse(Request $request)
    {
        $codePostal = Code_postal::where('codepostal', $request->codepostal)
            ->where('idpays', 1)
            ->first();

        if (!$codePostal) {
            $codePostal = Code_postal::create([
                'idpays' => 1,
                'codepostal' => $request->codepostal,
            ]);
        }

        $ville = Ville::where('nomville', $request->nomville)
            ->where('idcodepostal', $codePostal->idcodepostal)
            ->where('idpays', 1)
            ->first();

        if (!$ville) {
            $ville = Ville::create([
                'nomville' => $request->nomville,
            ]);
        }

        $adresse = Adresse::where('idville', $ville->idville)
            ->where('libelleadresse', $request->libelleadresse)
            ->first();

        if (!$adresse) {
            $adresse = Adresse::create([
                'libelleadresse' => $request->libelleadresse,
            ]);
        }

        return $adresse;
    }

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

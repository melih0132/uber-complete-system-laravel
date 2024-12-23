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
        //dd($request->all()); // Débogage pour voir les données envoyées

        // Validation des données
        $validatedData = $request->validate([
            'nometablissement' => 'required|string|max:50',
            'libelleadresse' => 'required|string|max:100',
            'nomville' => 'required|string|max:50',
            'codepostal' => 'required|string|max:5|regex:/^\d{5}$/',
            'typeetablissement' => 'required|in:Restaurant,Épicerie',
            'livraison' => 'required|boolean',
            'aemporter' => 'required|boolean',
            'imageetablissement' => 'nullable|file|max:2048',

            // Validation des horaires
            'horaire_ouverture_lundi'=> 'nullable|date_format:H:i',
            'horaire_fermeture_lundi' => 'nullable|date_format:H:i|after:horaire_ouverture_lundi',
            'ferme_lundi' => 'nullable|boolean',

            'horaire_ouverture_mardi'=> 'nullable|date_format:H:i',
            'horaire_fermeture_mardi' => 'nullable|date_format:H:i|after:horaire_ouverture_mardi',
            'ferme_mardi' => 'nullable|boolean',

            'horaire_ouverture_mercredi'=> 'nullable|date_format:H:i',
            'horaire_fermeture_mercredi' => 'nullable|date_format:H:i|after:horaire_ouverture_mercredi',
            'ferme_mercredi' => 'nullable|boolean',

            'horaire_ouverture_jeudi'=> 'nullable|date_format:H:i',
            'horaire_fermeture_jeudi' => 'nullable|date_format:H:i|after:horaire_ouverture_jeudi',
            'ferme_jeudi' => 'nullable|boolean',

            'horaire_ouverture_vendredi'=> 'nullable|date_format:H:i',
            'horaire_fermeture_vendredi' => 'nullable|date_format:H:i|after:horaire_ouverture_vendredi',
            'ferme_vendredi' => 'nullable|boolean',

            'horaire_ouverture_samedi'=> 'nullable|date_format:H:i',
            'horaire_fermeture_samedi' => 'nullable|date_format:H:i|after:horaire_ouverture_samedi',
            'ferme_samedi' => 'nullable|boolean',

            'horaire_ouverture_dimanche'=> 'nullable|date_format:H:i',
            'horaire_fermeture_dimanche' => 'nullable|date_format:H:i|after:horaire_ouverture_dimanche',
            'ferme_dimanche' => 'nullable|boolean',
        ]);

        try {
            $filePath = null;
            if ($request->hasFile('imageetablissement')) {
                $file = $request->file('imageetablissement');
                $filePath = $file->store('images/etablissements', 'public');
            }

            $adresse = $this->getOrCreateAdresse($request);

            $etablissement = Etablissement::create([
                'idadresse' => $adresse->idadresse,
                'typeetablissement' => $validatedData['typeetablissement'],
                'nometablissement' => $validatedData['nometablissement'],
                'description' => $request->description,
                'imageetablissement' => $filePath,
                'livraison' => $validatedData['livraison'],
                'aemporter' => $validatedData['aemporter'],
            ]);
            $joursSemaine = [
                'lundi' => ['horaire_ouverture_lundi', 'horaire_fermeture_lundi', 'ferme_lundi'],
                'mardi' => ['horaire_ouverture_mardi', 'horaire_fermeture_mardi', 'ferme_mardi'],
                'mercredi' => ['horaire_ouverture_mercredi', 'horaire_fermeture_mercredi', 'ferme_mercredi'],
                'jeudi' => ['horaire_ouverture_jeudi', 'horaire_fermeture_jeudi', 'ferme_jeudi'],
                'vendredi' => ['horaire_ouverture_vendredi', 'horaire_fermeture_vendredi', 'ferme_vendredi'],
                'samedi' => ['horaire_ouverture_samedi', 'horaire_fermeture_samedi', 'ferme_samedi'],
                'dimanche' => ['horaire_ouverture_dimanche', 'horaire_fermeture_dimanche', 'ferme_dimanche']
            ];

            foreach ($joursSemaine as $jour => $horaires) {
                $ferme = $request->input($horaires[2]) ?? false;

                if ($ferme) {
                    continue;
                }

                if (!empty($request->input($horaires[0])) && !empty($request->input($horaires[1]))) {
                    Horaires::create([
                        'idetablissement' => $etablissement->idetablissement,
                        'joursemaine' => ucfirst($jour),
                        'horairesouverture' => Carbon::parse($request->input($horaires[0])),
                        'horairesfermeture' => Carbon::parse($request->input($horaires[1])),
                    ]);
                }
            }

            return redirect()->route('etablissement.index')->with('success', 'Établissement créé avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création de l’établissement : ' . $e->getMessage()]);
        }
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
                'idville' => $ville->idville,
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etablissement;
use App\Models\Ville;
use App\Models\Horaires;
use App\Models\Produit;
use App\Models\Adresse;
use App\Models\Categorie_prestation;
use App\Models\Categorie_produit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EtablissementController extends Controller
{
    public function index(Request $request)
    {
        // Nouveaux paramètres de recherche venant de accueilubereats
        $searchVille = $request->input('recherche_ville');
        $selectedJour = $request->input('selected_jour');
        $selectedHoraire = $request->input('selected_horaires');

        // Récupération des paramètres de la requête
        $searchTexte = $request->input('recherche_produit');
        $selectedTypeAffichage = $request->input('type_affichage', 'all');
        $selectedTypeEtablissement = $request->input('type_etablissement');
        $selectedTypeLivraison = $request->input('type_livraison');

        // Récupération des paramètres de la requête catégorie
        $selectedCategoriePrestation = $request->input('categorie_restaurant');
        $selectedCategorieProduit = $request->input('categorie_produit');

        // Requête de base pour récupérer les établissements
        $etablissementsQuery = DB::table('etablissement as e')
            ->join('adresse as a', 'e.idadresse', '=', 'a.idadresse')
            ->join('ville as v', 'a.idville', '=', 'v.idville')
            ->leftJoin('a_comme_categorie as acc', 'e.idetablissement', '=', 'acc.idetablissement')
            ->leftJoin('categorie_prestation as cp', 'acc.idcategorieprestation', '=', 'cp.idcategorieprestation')
            ->select('e.*', 'a.libelleadresse as adresse', 'v.nomville as ville')
            ->distinct();

        // Filtre par ville (établissements)
        if (!empty($searchVille)) {
            $etablissementsQuery->whereRaw("LOWER(v.nomville) LIKE LOWER(?)", ["%{$searchVille}%"]);
        }

        $jourSemaine = $this->getJourSemaine($selectedJour);

        if (!empty($selectedJour) && !empty($selectedHoraire)) {
            try {
                // Sépare les heures de début et de fin
                [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);

                // Formater les heures pour correspondre au format attendu
                $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s+01:00');
                $heureFin = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s+01:00');

                // Construire la requête pour les horaires
                $horairesQuery = DB::table('horaires as h')
                    ->select('h.idetablissement')
                    ->where('h.joursemaine', '=', $jourSemaine)
                    ->where(function ($query) use ($heureDebut, $heureFin) {
                        $query->where('h.horairesouverture', '<=', $heureDebut)
                              ->where('h.horairesfermeture', '>=', $heureFin);
                    })
                    ->pluck('h.idetablissement');

                // Appliquer les filtres sur les établissements
                $etablissementsQuery->whereIn('e.idetablissement', $horairesQuery);
            } catch (\Exception $e) {
                return back()->withErrors(['selected_horaires' => 'Les horaires sélectionnés sont invalides.']);
            }
        }

        // Application des filtres pour les établissements
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

        // Récupération des établissements
        $etablissements = $selectedTypeAffichage === 'produits' ? collect() : $etablissementsQuery->paginate(6);

        // Requête de base pour les produits
        $produitsQuery = DB::table('produit as p')
            ->join('est_situe_a_2 as es', 'p.idproduit', '=', 'es.idproduit')
            ->join('etablissement as e', 'e.idetablissement', '=', 'es.idetablissement')
            ->join('adresse as a', 'e.idadresse', '=', 'a.idadresse')
            ->join('ville as v', 'a.idville', '=', 'v.idville')
            ->join('a_3 as a3', 'a3.idproduit', '=', 'p.idproduit')
            ->join('categorie_produit as cat_prod', 'cat_prod.idcategorie', '=', 'a3.idcategorie')
            ->select('p.*', 'e.nometablissement', 'v.nomville', 'cat_prod.nomcategorie')
            ->distinct();

        // Filtrer les produits par ville (en fonction des établissements associés)
        if (!empty($searchVille)) {
            $produitsQuery->whereRaw("LOWER(v.nomville) LIKE LOWER(?)", ["%{$searchVille}%"]);
        }

        // Filtrer les produits en fonction des horaires des établissements
        if (!empty($jourSemaine) && !empty($selectedHoraire)) {
            try {
                // Séparer les heures de début et de fin
                [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);

                // Convertir les horaires au format SQL (ISO 8601)
                $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s+01:00');
                $heureFin = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s+01:00');

                // Requête pour récupérer les établissements ouverts aux horaires donnés
                $horairesQuery = DB::table('horaires as h')
                    ->select('h.idetablissement')
                    ->where('h.joursemaine', '=', $jourSemaine)
                    ->where(function ($query) use ($heureDebut, $heureFin) {
                        $query->where('h.horairesouverture', '<=', $heureDebut)
                            ->where('h.horairesfermeture', '>=', $heureFin);
                    })
                    ->pluck('h.idetablissement');

                // Filtrer les produits en fonction des établissements ouverts
                $produitsQuery->whereIn('e.idetablissement', $horairesQuery);
            } catch (\Exception $e) {
                return back()->withErrors(['selected_horaires' => 'Les horaires sélectionnés sont invalides.']);
            }
        }

        // Filtres produits
        $produitsQuery
            ->when(!empty($searchTexte), function ($query) use ($searchTexte) {
                return $query->whereRaw("LOWER(p.nomproduit) LIKE LOWER(?)", ["%{$searchTexte}%"]);
            })
            ->when($selectedCategorieProduit, function ($query) use ($selectedCategorieProduit) {
                return $query->where('cat_prod.idcategorie', $selectedCategorieProduit);
            });

        // Récupération des produits
        $produits = $selectedTypeAffichage === 'etablissements' ? collect() : $produitsQuery->paginate(6);

        // Chargement des catégories de prestation et de produits
        $categoriesPrestation = Categorie_prestation::all();
        $categoriesProduit = Categorie_produit::all();

        // Retourne la vue avec les données nécessaires
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

        // Requête pour filtrer par horaires
        $horairesQuery = DB::table('horaires as h')
            ->join('etablissement as et', 'et.idetablissement', '=', 'h.idetablissement');

        // Filtrer par jour de la semaine si sélectionné
        $jourSemaine = $this->getJourSemaine($selectedJour);
        $horairesQuery->where('h.joursemaine', '=', $jourSemaine);

        // Horaires par défaut (pour couvrir toute la journée)
        $heureDebut = '06:00:00';
        $heureFin = '23:00:00';

        // Si l'utilisateur a sélectionné un créneau horaire, on l'extrait
        if (!empty($selectedHoraire)) {
            [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);

            // Convertir les heures au format PostgreSQL (HH:mm:ss)
            $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s');
            $heureFin = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s');
        }

        // Ajouter la condition de filtrage par créneau horaire
        if (!empty($selectedHoraire)) {
            $horairesQuery->where(function ($query) use ($heureDebut, $heureFin) {
                $query->whereRaw('?::time BETWEEN h.horairesouverture AND h.horairesfermeture', [$heureDebut])
                    ->whereRaw('?::time BETWEEN h.horairesouverture AND h.horairesfermeture', [$heureFin]);
            });
        }

        // Récupérer les ID des établissements correspondant aux horaires filtrés
        $etablissementsFiltreHoraires = $horairesQuery->pluck('et.idetablissement')->unique();

        // Création des créneaux horaires (intervalle de 30 minutes)
        $slots = [];
        $start = Carbon::createFromFormat('H:i:s', $heureDebut);
        $end = Carbon::createFromFormat('H:i:s', $heureFin);

        while ($start->lessThan($end)) {
            $slotStart = $start->format('H:i');
            $start->addMinutes(30);
            $slotEnd = $start->format('H:i');
            $slots[] = "$slotStart - $slotEnd";
        }

        // Retourne la vue avec les paramètres et créneaux horaires générés
        return view('accueil-uber-eat', [
            'slots' => $slots,
            'searchVille' => $searchVille,
            'selectedJour' => $selectedJour,
            'selectedHoraire' => $selectedHoraire,
        ]);
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

    public function detail($idetablissement)
    {
        // Récupérer l'établissement avec ses horaires, adresse et ville associés
        $etablissement = DB::table('etablissement as e')
        ->join('adresse as a', 'e.idadresse', '=', 'a.idadresse')
        ->join('ville as v', 'a.idville', '=', 'v.idville')
        ->join('code_postal as cp', 'v.idcodepostal', '=', 'cp.idcodepostal')
        ->where('e.idetablissement', $idetablissement)
        ->select('e.*',
                'a.libelleadresse as adresse',
                'v.nomville as ville',
                'cp.codepostal')
        ->first();

        $horaires = DB::table('horaires as h')
        ->join('etablissement as e', 'e.idetablissement', '=', 'h.idetablissement')
        ->where('e.idetablissement', $idetablissement)
        ->get();

        // Récupérer les produits associés à l'établissement
        $produits = DB::table('produit as p')
            ->join('est_situe_a_2 as es', 'p.idproduit', '=', 'es.idproduit')
            ->where('es.idetablissement', $idetablissement)
            ->select('p.*')
            ->get();

        // Passer les données à la vue
        return view('detail-etablissement', ['etablissement' => $etablissement,
                                             'produits' => $produits,
                                            'horaires' => $horaires]);
    }

    public function add()
    {
        $categoriesPrestation = Categorie_prestation::all();
        $categoriesProduit = Categorie_produit::all();
        $villes = Ville::all();

        return view('add-etablissement', [
            'villes' => $villes,
            'categoriesPrestation' => $categoriesPrestation,
            'categoriesProduit' => $categoriesProduit,
        ]);
    }

    public function store(Request $request)
    {
        $ville = Ville::where('nomville', $request->nomville)->first();

    if (!$ville) {
        return redirect()->back()->withErrors('Ville non trouvée.');
    }

    $adresse = Adresse::where('libelleadresse', $request->libelleadresse)
                      ->where('codepostal', $request->codepostal)
                      ->where('nomville', $ville->nomville)
                      ->first();

    if (!$adresse) {
        $adresse = Adresse::create([
            'libelleadresse' => $request->libelleadresse,
            'nomville' => $ville->nomville,
            'codepostal' => $request->codepostal,
        ]);
    }
        $newIdEtablissement = Etablissement::max('idetablissement') + 1;
        $newIdAdresse = Adresse::max('idadresse') + 1;

        $newEtablissement = Etablissement::create([
            'idetablissement' => $newIdEtablissement,
            'idadresse' => $newIdAdresse,
            'typeetablissement' => $request->typeetablissement,
            'nometablissement' => $request->nometablissement,
            'description' => $request->description,
            'horaire_ouverture' => $request->horaire_ouverture,
            'horaire_fermeture' => $request->horaire_fermeture,
            'livraison' => $request->livraison,
            'aemporter' => $request->aemporter,
        ]);



        $request->validate([
            'nometablissement' => 'required|string|max:255',
            'typeetablissement' => 'required|string|max:50|in:Restaurant,Épicerie',
            'libelleadresse' => 'required|string|max:255',
            'nomville' => 'required|string|exists:ville,nomville',
            'codepostal' => 'required|string|max:10',
            'categorie_restaurant' => 'required|array|min:1',
            'description' => 'nullable|string|max:1500',
            'horaire_ouverture' => 'required|date_format:H:i',
            'horaire_fermeture' => 'required|date_format:H:i|after:horaire_ouverture',
            'livraison' => 'required|boolean',
            'aemporter' => 'required|boolean',
        ]);



        $request->categories()->sync($request['categorie_restaurant']);

        return redirect()->route('etablissement.index')->with('success', 'Établissement créé avec succès!');


    }

//     public function store(Request $request)
//     {

//         $validated = $request->validate([
//             'nometablissement' => 'required|string|max:255',
//             'idadresse' => 'required|string|max:255',
//             'idville' => 'required|exists:ville,idville',
//             'categorie_restaurant' => 'required|array|min:1',
//             'categorie_restaurant.*' => 'exists:categorie_prestation,idcategorieprestation',
//             'horaire_ouverture' => 'required|date_format:H:i',
//             'horaire_fermeture' => 'required|date_format:H:i|after:horaire_ouverture',
//         ]);
// dd($request->all() );
//         $newIdEtablissement = Etablissement::max('idetablissement') + 1;

//         $newEtablissement = Etablissement::create([
//                  'idetablissement' => $newIdEtablissement,
//                  'typeetablissement' => $request->typeetablissement,
//                  'idadresse' => $request->idadresse,
//                  'nometablissement' => $request->nometablissement,
//                  'description' => $request->description,
//                  'imageetablissement' => $request->imageetablissement,
//                  'livraison' => $request->livraison,
//                  'aemporter' => $request->aemporter,
//              ]);

//             return redirect()->route('connexion')->with('success', 'Votre compte client a été créé avec succès.');
//         }

        // $etablissement = new Etablissement();
        // $etablissement->nometablissement = $request->input('nom');
        // $etablissement->adresse = $request->input('adresse');
        // $etablissement->idville = $request->input('ville');
        // $etablissement->horaire_ouverture = $request->input('horaire_ouverture');
        // $etablissement->horaire_fermeture = $request->input('horaire_fermeture');
        // $etablissement->save();

        // $etablissement->categories()->sync($request->input('categorie_restaurant'));

        // return redirect()->route('etablissement.index')->with('success', 'Établissement créé avec succès !');
    }



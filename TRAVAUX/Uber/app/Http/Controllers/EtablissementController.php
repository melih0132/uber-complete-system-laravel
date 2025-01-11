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

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class EtablissementController extends Controller
{
    public function accueilubereats(Request $request)
    {
        $request->validate([
            'selected_jour' => 'nullable|date_format:d/m/Y',
            'selected_horaires' => 'nullable|string',
        ]);

        $searchVille = $request->input('recherche_ville');
        $inputDate = $request->input('selected_jour');
        $selectedJour = $inputDate
            ? Carbon::createFromFormat('d/m/Y', $inputDate, 'Europe/Paris')->format('Y-m-d')
            : Carbon::now('Europe/Paris')->format('Y-m-d');

        $selectedJour = Carbon::parse($selectedJour, 'Europe/Paris')->isBefore(Carbon::today('Europe/Paris'))
            ? Carbon::today('Europe/Paris')->format('Y-m-d')
            : $selectedJour;

        $selectedHoraire = $request->input('selected_horaires');

        $slots = [];
        $period = CarbonPeriod::create('00:00', '30 minutes', '23:59');
        foreach ($period as $time) {
            $slotStart = $time->format('H:i');
            $slotEnd = $time->copy()->addMinutes(30)->format('H:i');
            if ($slotEnd !== '00:00') {
                $slots[] = "$slotStart - $slotEnd";
            }
        }

        if (empty($slots)) {
            abort(500, 'Aucun créneau horaire disponible.');
        }

        $heureActuelle = Carbon::now('Europe/Paris')->format('H:i');
        $defaultHoraire = collect($slots)->first(fn($slot) => $heureActuelle >= explode(' - ', $slot)[0] && $heureActuelle < explode(' - ', $slot)[1])
            ?? $slots[0] ?? null;

        $selectedHoraire = $selectedHoraire ?: $defaultHoraire;

        return view('accueil-uber-eat', [
            'slots' => $slots,
            'searchVille' => $searchVille,
            'selectedJour' => $selectedJour,
            'selectedHoraire' => $selectedHoraire,
            'defaultHoraire' => $defaultHoraire,
        ]);
    }

    public function index(Request $request)
    {
        $searchVille = $request->input('recherche_ville');
        $selectedJour = $request->input('selected_jour');
        $selectedJour = $selectedJour
            ? \Carbon\Carbon::createFromFormat('d/m/Y', $selectedJour)->format('Y-m-d')
            : \Carbon\Carbon::now('Europe/Paris')->format('Y-m-d');
        $selectedHoraire   = $request->input('selected_horaires');

        $selectedTypeAffichage       = $request->input('type_affichage', 'all'); // "etablissements" ou "produits" ou "all"

        $selectedTypeEtablissement   = $request->input('type_etablissement');
        $selectedTypeLivraison       = $request->input('type_livraison');
        $selectedCategoriePrestation = $request->input('categorie_restaurant');
        $selectedCategorieProduit    = $request->input('categorie_produit');

        // Texte de recherche (nom de produit, etc.)
        $searchTexte = $request->input('recherche_produit');

        // Conversion du jour sélectionné en jour de semaine (lundi, mardi, etc.)
        $jourSemaine = null;
        if (!empty($selectedJour)) {
            $jourSemaine = $this->getJourSemaine($selectedJour);
        }

        // ------------------------------------------------------------------
        // 1. Préparation de la requête des établissements
        // ------------------------------------------------------------------
        $etablissementsQuery = DB::table('etablissement as e')
            ->join('adresse as a', 'e.idadresse', '=', 'a.idadresse')
            ->join('ville as v', 'a.idville', '=', 'v.idville')
            ->leftJoin('a_comme_categorie as acc', 'e.idetablissement', '=', 'acc.idetablissement')
            ->leftJoin('categorie_prestation as cp', 'acc.idcategorieprestation', '=', 'cp.idcategorieprestation')
            ->select('e.*', 'a.libelleadresse as adresse', 'v.nomville as ville')
            ->distinct();

        // Si on veut filtrer par créneau horaire/jour
        if (!empty($jourSemaine) && !empty($selectedHoraire)) {
            try {
                [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);

                // On reformate en H:i:s+01:00 pour coller aux formats en DB
                $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s+01:00');
                $heureFin   = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s+01:00');

                // On recherche les établissements dont les horaires couvrent le créneau sélectionné
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

        // Optionnel : si vous voulez filtrer par ville dans index()
        if (!empty($searchVille)) {
            $etablissementsQuery->whereRaw("LOWER(v.nomville) LIKE LOWER(?)", ["%{$searchVille}%"]);
        }

        // Vous pouvez mettre d'autres filtres basiques ici, selon vos besoins,
        // par ex. type d’établissement, type de livraison, etc.

        // Finalement, on récupère réellement les établissements (pagination par exemple)
        $etablissements = $etablissementsQuery->paginate(6);

        // ------------------------------------------------------------------
        // 2. Préparation de la requête des produits
        // ------------------------------------------------------------------
        $produitsQuery = DB::table('produit as p')
            ->join('est_situe_a_2 as es', 'p.idproduit', '=', 'es.idproduit')
            ->join('etablissement as e', 'e.idetablissement', '=', 'es.idetablissement')
            ->join('adresse as a', 'e.idadresse', '=', 'a.idadresse')
            ->join('ville as v', 'a.idville', '=', 'v.idville')
            ->join('a_3 as a3', 'a3.idproduit', '=', 'p.idproduit')
            ->join('categorie_produit as cat_prod', 'cat_prod.idcategorie', '=', 'a3.idcategorie')
            ->select('p.*', 'e.nometablissement', 'v.nomville', 'cat_prod.nomcategorie')
            ->distinct();

        // Filtrage horaires produits (mêmes conditions que pour les établissements)
        if (!empty($jourSemaine) && !empty($selectedHoraire)) {
            try {
                [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);
                $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s+01:00');
                $heureFin   = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s+01:00');

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

        // Filtrage par ville
        if (!empty($searchVille)) {
            $produitsQuery->whereRaw("LOWER(v.nomville) LIKE LOWER(?)", ["%{$searchVille}%"]);
        }

        // Filtrage par texte saisi (nom de produit par exemple)
        if (!empty($searchTexte)) {
            $produitsQuery->whereRaw("LOWER(p.nomproduit) LIKE LOWER(?)", ["%{$searchTexte}%"]);
        }

        // Récupération (pagination ou non)
        $produits = $produitsQuery->paginate(6);

        // ------------------------------------------------------------------
        // 3. Récupération des catégories associées aux établissements filtrés
        // ------------------------------------------------------------------
        // Pour éviter de charger des catégories qui ne correspondent pas
        // aux établissements réellement filtrés, on récupère la liste ID:
        $filteredEtablissements = $etablissementsQuery->pluck('e.idetablissement');

        $categoriesPrestation = DB::table('categorie_prestation as cp')
            ->join('a_comme_categorie as acc', 'cp.idcategorieprestation', '=', 'acc.idcategorieprestation')
            ->whereIn('acc.idetablissement', $filteredEtablissements)
            ->distinct()
            ->select('cp.idcategorieprestation', 'cp.libellecategorieprestation', 'cp.imagecategorieprestation')
            ->get();

        $categoriesProduit = DB::table('categorie_produit as cat_prod')
            ->join('a_3 as a3', 'cat_prod.idcategorie', '=', 'a3.idcategorie')
            ->join('produit as p', 'p.idproduit', '=', 'a3.idproduit')
            ->join('est_situe_a_2 as es', 'p.idproduit', '=', 'es.idproduit')
            ->whereIn('es.idetablissement', $filteredEtablissements)
            ->distinct()
            ->select('cat_prod.idcategorie', 'cat_prod.nomcategorie')
            ->get();

        // ------------------------------------------------------------------
        // 4. Retourner la vue avec toutes les variables nécessaires
        // ------------------------------------------------------------------
        return view('etablissements.etablissement', [
            'etablissements'              => $etablissements,
            'produits'                    => $produits,

            'selectedTypeAffichage'       => $selectedTypeAffichage,

            'selectedTypeEtablissement'   => $selectedTypeEtablissement,
            'selectedTypeLivraison'       => $selectedTypeLivraison,
            'selectedCategoriePrestation' => $selectedCategoriePrestation,
            'selectedCategorieProduit'    => $selectedCategorieProduit,

            'searchProduit'               => $searchTexte,

            'categoriesPrestation'        => $categoriesPrestation,
            'categoriesProduit'           => $categoriesProduit
        ]);
    }

    public function filtrageEtablissements(Request $request)
    {
        // On récupére la ville et les créneaux horaires
        $searchVille = $request->input('recherche_ville');
        $selectedJour    = $request->input('selected_jour');
        $selectedHoraire = $request->input('selected_horaires');
        $jourSemaine     = (!empty($selectedJour)) ? $this->getJourSemaine($selectedJour) : null;

        $categoriePrestationFiltrees = $request->input('prestations_filtrees', []);
        $categoriesProduitFiltrees = $request->input('categories_produit_filtrees', []);

        // Récupération des champs du formulaire (recherche produit, types, etc.)
        $searchTexte = $request->input('recherche_produit');

        $selectedTypeAffichage       = $request->input('type_affichage', 'all');
        $selectedTypeEtablissement   = $request->input('type_etablissement');
        $selectedTypeLivraison       = $request->input('type_livraison');
        $selectedCategoriePrestation = $request->input('categorie_restaurant');
        $selectedCategorieProduit    = $request->input('categorie_produit');

        // ------------------------------------------------------------------
        // 1. Préparation de la requête "établissements"
        // ------------------------------------------------------------------
        $etablissementsQuery = DB::table('etablissement as e')
            ->join('adresse as a', 'e.idadresse', '=', 'a.idadresse')
            ->join('ville as v', 'a.idville', '=', 'v.idville')
            ->leftJoin('a_comme_categorie as acc', 'e.idetablissement', '=', 'acc.idetablissement')
            ->leftJoin('categorie_prestation as cp', 'acc.idcategorieprestation', '=', 'cp.idcategorieprestation')
            ->select('e.*', 'a.libelleadresse as adresse', 'v.nomville as ville')
            ->distinct();

        // Filtrage par ville
        if (!empty($searchVille)) {
            $etablissementsQuery->whereRaw("LOWER(v.nomville) LIKE LOWER(?)", ["%{$searchVille}%"]);
        }

        // Filtrage basique (recherche sur le nom établissement) ou ce que vous souhaitez
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
            // Si aucun type de livraison n'est sélectionné,
            // on applique par exemple la livraison en "true" par défaut
            ->when(empty($selectedTypeLivraison), function ($query) {
                return $query->where('e.livraison', true);
            })
            ->when($selectedCategoriePrestation, function ($query) use ($selectedCategoriePrestation) {
                return $query->where('cp.idcategorieprestation', $selectedCategoriePrestation);
            });

        // Ici, selon le type d'affichage, on charge ou pas les établissements
        // (exemple : si "produits", on met un tableau vide pour `$etablissements`)
        $etablissements = ($selectedTypeAffichage === 'produits')
            ? collect()
            : $etablissementsQuery->paginate(6);

        // ------------------------------------------------------------------
        // 2. Requête "produits"
        // ------------------------------------------------------------------
        $produitsQuery = DB::table('produit as p')
            ->join('est_situe_a_2 as es', 'p.idproduit', '=', 'es.idproduit')
            ->join('etablissement as e', 'e.idetablissement', '=', 'es.idetablissement')
            ->join('adresse as a', 'e.idadresse', '=', 'a.idadresse')
            ->join('ville as v', 'a.idville', '=', 'v.idville')
            ->join('a_3 as a3', 'a3.idproduit', '=', 'p.idproduit')
            ->join('categorie_produit as cat_prod', 'cat_prod.idcategorie', '=', 'a3.idcategorie')
            ->select('p.*', 'e.nometablissement', 'v.nomville', 'cat_prod.nomcategorie')
            ->distinct();

        // Filtre par ville si besoin
        if (!empty($searchVille)) {
            $produitsQuery->whereRaw("LOWER(v.nomville) LIKE LOWER(?)", ["%{$searchVille}%"]);
        }

        // Filtrage horaires
        if (!empty($jourSemaine) && !empty($selectedHoraire)) {
            try {
                [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);
                $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s+01:00');
                $heureFin   = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s+01:00');

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

        // Filtre par nom de produit
        $produitsQuery->when(!empty($searchTexte), function ($query) use ($searchTexte) {
            return $query->whereRaw("LOWER(p.nomproduit) LIKE LOWER(?)", ["%{$searchTexte}%"]);
        });

        // Filtre par catégorie
        $produitsQuery->when($selectedCategorieProduit, function ($query) use ($selectedCategorieProduit) {
            return $query->where('cat_prod.idcategorie', $selectedCategorieProduit);
        });

        // Ici, selon type d'affichage, on charge ou pas les produits
        $produits = ($selectedTypeAffichage === 'etablissements')
            ? collect()
            : $produitsQuery->paginate(6);

        // ------------------------------------------------------------------
        // 3. Catégories prestation et produit (pour les filtres)
        // ------------------------------------------------------------------
        $categoriesPrestation = DB::table('categorie_prestation as cp')
            ->whereIn('cp.idcategorieprestation', $categoriePrestationFiltrees)
            ->distinct()
            ->select('cp.idcategorieprestation', 'cp.libellecategorieprestation', 'cp.imagecategorieprestation')
            ->get();

        $categoriesProduit = DB::table('categorie_produit as cat_prod')
            ->whereIn('cat_prod.idcategorie', $categoriesProduitFiltrees)
            ->distinct()
            ->select('cat_prod.idcategorie', 'cat_prod.nomcategorie')
            ->get();


        // ------------------------------------------------------------------
        // 4. Retour de la vue filtrée
        // ------------------------------------------------------------------
        return view('etablissements.etablissement', [
            'etablissements'              => $etablissements,
            'produits'                    => $produits,

            'selectedTypeEtablissement'   => $selectedTypeEtablissement,
            'selectedTypeAffichage'       => $selectedTypeAffichage,
            'selectedTypeLivraison'       => $selectedTypeLivraison,
            'selectedCategoriePrestation' => $selectedCategoriePrestation,
            'selectedCategorieProduit'    => $selectedCategorieProduit,

            'searchProduit'               => $searchTexte,

            'categoriesPrestation'        => $categoriesPrestation,
            'categoriesProduit'           => $categoriesProduit
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

        // Regroupement des horaires jour par jour
        $groupedHoraires = [];
        foreach ($horaires as $horaire) {
            $ouverture = is_null($horaire->horairesouverture)
                ? 'Fermé'
                : \Carbon\Carbon::parse($horaire->horairesouverture)->format('H:i');
            $fermeture = is_null($horaire->horairesfermeture)
                ? 'Fermé'
                : \Carbon\Carbon::parse($horaire->horairesfermeture)->format('H:i');

            $horaireKey = ($ouverture === 'Fermé' && $fermeture === 'Fermé')
                ? 'Fermé'
                : "$ouverture - $fermeture";

            if (!isset($groupedHoraires[$horaireKey])) {
                $groupedHoraires[$horaireKey] = [];
            }
            $groupedHoraires[$horaireKey][] = $horaire->joursemaine;
        }

        // Récupération des produits de l’établissement
        $produits = DB::table('produit as p')
            ->join('est_situe_a_2 as es', 'p.idproduit', '=', 'es.idproduit')
            ->where('es.idetablissement', $idetablissement)
            ->select('p.idproduit', 'p.nomproduit', 'p.prixproduit', 'p.imageproduit', 'p.description')
            ->get();

        // Récupération des catégories de prestation
        $categoriesPrestations = DB::table('a_comme_categorie as acc')
            ->join('categorie_prestation as cp', 'acc.idcategorieprestation', '=', 'cp.idcategorieprestation')
            ->where('acc.idetablissement', $idetablissement)
            ->select('cp.libellecategorieprestation', 'cp.descriptioncategorieprestation', 'cp.imagecategorieprestation')
            ->get();

        return view('etablissements.detail-etablissement', [
            'etablissement'       => $etablissement,
            'produits'            => $produits,
            'groupedHoraires'     => $groupedHoraires,
            'categoriesPrestations' => $categoriesPrestations,
        ]);
    }

    private function getJourSemaine($dateString)
    {
        $jours = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];

        return $jours[Carbon::parse($dateString)->dayOfWeek];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etablissement;
use App\Models\Produit;

use App\Models\Ville;
use App\Models\Adresse;

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
        // Validation des entrées
        $request->validate([
            'selected_jour' => 'nullable|date_format:d/m/Y',
            'selected_horaires' => 'nullable|string',
            'recherche_ville' => 'nullable|string|max:255',
        ]);

        // Récupérer les valeurs de la requête
        $searchVille = $request->input('recherche_ville');
        $inputDate = $request->input('selected_jour');
        $selectedHoraire = $request->input('selected_horaires');

        // Gérer la date sélectionnée
        $selectedJour = $inputDate
            ? $this->parseInputDate($inputDate)
            : Carbon::now('Europe/Paris')->format('Y-m-d');

        // Vérifier si la date est antérieure à aujourd'hui
        $selectedJour = Carbon::parse($selectedJour)->isBefore(Carbon::today())
            ? Carbon::today()->format('Y-m-d')
            : $selectedJour;

        // Générer les créneaux horaires
        $slots = $this->generateTimeSlots();
        if (empty($slots)) {
            abort(500, 'Aucun créneau horaire disponible.');
        }

        // Déterminer l'horaire par défaut (selon l'heure actuelle)
        $defaultHoraire = $this->getDefaultTimeSlot($slots);

        // Utiliser l'horaire sélectionné ou par défaut
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
        $inputDate = $request->input('selected_jour');
        $selectedJour = $this->parseInputDate($inputDate) ?? Carbon::today()->format('Y-m-d');
        $selectedHoraire = $request->input('selected_horaires');

        $selectedTypeAffichage       = $request->input('type_affichage', 'all');

        $selectedTypeEtablissement   = $request->input('type_etablissement');
        $selectedTypeLivraison       = $request->input('type_livraison');
        $selectedCategoriePrestation = $request->input('categorie_restaurant');
        $selectedCategorieProduit    = $request->input('categorie_produit');

        $searchTexte = $request->input('recherche_produit');

        $jourSemaine = $selectedJour ? $this->getJourSemaine($selectedJour) : null;

        // Étape 1 : Filtrage des établissements
        $etablissementsQuery = Etablissement::with(['adresse.ville'])
            ->when($searchVille, function ($query, $searchVille) {
                $query->whereHas('adresse.ville', function ($q) use ($searchVille) {
                    $q->whereRaw('LOWER(ville.nomville) LIKE LOWER(?)', ["%{$searchVille}%"]);
                });
            })
            ->when($selectedTypeEtablissement, function ($query, $type) {
                $query->where('typeetablissement', ucfirst($type));
            })
            ->when($selectedTypeLivraison, function ($query, $type) {
                if ($type === 'retrait') {
                    $query->where('aemporter', true);
                } elseif ($type === 'livraison') {
                    $query->where('livraison', true);
                }
            }, function ($query) {
                $query->where('livraison', true); // Par défaut, filtrer par livraison
            })
            ->when($selectedCategoriePrestation, function ($query, $categorie) {
                $query->whereHas('categories', function ($q) use ($categorie) {
                    $q->where('idcategorieprestation', $categorie);
                });
            });

        if (!empty($jourSemaine) && !empty($selectedHoraire)) {
            try {
                [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);
                $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s');
                $heureFin = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s');

                $etablissementsQuery->whereHas('horaires', function ($query) use ($jourSemaine, $heureDebut, $heureFin) {
                    $query->where('joursemaine', $jourSemaine)
                        ->where('heuredebut', '<=', $heureDebut)
                        ->where('heurefin', '>=', $heureFin);
                });
            } catch (\Exception $e) {
                return back()->withErrors(['selected_horaires' => 'Les horaires sélectionnés sont invalides.']);
            }
        }

        // Pagination des établissements
        $etablissements = $selectedTypeAffichage !== 'produits'
            ? $etablissementsQuery->paginate(6)
            : collect();

        // Étape 2 : Filtrage des produits
        $produitsQuery = Produit::with(['categories', 'etablissements.adresse.ville'])
            ->when($selectedCategorieProduit, function ($query, $categorie) {
                $query->whereHas('categories', function ($q) use ($categorie) {
                    $q->where('categorie_produit.idcategorie', $categorie);
                });
            })
            ->when($searchTexte, function ($query, $texte) {
                $query->whereRaw('LOWER(produit.nomproduit) LIKE LOWER(?)', ["%{$texte}%"]);
            })
            ->whereHas('etablissements', function ($query) use ($etablissements) {
                $query->whereIn('etablissement.idetablissement', $etablissements->pluck('idetablissement'));
            });

        // Pagination des produits
        $produits = $selectedTypeAffichage !== 'etablissements'
            ? $produitsQuery->paginate(6)
            : collect();

        // Étape 3 : Récupération des catégories
        $categoriesPrestation = CategoriePrestation::whereHas('etablissements', function ($query) use ($etablissements) {
            $query->whereIn('etablissement.idetablissement', $etablissements->pluck('idetablissement'));
        })->distinct()->get();

        $categoriesProduit = CategorieProduit::whereHas('produits', function ($query) use ($produits) {
            $query->whereIn('produit.idproduit', $produits->pluck('idproduit'));
        })->distinct()->get();

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
        $searchVille = $request->input('recherche_ville');
        $inputDate = $request->input('selected_jour');
        $selectedJour = $this->parseInputDate($inputDate) ?? Carbon::today()->format('Y-m-d');
        $selectedHoraire = $request->input('selected_horaires');

        $selectedTypeAffichage = $request->input('type_affichage', 'all');
        $selectedTypeEtablissement = $request->input('type_etablissement');
        $selectedTypeLivraison = $request->input('type_livraison');
        $selectedCategoriePrestation = $request->input('categorie_restaurant');
        $selectedCategorieProduit = $request->input('categorie_produit');

        $searchTexte = $request->input('recherche_produit');

        $prestationsFiltrees = $request->input('prestations_filtrees', []);
        $categoriesProduitFiltrees = $request->input('categories_produit_filtrees', []);

        $jourSemaine = $selectedJour ? $this->getJourSemaine($selectedJour) : null;

        $etablissementsQuery = Etablissement::with(['adresse.ville', 'categories'])
            ->when($searchVille, function ($query, $searchVille) {
                $query->whereHas('adresse.ville', function ($q) use ($searchVille) {
                    $q->whereRaw('LOWER(nomville) LIKE LOWER(?)', ["%{$searchVille}%"]);
                });
            })
            ->when($selectedTypeEtablissement, function ($query, $type) {
                $query->where('typeetablissement', ucfirst($type));
            })
            ->when($selectedTypeLivraison, function ($query, $type) {
                if ($type === 'retrait') {
                    $query->where('aemporter', true);
                } elseif ($type === 'livraison') {
                    $query->where('livraison', true);
                }
            }, function ($query) {
                $query->where('livraison', true);
            })
            ->when($selectedCategoriePrestation, function ($query, $categorie) {
                $query->whereHas('categories', function ($q) use ($categorie) {
                    $q->where('a_comme_categorie.idcategorieprestation', $categorie);
                });
            })
            ->when($jourSemaine && $selectedHoraire, function ($query) use ($jourSemaine, $selectedHoraire) {
                [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);
                $query->whereHas('horaires', function ($q) use ($jourSemaine, $heureDebut, $heureFin) {
                    $q->where('horaires.joursemaine', $jourSemaine)
                        ->where('horaires.heuredebut', '<=', Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s'))
                        ->where('horaires.heurefin', '>=', Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s'));
                });
            })
            ->when($searchTexte, function ($query, $texte) {
                $query->where(function ($subQuery) use ($texte) {
                    $subQuery->whereRaw('LOWER(nometablissement) LIKE LOWER(?)', ["%{$texte}%"])
                        ->orWhereHas('produits', function ($q) use ($texte) {
                            $q->whereRaw('LOWER(nomproduit) LIKE LOWER(?)', ["%{$texte}%"]);
                        });
                });
            });


        if (!empty($jourSemaine) && !empty($selectedHoraire)) {
            try {
                [$heureDebut, $heureFin] = explode(' - ', $selectedHoraire);
                $heureDebut = Carbon::createFromFormat('H:i', $heureDebut)->format('H:i:s');
                $heureFin = Carbon::createFromFormat('H:i', $heureFin)->format('H:i:s');

                $etablissementsQuery->whereHas('horaires', function ($query) use ($jourSemaine, $heureDebut, $heureFin) {
                    $query->where('joursemaine', $jourSemaine)
                        ->where('heuredebut', '<=', $heureDebut)
                        ->where('heurefin', '>=', $heureFin);
                });
            } catch (\Exception $e) {
                return back()->withErrors(['selected_horaires' => 'Les horaires sélectionnés sont invalides.']);
            }
        }

        // Pagination des établissements
        $etablissements = ($selectedTypeAffichage !== 'produits')
            ? $etablissementsQuery->paginate(6)
            : collect();


        // Étape 2 : Filtrage des produits
        $produitsQuery = Produit::with(['categories', 'etablissements.adresse.ville'])
            ->when($searchTexte, function ($query, $texte) {
                $query->whereRaw('LOWER(nomproduit) LIKE LOWER(?)', ["%{$texte}%"]);
            })
            ->whereHas('etablissements', function ($query) use ($etablissements) {
                $query->whereIn('etablissement.idetablissement', $etablissements->pluck('idetablissement'));
            });

        // Pagination des produits
        $produits = ($selectedTypeAffichage !== 'etablissements')
            ? $produitsQuery->paginate(6)
            : collect();


        // Étape 3 : Filtrage des catégories
        $categoriesPrestation = CategoriePrestation::whereIn('idcategorieprestation', $prestationsFiltrees)
            ->distinct()
            ->get();

        $categoriesProduit = CategorieProduit::whereIn('idcategorie', $categoriesProduitFiltrees)
            ->distinct()
            ->get();


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

    private function parseInputDate($date)
    {
        try {
            return $date
                ? Carbon::createFromFormat('d/m/Y', $date, 'Europe/Paris')->format('Y-m-d')
                : null;
        } catch (\Exception $e) {
            return null; // Return null for invalid dates
        }
    }

    private function generateTimeSlots()
    {
        $slots = [];
        $period = CarbonPeriod::create('00:00', '30 minutes', '23:59');
        foreach ($period as $time) {
            $slotStart = $time->format('H:i');
            $slotEnd = $time->copy()->addMinutes(30)->format('H:i');
            if ($slotEnd !== '00:00') {
                $slots[] = "$slotStart - $slotEnd";
            }
        }
        return $slots;
    }

    private function getDefaultTimeSlot(array $slots)
    {
        $heureActuelle = Carbon::now('Europe/Paris')->format('H:i');
        foreach ($slots as $slot) {
            [$debut, $fin] = explode(' - ', $slot);
            if ($heureActuelle >= $debut && $heureActuelle < $fin) {
                return $slot;
            }
        }
        return $slots[0] ?? null; // Retourner le premier créneau si aucun ne correspond
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

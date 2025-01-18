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
        $searchVille = $request->input('recherche_ville');
        $inputDate = $request->input('selected_jour');
        $selectedHoraire = $request->input('selected_horaires');

        $selectedJour = $inputDate
            ? $this->parseInputDate($inputDate)
            : Carbon::now('Europe/Paris')->format('Y-m-d');

        // si date passé, alors correction
        $selectedJour = Carbon::parse($selectedJour)->isBefore(Carbon::today())
            ? Carbon::today()->format('Y-m-d')
            : $selectedJour;

        $slots = $this->generateTimeSlots();
        if (empty($slots)) {
            abort(500, 'Aucun créneau horaire disponible.');
        }

        $defaultHoraire = $this->getDefaultTimeSlot($slots);

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

        // where raw dangeureux, nécessaire de trouver une alternative (requête sql brut)
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

        // pagination ne marche pas
        $etablissements = $selectedTypeAffichage !== 'produits'
            ? $etablissementsQuery->paginate(6)
            : collect();

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

        // pagination ne marche pas
        $produits = $selectedTypeAffichage !== 'etablissements'
            ? $produitsQuery->paginate(6)
            : collect();

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

        // pagination ne marche pas
        $etablissements = ($selectedTypeAffichage !== 'produits')
            ? $etablissementsQuery->paginate(6)
            : collect();


        $produitsQuery = Produit::with(['categories', 'etablissements.adresse.ville'])
            ->when($searchTexte, function ($query, $texte) {
                $query->whereRaw('LOWER(nomproduit) LIKE LOWER(?)', ["%{$texte}%"]);
            })
            ->whereHas('etablissements', function ($query) use ($etablissements) {
                $query->whereIn('etablissement.idetablissement', $etablissements->pluck('idetablissement'));
            });

        // pagination ne marche pas
        $produits = ($selectedTypeAffichage !== 'etablissements')
            ? $produitsQuery->paginate(6)
            : collect();


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
        $etablissement = Etablissement::with([
            'adresse.ville.codePostal',
            'categories',
            'produits'
        ])->findOrFail($idetablissement);

        $adresse = $etablissement->adresse;
        $ville = $adresse ? $adresse->ville : null;
        $codePostal = $ville ? $ville->codePostal : null;

        $etablissement->adresse = $adresse ? $adresse->libelleadresse : 'Adresse non renseignée';
        $etablissement->ville = $ville ? $ville->nomville : 'Ville inconnue';
        $etablissement->codepostal = $codePostal ? $codePostal->codepostal : 'Code postal inconnu';

        $horaires = Horaires::where('idetablissement', $idetablissement)
            ->select('joursemaine', 'heuredebut', 'heurefin')
            ->get();

        $groupedHoraires = [];

        foreach ($horaires as $horaire) {
            $ouverture = $horaire->heuredebut ? Carbon::parse($horaire->heuredebut)->format('H:i') : 'Fermé';
            $fermeture = $horaire->heurefin ? Carbon::parse($horaire->heurefin)->format('H:i') : 'Fermé';

            $horaireKey = ($ouverture === 'Fermé' && $fermeture === 'Fermé') ? 'Fermé' : "$ouverture - $fermeture";

            if (!isset($groupedHoraires[$horaireKey])) {
                $groupedHoraires[$horaireKey] = [];
            }
            $groupedHoraires[$horaireKey][] = $horaire->joursemaine;
        }

        $produits = $etablissement->produits()->select([
            'produit.idproduit',
            'produit.nomproduit',
            'produit.prixproduit',
            'produit.imageproduit',
            'produit.description'
        ])->get();

        $categoriesPrestations = $etablissement->categories()->select([
            'libellecategorieprestation',
            'descriptioncategorieprestation',
            'imagecategorieprestation'
        ])->get();

        return view('etablissements.detail-etablissement', [
            'etablissement' => $etablissement,
            'produits' => $produits,
            'groupedHoraires' => $groupedHoraires,
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
            return null;
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
        return $slots[0] ?? null;
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

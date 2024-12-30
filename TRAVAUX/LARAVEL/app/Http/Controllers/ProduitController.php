<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produit;
use App\Models\Categorie_prestation;

class ProduitController extends Controller {

    public function index(Request $request)
    {
        $selectedVille = $request->input('ville');
        $selectedTypeLivraison = $request->input('type_livraison');
        $selectedHoraire = $request->input('horaire');
        $selectedCategoriePrestation = $request->input('categorie_restaurant');
        $selectedCategorieProduit = $request->input('categorie_produit');

        /* $query = DB::table('etablissement')
            ->join('adresse as a', 'etablissement.idadresse', '=', 'a.idadresse')
            ->join('ville as v', 'a.idville', '=', 'v.idville')
            ->leftJoin('a_comme_categorie as acc', 'etablissement.idetablissement', '=', 'acc.idetablissement')
            ->leftJoin('categorie_prestation as cp', 'acc.idcategorieprestation', '=', 'cp.idcategorieprestation')
            ->select(
                'etablissement.*',
                'a.libelleadresse as adresse',
                'v.nomville as ville'
            ); */

            $query = DB::table('produit')
            ->join('a_3 a ',  'a.idproduit', '=', 'produit.idproduit')
            ->join('categorie_produit cp', 'cp.idcategorie', '=', 'a.idcategorie' )
            ->leftJoin('a_comme_categorie as acc', 'produit.idproduit', '=', 'acc.idproduit')
            ->leftJoin('categorie_prestation as cp', 'acc.idcategorieprestation', '=', 'cp.idcategorieprestation')
            ->leftJoin('categorie_produit as cp', 'acc.idcategorieproduit', '=', 'cp.idcategorieproduit')
            ->select(
                'nomproduit',
                'nomcategorie',
            );


        if ($selectedVille) {
            $query->where('v.idville', $selectedVille);
        }

        if ($selectedTypeLivraison) {
            if ($selectedTypeLivraison == 'retrait') {
                $query->where('etablissement.aemporter', true);
            } elseif ($selectedTypeLivraison == 'livraison') {
                $query->where('etablissement.livraison', true);
            }
        }

        if ($selectedHoraire) {
            if ($selectedHoraire == 'matin') {
                $query->whereTime('etablissement.horairesouverture', '<=', '12:00:00')
                      ->whereTime('etablissement.horairesfermeture', '>=', '12:00:00');
            } elseif ($selectedHoraire == 'apres-midi') {
                $query->whereTime('etablissement.horairesouverture', '<=', '17:00:00')
                      ->whereTime('etablissement.horairesfermeture', '>=', '17:00:00');
            } elseif ($selectedHoraire == 'soir') {
                $query->whereTime('etablissement.horairesouverture', '<=', '23:00:00')
                      ->whereTime('etablissement.horairesfermeture', '>=', '23:00:00');
            }
        }

        if ($selectedCategoriePrestation) {
            $query->where('acc.idcategorieprestation', $selectedCategoriePrestation);
        }

        $produits = $query->distinct()->get();

        $villes = Ville::all();
        $categoriesPrestation = Categorie_prestation::all();

        return view('etablissement', [
            'etablissements' => $etablissements,
            'villes' => $villes,
            'categoriesPrestation' => $categoriesPrestation,
            'selectedVille' => $selectedVille,
            'selectedTypeLivraison' => $selectedTypeLivraison,
            'selectedHoraire' => $selectedHoraire,
            'selectedCategoriePrestation' => $selectedCategoriePrestation,
        ]);
    }

    public function detail($idetablissement)  {

        $etablissement = DB::table('etablissement')
        ->join('adresse as a', 'etablissement.idadresse', '=', 'a.idadresse')
        ->join('est_situe_a_2 as est','etablissement.idetablissement','=','est.idetablissement')
        ->join('produit as p', 'est.idproduit', '=', 'p.idproduit')
        ->join('ville as v', 'a.idville', '=', 'v.idville')
        ->join('code_postal as cp', 'v.idcodepostal', '=', 'cp.idcodepostal')
        ->where('etablissement.idetablissement', $idetablissement)
        ->select(
            'etablissement.*',
            'a.libelleadresse as adresse',
            'v.nomville as ville',
            'cp.codepostal'
        )
        ->first();

        $produits = DB::table('produit as p')
        ->join('est_situe_a_2 as est','p.idproduit','=','est.idproduit')
        ->join('etablissement as e', 'e.idetablissement', '=', 'est.idetablissement')
        ->where('est.idetablissement', $idetablissement)
        ->select(
            'p.*'
        )
        ->get();

        return view('detail-etablissement', ['etablissement' => $etablissement,
                                            'produits'=>$produits]);
    }

/*     public function rechercheProduit(Request $request)
    {
        $rechercheTexte = $request->input('recherche_produit');

        // Recherche insensible à la casse avec LIKE
        $produits = Produit::whereRaw("LOWER(nomproduit) LIKE LOWER(?)", ["%{$rechercheTexte}%"])->get();

        return view('produit-page', [
            'produits' => $produits,
            'categories' => Categorie_produit::all()
        ]);
    } */
}

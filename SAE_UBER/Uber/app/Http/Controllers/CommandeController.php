<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    public function index(){
        $commandes = Commande::all();

        $views = DB::table('commande as cm')
        ->join('panier as p', 'p.idpanier', '=', 'cm.idpanier')
        ->join('client as c', 'c.idclient', '=', 'p.idclient')
        ->leftJoin('adresse as a', 'a.idadresse', '=', 'cm.idadresse')
        ->leftJoin('ville as v', 'a.idville', '=', 'v.idville')
        ->leftJoin('code_postal as cp', 'v.idcodepostal', '=', 'cp.idcodepostal')
        ->leftJoin('adresse as a2', 'cm.adr_idadresse', '=', 'a2.idadresse')
        ->where('statutcommande', 'En attente')
        ->select(
            'c.nomuser',
            'c.prenomuser',
            'c.genreuser',
            'cm.idadresse',
            'a.libelleadresse as libelle_idadresse',
            'cm.adr_idadresse',
            'cm.idcommande',
            'a2.libelleadresse as libelle_adr_idadresse',
            'v.nomville',
            'cp.codepostal',
            'cm.prixcommande',
            'cm.statutcommande',
            'cm.tempscommande'
        )
        ->get();

        return view('commande',  ['commandes' => $commandes,
                                'views' => $views ]);

    }
}

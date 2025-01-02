<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Models\Coursier;

use App\Models\Entretien;
use App\Models\Vehicule;

class CoursierController extends Controller
{
    private function isCoursierEligible($coursierId)
    {
        $entretienValid = Entretien::where('idcoursier', $coursierId)
            ->where('resultat', 'Retenu')
            ->whereNotNull('rdvlogistiquedate')
            ->whereNotNull('rdvlogistiquelieu')
            ->exists();

        $vehiculeValid = Vehicule::where('idcoursier', $coursierId)
            ->where('statusprocessuslogistique', 'Validé')
            ->exists();

        // Le coursier est éligible si il est retenue à l'entretien rh et a un véhicule validé par le service logistique
        return $entretienValid && $vehiculeValid;
    }

    public function entretien(Request $request)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('myaccount')->with('error', 'Accès refusé.');
        }

        $entretien = Entretien::where('idcoursier', $user['id'])->first();

        if (!$entretien) {
            return redirect()->route('myaccount')->with('error', 'Aucun entretien trouvé.');
        }

        switch ($entretien->status) {
            case 'En attente':
                return view('entretien.en-attente', compact('entretien'));
                break;

            case 'Planifié':
                return view('entretien.planifie', compact('entretien'));
                break;

            case 'Terminée':
                return view('entretien.termine', compact('entretien'));
                break;

            case 'Annulée':
                return view('entretien.annule', compact('entretien'));
                break;

            default:
                return redirect()->route('myaccount')->with('error', 'Statut d\'entretien inconnu.');
        }
    }

    public function validerEntretien($entretienId)
    {
        $entretien = Entretien::findOrFail($entretienId);

        $entretien->status = 'Planifié';
        $entretien->save();

        return redirect()->route('coursier.entretien')->with('success', 'Entretien planifié avec succès.');
    }

    public function annulerEntretien($entretienId)
    {
        $entretien = Entretien::findOrFail($entretienId);

        $entretien->status = 'Annulée';
        $entretien->save();

        return redirect()->route('coursier.entretien')->with('error', 'Entretien annulé.');
    }

    public function planifie(Request $request)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('myaccount')->with('error', 'Accès refusé.');
        }

        $entretien = Entretien::where('idcoursier', $user['id'])->where('status', 'Plannifié')->first();

        if (!$entretien) {
            return redirect()->route('coursier.entretien')->with('error', 'Aucun entretien planifié trouvé.');
        }

        return view('entretien.planifie', compact('entretien'));
    }

    public function index(Request $request)
    {
        $user = session('user');

        if (!$user || !$this->isCoursierEligible($user['id'])) {
            return redirect()->route('myaccount')->with('error', 'Accès refusé.');
        }

        $routeName = $request->route()->getName();
        $isCoursesRoute = str_contains($routeName, 'courses');

        if ($isCoursesRoute) {
            $tasks = DB::table('course as co')
                ->join('reservation as r', 'co.idreservation', '=', 'r.idreservation')
                ->join('client as c', 'r.idclient', '=', 'c.idclient')
                ->join('adresse as a', 'co.idadresse', '=', 'a.idadresse')
                ->leftJoin('ville as v', 'a.idville', '=', 'v.idville')
                ->leftJoin('code_postal as cp', 'v.idcodepostal', '=', 'cp.idcodepostal')
                ->join('adresse as a2', 'co.adr_idadresse', '=', 'a2.idadresse')
                ->where('statutcourse', 'En attente')
                ->select(
                    'c.nomuser',
                    'c.prenomuser',
                    'c.genreuser',
                    'co.idadresse',
                    'a.libelleadresse as libelle_idadresse',
                    'co.adr_idadresse',
                    'r.idreservation',
                    'co.datecourse',
                    'co.heurecourse',
                    'a2.libelleadresse as libelle_adr_idadresse',
                    'v.nomville',
                    'cp.codepostal',
                    'co.prixcourse',
                    'co.statutcourse',
                    'co.distance',
                    'co.temps'
                )
                ->orderBy('idreservation')
                ->get();
        } else {
            $tasks = DB::table('commande as cm')
                ->join('panier as p', 'p.idpanier', '=', 'cm.idpanier')
                ->join('client as c', 'p.idclient', '=', 'c.idclient')
                ->leftJoin('adresse as a', 'cm.idadresse', '=', 'a.idadresse')
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
        }

        return view('conducteurs.course-en-attente', [
            'tasks' => $tasks,
            'type' => $isCoursesRoute ? 'courses' : 'livraisons'
        ]);
    }

    public function acceptTask(Request $request, $idreservation)
    {
        $routeName = $request->route()->getName();
        $isCoursesRoute = str_contains($routeName, 'courses');

        DB::transaction(function () use ($idreservation, $isCoursesRoute) {
            if ($isCoursesRoute) {
                DB::table('course')->where('idreservation', $idreservation)
                    ->update(['idcoursier' => 1, 'statutcourse' => 'En cours']);
            } else {
                DB::table('commande')->where('idcommande', $idreservation)
                    ->update(['idcoursier' => 1, 'statutcommande' => 'En cours']);
            }
        });

        $taskDetails = $isCoursesRoute
            ? DB::table('course as co')
            ->join('reservation as r', 'co.idreservation', '=', 'r.idreservation')
            ->join('client as c', 'r.idclient', '=', 'c.idclient')
            ->leftJoin('adresse as a', 'co.idadresse', '=', 'a.idadresse')
            ->leftJoin('ville as v', 'a.idville', '=', 'v.idville')
            ->leftJoin('adresse as a2', 'co.adr_idadresse', '=', 'a2.idadresse')
            ->where('co.idreservation', $idreservation)
            ->select(
                'r.idreservation',
                'c.nomuser',
                'c.prenomuser',
                'c.genreuser',
                'a.libelleadresse as libelle_idadresse',
                'a2.libelleadresse as libelle_adr_idadresse',
                'v.nomville',
                'co.prixcourse',
                'co.distance',
                'co.temps',
                'co.statutcourse'
            )
            ->first()
            : DB::table('commande as cm')
            ->join('panier as p', 'p.idpanier', '=', 'cm.idpanier')
            ->join('client as c', 'p.idclient', '=', 'c.idclient')
            ->leftJoin('adresse as a', 'cm.idadresse', '=', 'a.idadresse')
            ->leftJoin('ville as v', 'a.idville', '=', 'v.idville')
            ->leftJoin('adresse as a2', 'cm.adr_idadresse', '=', 'a2.idadresse')
            ->where('cm.idcommande', $idreservation)
            ->select(
                'cm.idcommande',
                'c.nomuser',
                'c.prenomuser',
                'c.genreuser',
                'a.libelleadresse as libelle_idadresse',
                'a2.libelleadresse as libelle_adr_idadresse',
                'v.nomville',
                'cm.prixcommande',
                'cm.statutcommande',
                'cm.tempscommande'
            )
            ->first();

        return view('conducteurs.detail-course', [
            'type' => $isCoursesRoute ? 'course' : 'delivery',
            'id' => $idreservation,
            'taskDetails' => $taskDetails
        ]);
    }

    public function cancelTask(Request $request, $idreservation)
    {
        $routeName = $request->route()->getName();
        $isCoursesRoute = str_contains($routeName, 'courses');

        try {
            DB::transaction(function () use ($idreservation, $isCoursesRoute) {
                if ($isCoursesRoute) {
                    DB::table('course')->where('idreservation', $idreservation)
                        ->update(['statutcourse' => 'En attente', 'idcoursier' => null]);
                } else {
                    DB::table('commande')->where('idcommande', $idreservation)
                        ->update(['statutcommande' => 'En attente']);
                }
            });

            return redirect()->route($isCoursesRoute ? 'coursier.courses.index' : 'coursier.livraisons.index')
                ->with('success', 'Tâche annulée avec succès.');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur s\'est produite.', 'error' => $e->getMessage()], 500);
        }
    }

    public function finishTask(Request $request, $idreservation)
    {
        $routeName = $request->route()->getName();
        $isCoursesRoute = str_contains($routeName, 'courses');

        try {
            DB::transaction(function () use ($idreservation, $isCoursesRoute) {
                if ($isCoursesRoute) {
                    DB::table('course')->where('idreservation', $idreservation)
                        ->update(['statutcourse' => 'Terminée']);
                } else {
                    DB::table('commande')->where('idcommande', $idreservation)
                        ->update(['statutcommande' => 'Terminée']);
                }
            });

            return redirect()->route($isCoursesRoute ? 'coursier.courses.index' : 'coursier.livraisons.index')
                ->with('success', 'Tâche terminée avec succès.');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur s\'est produite.', 'error' => $e->getMessage()], 500);
        }
    }
}

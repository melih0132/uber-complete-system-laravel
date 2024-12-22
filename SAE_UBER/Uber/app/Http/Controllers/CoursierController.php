<?php

namespace App\Http\Controllers;

use App\Models\Coursier;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursierController extends Controller
{
    public function index(){
        $coursiers = Coursier::all();
	    $courses = Course::where('statutcourse', 'En attente')->get();

        $views = DB::table('course as co')
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
        ->orderby('idreservation')
        ->get();

        return view('coursier', ['coursiers' => $coursiers,
	                          'courses' => $courses,
                              'views' => $views ]);
    }

    public function setBDAccept($idreservation)
    {

        DB::table('course')
            ->where('idreservation', $idreservation)
            ->update(['idcoursier' => 1, 'statutcourse' => 'En cours']);

        $reservation = DB::table('course as co')
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
            ->first(); // Récupère une seule réservation


        return view('detail-coursier', [
            'idreservation' => $idreservation,
            'reservation' => $reservation
        ]);
    }

    public function setBDCancel($idreservation)
    {
        try {
            DB::table('course')
                ->where('idreservation', $idreservation)
                ->update(['statutcourse' => 'En attente', 'idcoursier' => null]);
            // Après la mise à jour, appeler `index`
            // return $this->index();
            //pour que quand on accepte et annule dans la barre de recherche on a pas annuler
            return redirect()->route('coursier.index')->with('success', 'La course a été annulée avec succès.');
        } catch (\Exception $e) {
            // Gestion des erreurs
            return response()->json(['message' => 'Une erreur s\'est produite.', 'error' => $e->getMessage()], 500);
        }
    }




    public function showTrips()
    {
        $coursiers = Coursier::all();
        return view('coursier-courses', ['coursiers' => $coursiers, 'trips' => [], 'totalAmount' => 0]);
    }

    public function filterTrips(Request $request)
{
    $validated = $request->validate([
        'idcoursier' => 'required|numeric',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $idcoursier = $validated['idcoursier'];
    $startDate = $validated['start_date'];
    $endDate = $validated['end_date'];


    $trips = DB::table('coursier as c')
        ->join('course as co', 'c.idcoursier', '=', 'co.idcoursier')
        ->where('c.idcoursier', $idcoursier)
        ->whereBetween('co.datecourse', [$startDate, $endDate])
        ->select('c.idcoursier', 'co.idcourse', 'co.datecourse', 'co.prixcourse', 'co.pourboire', 'co.distance', 'co.temps')
        ->get();


    $totalAmount = DB::table('coursier as c')
        ->join('course as co', 'c.idcoursier', '=', 'co.idcoursier')
        ->where('c.idcoursier', $idcoursier)
        ->whereBetween('co.datecourse', [$startDate, $endDate])
        ->sum(DB::raw('co.prixcourse + COALESCE(co.pourboire, 0)'));


    $coursiers = Coursier::all();

    $selectedCoursier = Coursier::find($idcoursier);


    return view('coursier-courses', [
        'coursiers' => $coursiers,
        'trips' => $trips,
        'totalAmount' => $totalAmount,
        'idcoursier' => $idcoursier,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'selectedCoursier' => $selectedCoursier
    ]);
}


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Course;
use App\Models\Reservation;

use Carbon\Carbon;

class ServiceCourseController extends Controller
{
    public function index()
    {
        $courses  = DB::table('course as co')
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

        return view('service-course.index', ['courses' => $courses]);
    }

    public function analyse()
    {
        $performances = true;

        return view('service-course.analyse', compact('performances'));
    }

    public function statistiquesMensuelles()
    {
        session()->put('statSession', 'Course');

        $statSession = session('statSession');

        $statistiques = DB::table('course')
            ->selectRaw('EXTRACT(MONTH FROM datecourse) as mois, EXTRACT(YEAR FROM datecourse) as annee, COUNT(*) as total_courses')
            ->groupByRaw('EXTRACT(YEAR FROM datecourse), EXTRACT(MONTH FROM datecourse)')
            ->orderByRaw('EXTRACT(YEAR FROM datecourse), EXTRACT(MONTH FROM datecourse)')
            ->where('statutcourse', 'Terminée')
            ->get();



        return view('service-course.analyseImpact', compact('statistiques'), ['statSession' => $statSession]);
    }

    public function statistiquesMontants()
    {
        session()->put('statSession', 'Montant');

        $statSession = session('statSession');

        $statistiques = DB::table('course')
            ->selectRaw('EXTRACT(MONTH FROM datecourse) as mois, EXTRACT(YEAR FROM datecourse) as annee, SUM(prixcourse) as total_montant')
            ->groupByRaw('EXTRACT(YEAR FROM datecourse), EXTRACT(MONTH FROM datecourse)')
            ->orderByRaw('EXTRACT(YEAR FROM datecourse), EXTRACT(MONTH FROM datecourse)')
            ->where('statutcourse', 'Terminée')
            ->get();



        return view('service-course.analyseImpact', compact('statistiques'), ['statSession' => $statSession]);
    }


    public function statistiquesPrestations()
    {
        session()->put('statSession', 'TypePrestation');

        $statSession = session('statSession');

        $statistiques = DB::table('course as c')
            ->leftJoin('type_prestation as tp', "c.idprestation", "=", "tp.idprestation")
            ->selectRaw(
                'EXTRACT(MONTH FROM datecourse) as mois,
        EXTRACT(YEAR FROM datecourse) as annee,
        SUM(CASE WHEN c.idprestation = 1 THEN 1 ELSE 0 END) as UberX,
        SUM(CASE WHEN c.idprestation = 2 THEN 1 ELSE 0 END) as UberXL,
        SUM(CASE WHEN c.idprestation = 3 THEN 1 ELSE 0 END) as UberVan,
        SUM(CASE WHEN c.idprestation = 4 THEN 1 ELSE 0 END) as Comfort,
        SUM(CASE WHEN c.idprestation = 5 THEN 1 ELSE 0 END) as Green,
        SUM(CASE WHEN c.idprestation = 6 THEN 1 ELSE 0 END) as UberPet,
        SUM(CASE WHEN c.idprestation = 7 THEN 1 ELSE 0 END) as Berlin'
            )
            ->groupByRaw('EXTRACT(MONTH FROM datecourse), EXTRACT(YEAR FROM datecourse)')
            ->orderByRaw('annee, mois')
            ->where('statutcourse', 'Terminée')
            ->get();


        $labels = $statistiques->map(function ($stat) {
            return \Carbon\Carbon::createFromFormat('m-Y', $stat->mois . '-' . $stat->annee)->format('F Y');
        })->toArray();



        return view('service-course.analyseImpact', compact('labels'), ['labels' => $labels, 'statistiques' => $statistiques, 'statSession' => $statSession]);
    }



    public function statistiquesGeo()
    {
        session()->put('statSession', 'Geo');

        $statSession = session('statSession');

        $statistiques = DB::table('course as co')
            ->leftJoin('adresse as a', 'co.idadresse', '=', 'a.idadresse')
            ->leftJoin('ville as v', 'a.idville', '=', 'v.idville')
            ->selectRaw('
            EXTRACT(MONTH FROM datecourse) AS mois,
            EXTRACT(YEAR FROM datecourse) AS annee,
            v.nomville as ville,
            SUM(prixcourse) AS total_montant')
            ->groupBy('mois', 'annee', 'nomville')
            ->orderBy('annee')
            ->orderBy('mois')
            ->orderBy('ville')
            ->where('statutcourse', 'Terminée')
            ->get();

        $labels = $statistiques->map(function ($stat) {
            $date = Carbon::createFromFormat('m-Y', $stat->mois . '-' . $stat->annee);

            $stat->mois_annee = $date->format('F Y');
            return $stat;
        });

        return view('service-course.analyseImpact',  ['labels' => $labels, 'statistiques' => $statistiques, 'statSession' => $statSession]);
    }
}

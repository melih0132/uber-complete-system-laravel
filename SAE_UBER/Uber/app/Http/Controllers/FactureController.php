<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class FactureController extends Controller
{
    public function index(Request $request, $idreservation)
    {
        // ceci fonctionne mais dès qu'on l'enlève, elle ne marche plus
        
        $locale = $request->input('locale', 'fr');
        app()->setLocale($locale);

        $course = DB::table('course as c')
            ->leftJoin('coursier as cou', 'cou.idcoursier', '=', 'c.idcoursier')
            ->join('reservation as r', 'c.idreservation', '=', 'r.idreservation')
            ->join('facture_course as fa', 'c.idcourse', '=', 'fa.idcourse')
            ->join('pays as p', 'p.idpays', '=', 'fa.idpays')
            ->join('client as cl', 'r.idclient', '=', 'cl.idclient')
            ->join('adresse as a_start', 'c.idadresse', '=', 'a_start.idadresse')
            ->join('adresse as a_end', 'c.adr_idadresse', '=', 'a_end.idadresse')
            ->join('type_prestation as tp', 'c.idprestation', '=', 'tp.idprestation')
            ->select(
                'c.idcourse',
                'cou.nomuser as chauffeur',
                'c.prixcourse',
                'c.pourboire',
                'c.distance',
                'c.temps',
                'c.datecourse',
                'c.heurecourse',
                'a_start.libelleadresse as startAddress',
                'a_end.libelleadresse as endAddress',
                'tp.libelleprestation',
                'r.datereservation',
                'r.heurereservation',
                'p.pourcentagetva',
                'cl.*'
            )
            ->where('c.idreservation', $idreservation)
            ->first();

        if (!$course) {
            return redirect()->back()->withErrors(['error' => 'Reservation not found.']);
        }

        $datecourse = Carbon::parse($course->datecourse)
            ->locale('fr')
            ->isoFormat('D MMMM YYYY');

        $duree_course = Carbon::parse($course->temps)->format('H:i:s');

        $data = [
            'company_name' => "Uber",
            'idcourse' => $course->idcourse,
            'chauffeur' => $course->chauffeur,
            'startAddress' => $course->startAddress,
            'endAddress' => $course->endAddress,
            'prixcourse' => $course->prixcourse,
            'pourboire' => $course->pourboire,
            'datecourse' => $datecourse,
            'duree_course' => $duree_course,
        ];

        $pdf = PDF::loadView('facture', $data)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isUtf8Enabled', true)
            ->setOption('encoding', 'UTF-8')
            ->setOption('defaultFont', 'DejaVu Sans');

        return $pdf->stream('Facture_' . $course->idcourse . '.pdf');
    }
}

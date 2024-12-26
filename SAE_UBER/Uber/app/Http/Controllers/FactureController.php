<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf as PDF;
use Carbon\Carbon;


class FactureController extends Controller
{
    public function index(Request $request, $idreservation)
    {
        $validated = $request->validate([
            'pourboire' => 'nullable|numeric|min:0|max:80',
        ]);

        // ceci fonctionne mais dès qu'on l'enlève, elle ne marche plus
        $pourboire = $validated['pourboire'] ?? 0;

        $locale = $request->input('locale', 'fr');
        app()->setLocale($locale);

        $course = DB::table('course as c')
            ->join('coursier as cou', 'cou.idcoursier', '=', 'c.idcoursier')
            ->join('reservation as r', 'c.idreservation', '=', 'r.idreservation')
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
                'cl.*'
            )
            ->where('c.idreservation', $idreservation)
            ->first();


        $TVA = DB::table('pays')
            ->select(
                'pourcentagetva',
            )
            ->where('nompays', 'France')
            ->first();

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
            'pourboire' => $pourboire,
            'datereservation' => $course->datereservation,
            'heurereservation' => $course->heurereservation,
            'datecourse' => $course->datecourse,
            'heurecourse' => $course->heurecourse,
            'libelleprestation' => $course->libelleprestation,
            'pourcentagetva' => $TVA->pourcentagetva,
            'monnaie' => '€'
        ];

        $html = view('facture', $data)->render();


        $pdf = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);


        $pdf->WriteHTML($html);

        return response($pdf->Output("Facture_" . $idreservation . "pdf", 'I'), 200)
            ->header('Content-Type', 'application/pdf');
    }
}

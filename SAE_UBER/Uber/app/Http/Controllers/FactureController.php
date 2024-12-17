<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Course;
use App\Models\Adresse;
use App\Models\Coursier;
use App\Models\Client;
use Carbon\Carbon;

class FactureController extends Controller
{
    public function index($idReservation)
    {

        // Step 2: Retrieve the course (reservation) details from the database
        $course = DB::table('course as c')
            ->join('reservation as r', 'c.idreservation', '=', 'r.idreservation')
            ->join('adresse as a_start', 'c.idadresse', '=', 'a_start.idadresse')
            ->join('adresse as a_end', 'c.adr_idadresse', '=', 'a_end.idadresse')
            ->join('type_prestation as tp', 'c.idprestation', '=', 'tp.idprestation')
            ->select(
                'c.idcourse',
                'c.idcoursier',
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
                'r.heurereservation'
            )
            ->where('c.idreservation', $idReservation)
            ->first();

        // Step 3: Check if the reservation exists
        if (!$course) {
            return redirect()->back()->withErrors(['error' => 'Reservation not found.']);
        }

        // Step 4: Calculate total price (using items like prixcourse and pourboire)
        $items = [
            ['name' => 'Course', 'quantity' => 1, 'price' => $course->prixcourse],
            ['name' => 'Tip', 'quantity' => 1, 'price' => $course->pourboire],
        ];
        $total = collect($items)->sum(fn($item) => $item['quantity'] * $item['price']);

        // Step 5: Format date (you may want to format this as per your need)
        $date_prise_en_charge = Carbon::parse($course->datecourse)
            ->locale('fr')
            ->isoFormat('D MMMM YYYY');

        // Step 6: Calculate the duration
        $duree = \Carbon\Carbon::parse($course->temps);
        $duree_course = $duree->format('H') . ' heure(s) et ' . $duree->format('i') . ' minute(s)';

        // Step 7: Prepare data for the PDF view
        $data = [
            'company_name' => "Uber", // Assuming the company name is Uber
            'id_course' => $course->idcourse,
            'id_chauffeur' => $course->idchauffeur, // Assuming this is part of your course
            'items' => $items,
            'total' => $total,
            'client' => $course->client, // Assuming 'client' field exists on 'course'
            'chauffeur' => $course->chauffeur, // Assuming 'chauffeur' field exists on 'course'
            'lieu_depart' => $course->startAddress,
            'lieu_arrivee' => $course->endAddress,
            'pourboire' => $course->pourboire,
            'date_prise_en_charge' => $date_prise_en_charge,
            'duree_course' => $duree_course,
        ];


        // Step 8: Generate the PDF invoice
        $pdf = PDF::loadView('facture', $data);

        // Step 9: Return the PDF as a downloadable file
        $file = 'Facture_id_course_' . $course->idcourse . '.pdf';
        return $pdf->download($file);
    }
}

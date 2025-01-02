<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coursier;
use App\Models\Course;
use App\Models\Entretien;
use App\Models\Vehicule;
use Mpdf\Mpdf as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FacturationController extends Controller
{
    public function index(Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession || $userSession['role'] !== 'facturation') {
            abort(403, 'Accès non autorisé - vous devez être un membre du service Facturation');
        }

        $coursiers = $this->getEligibleCoursiers();

        return view('facturation.index', [
            'coursiers' => $coursiers,
            'trips' => [],
            'totalAmount' => 0,
            'idcoursier' => null,
            'start_date' => null,
            'end_date' => null,
        ]);
    }

    public function searchCoursiers(Request $request)
    {
        $search = $request->query('query', '');

        $coursiers = $this->getEligibleCoursiers()->filter(function ($coursier) use ($search) {
            return str_contains(strtolower($coursier->nomuser), strtolower($search)) ||
                str_contains(strtolower($coursier->prenomuser), strtolower($search)) ||
                str_contains((string) $coursier->idcoursier, $search);
        });

        return response()->json($coursiers->values());
    }

    public function filterTrips(Request $request)
    {
        $this->authorizeAccess($request);

        $validated = $request->validate([
            'idcoursier' => 'required|numeric|exists:coursier,idcoursier',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $idcoursier = $validated['idcoursier'];
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        if (!$this->isCoursierEligible($idcoursier)) {
            return redirect()->route('facturation.index')->withErrors([
                'idcoursier' => 'Ce coursier n’est pas éligible pour la facturation.',
            ]);
        }

        $trips = $this->getTrips($idcoursier, $startDate, $endDate);
        $totalAmount = $this->calculateTotalAmount($idcoursier, $startDate, $endDate);

        return view('facturation.index', [
            'coursiers' => $this->getEligibleCoursiers(),
            'trips' => $trips,
            'totalAmount' => $totalAmount,
            'idcoursier' => $idcoursier,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function generateInvoice(Request $request)
    {
        $this->authorizeAccess($request);

        $validated = $request->validate([
            'idcoursier' => 'required|numeric|exists:coursier,idcoursier',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $idcoursier = $validated['idcoursier'];
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        $coursier = Coursier::findOrFail($idcoursier);
        $trips = $this->getTrips($idcoursier, $startDate, $endDate);

        $totalGrossAmount = $this->calculateTotalAmount($idcoursier, $startDate, $endDate);

        $uberFees = $totalGrossAmount * 0.20;
        $totalNetAmount = $totalGrossAmount - $uberFees;

        $html = view('facturation.reglement', [
            'coursier' => $coursier,
            'trips' => $trips,
            'totalGrossAmount' => $totalGrossAmount,
            'uberFees' => $uberFees,
            'totalNetAmount' => $totalNetAmount,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ])->render();

        $pdf = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);
        $pdf->WriteHTML($html);

        return response($pdf->Output("Reglement_salaire_{$coursier->nomuser}_{$coursier->prenomuser}_{$startDate}_{$endDate}.pdf", 'I'), 200)
            ->header('Content-Type', 'application/pdf');
    }

    // je l'ai mis là AMIR BEKHOCUJEMNZNQC
    public function generateInvoiceCourse(Request $request, $idreservation)
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

        $html = view('facturation.facture', $data)->render();

        $pdf = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);

        $pdf->WriteHTML($html);

        return response($pdf->Output("Facture_" . $idreservation . "pdf", 'I'), 200)
            ->header('Content-Type', 'application/pdf');
    }

    private function authorizeAccess(Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession || $userSession['role'] !== 'facturation') {
            abort(403, 'Accès non autorisé - vous devez être un membre du service Facturation');
        }
    }

    private function getEligibleCoursiers()
    {
        return Coursier::whereHas('entretien', function ($query) {
            $query->where('resultat', 'Retenu')
                ->whereNotNull('rdvlogistiquedate')
                ->whereNotNull('rdvlogistiquelieu');
        })
            ->whereHas('vehicules', function ($query) {
                $query->where('statusprocessuslogistique', 'Validé');
            })
            ->select('idcoursier', 'nomuser', 'prenomuser')
            ->get();
    }

    private function isCoursierEligible($coursierId)
    {
        return Entretien::where('idcoursier', $coursierId)
            ->where('resultat', 'Retenu')
            ->whereNotNull('rdvlogistiquedate')
            ->whereNotNull('rdvlogistiquelieu')
            ->exists()
            &&
            Vehicule::where('idcoursier', $coursierId)
            ->where('statusprocessuslogistique', 'Validé')
            ->exists();
    }

    private function getTrips($idcoursier, $startDate, $endDate)
    {
        return Course::where('idcoursier', $idcoursier)
            ->whereBetween('datecourse', [$startDate, $endDate])
            ->whereIn('statutcourse', ['Terminée', 'Annulée'])
            ->select('idcourse', 'datecourse', 'prixcourse', 'pourboire', 'distance', 'temps', 'statutcourse')
            ->get();
    }

    private function calculateTotalAmount($idcoursier, $startDate, $endDate)
    {
        return Course::where('idcoursier', $idcoursier)
            ->whereBetween('datecourse', [$startDate, $endDate])
            ->whereIn('statutcourse', ['Terminée', 'Annulée'])
            ->sum(DB::raw('prixcourse + COALESCE(pourboire, 0)'));
    }
}

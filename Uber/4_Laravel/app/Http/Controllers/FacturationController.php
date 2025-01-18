<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Coursier;
use App\Models\Course;
use App\Models\Entretien;
use App\Models\Vehicule;

use App\Models\Facture;
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
        $totalGrossAmountTips = $this->calculateTotalAmountTips($idcoursier, $startDate, $endDate);

        $uberFees = $totalGrossAmount * 0.20;
        $totalNetAmount = $totalGrossAmount - $uberFees;

        $html = view('facturation.reglement', [
            'coursier' => $coursier,
            'trips' => $trips,
            'totalGrossAmount' => $totalGrossAmount,
            'uberFees' => $uberFees,
            'totalNetAmount' => $totalNetAmount,
            'totalGrossAmountTips' => $totalGrossAmountTips,
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

    public function generateInvoiceCourse(Request $request, $idreservation)
    {
        $validated = $request->validate([
            'pourboire' => 'nullable|numeric|min:0|max:80',
            'notecourse' => 'nullable|numeric|min:0|max:5',
        ]);

        $pourboire = $validated['pourboire'] ?? 0;
        $note = $validated['notecourse'] ?? null;

        $locale = $request->input('locale', 'fr');
        app()->setLocale($locale);

        $course = Course::with(['coursier', 'reservation.client', 'startAddress', 'endAddress', 'prestations'])
            ->where('idreservation', $idreservation)
            ->first();

        if (!$course) {
            abort(404, 'Course not found');
        }

        $dateCourse = Carbon::parse($course->datecourse)->locale('fr')->isoFormat('D MMMM YYYY');
        $dureeCourse = gmdate('H:i:s', $course->temps ?? 0);

        $tvaRate = DB::table('pays')->where('nompays', 'France')->value('pourcentagetva') ?? 20;

        $data = [
            'idclient' => $course->reservation->client->idclient,
            'idcoursier' => $course->coursier->idcoursier,
            'company_name' => 'Uber',
            'idcourse' => $course->idcourse,
            'chauffeur' => $course->coursier->nomuser,
            'startAddress' => $course->startAddress->libelleadresse ?? '',
            'endAddress' => $course->endAddress->libelleadresse ?? '',
            'prixcourse' => $course->prixcourse,
            'datecourse' => $dateCourse,
            'duree_course' => $dureeCourse,
            'pourboire' => $pourboire,
            'datereservation' => $course->reservation->datereservation,
            'heurereservation' => $course->reservation->heurereservation,
            'heurecourse' => $course->heurecourse,
            'libelleprestation' => $course->prestations->libelleprestation ?? '',
            'pourcentagetva' => $tvaRate,
            'monnaie' => '€',
        ];

        if ($note !== null) {
            $newAverage = ($course->coursier->notemoyenne
                ? ($note + $course->coursier->notemoyenne) / 2
                : $note);

            $course->coursier->update(['notemoyenne' => $newAverage]);
            $course->update(['notecourse' => $note]);
        }

        if ($pourboire > 0) {
            $course->update(['pourboire' => $pourboire]);
        }

        Facture::updateOrCreate([
            'idreservation' => $idreservation,
        ], [
            'idpays' => 1,
            'idclient' => $data['idclient'],
            'datefacture' => Carbon::now('Europe/Paris'),
            'montantreglement' => $data['prixcourse'] * (1 + $tvaRate / 100) + $pourboire,
        ]);

        $html = view('facturation.facture', $data)->render();
        $pdf = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);
        $pdf->WriteHTML($html);

        return response($pdf->Output("Facture_{$idreservation}.pdf", 'I'), 200)
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
            ->sum(DB::raw('prixcourse'));
    }

    private function calculateTotalAmountTips($idcoursier, $startDate, $endDate)
    {
        return Course::where('idcoursier', $idcoursier)
            ->whereBetween('datecourse', [$startDate, $endDate])
            ->whereIn('statutcourse', ['Terminée', 'Annulée'])
            ->sum(DB::raw('COALESCE(pourboire, 0)'));
    }
}

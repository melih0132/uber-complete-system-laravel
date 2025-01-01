<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coursier;
use App\Models\Course;
use App\Models\Entretien;
use App\Models\Vehicule;
use Mpdf\Mpdf;
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
        $totalAmount = $this->calculateTotalAmount($idcoursier, $startDate, $endDate);

        $html = view('facturation.invoice', [
            'coursier' => $coursier,
            'trips' => $trips,
            'totalAmount' => $totalAmount,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ])->render();

        $pdf = new Mpdf();
        $pdf->WriteHTML($html);

        return $pdf->Output("facture_coursier_{$coursier->nomuser}_{$coursier->prenomuser}_{$startDate}_{$endDate}.pdf", 'D');
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

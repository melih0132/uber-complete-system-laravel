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

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être un membre du service Facturation');
        }

        if ($userSession['role'] !== 'facturation') {
            abort(403, 'Accès non autorisé');
        }

        $coursiers = Coursier::whereHas('entretien', function ($query) {
            $query->where('resultat', 'Retenu')
                ->whereNotNull('rdvlogistiquedate')
                ->whereNotNull('rdvlogistiquelieu');
        })
            ->whereHas('vehicules', function ($query) {
                $query->where('statusprocessuslogistique', 'Validé');
            })
            ->select('idcoursier', 'nomuser', 'prenomuser')
            ->get();

        return view('facturation.index', [
            'coursiers' => $coursiers,
            'trips' => [],
            'totalAmount' => 0,
            'idcoursier' => null,
            'start_date' => null,
            'end_date' => null,
        ]);
    }

    public function filterTrips(Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être un membre du service Facturation');
        }

        if ($userSession['role'] !== 'facturation') {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'idcoursier' => 'required|numeric|exists:coursier,idcoursier',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $idcoursier = $validated['idcoursier'];

        if (!$this->isCoursierEligible($idcoursier)) {
            return redirect()->route('facturation.index')->withErrors([
                'idcoursier' => 'Ce coursier n’est pas éligible pour la facturation.',
            ]);
        }

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        $trips = Course::where('idcoursier', $idcoursier)
            ->whereBetween('datecourse', [$startDate, $endDate])
            ->whereIn('statutcourse', ['Terminée', 'Annulée'])
            ->select('idcourse', 'datecourse', 'prixcourse', 'pourboire', 'distance', 'temps', 'statutcourse')
            ->get();

        $totalAmount = Course::where('idcoursier', $idcoursier)
            ->whereBetween('datecourse', [$startDate, $endDate])
            ->whereIn('statutcourse', ['Terminée', 'Annulée'])
            ->sum(DB::raw('prixcourse + COALESCE(pourboire, 0)'));

        $coursiers = Coursier::select('idcoursier', 'nomuser', 'prenomuser')->get();

        return view('facturation.index', [
            'coursiers' => $coursiers,
            'trips' => $trips,
            'totalAmount' => $totalAmount,
            'idcoursier' => $idcoursier,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function generateInvoice(Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être un membre du service Facturation');
        }

        if ($userSession['role'] !== 'facturation') {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'idcoursier' => 'required|numeric|exists:coursier,idcoursier',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $idcoursier = $validated['idcoursier'];
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        $coursier = Coursier::find($idcoursier);

        $trips = Course::where('idcoursier', $idcoursier)
            ->whereBetween('datecourse', [$startDate, $endDate])
            ->whereIn('statutcourse', ['Terminée', 'Annulée'])
            ->select('idcourse', 'datecourse', 'prixcourse', 'pourboire', 'distance', 'temps', 'statutcourse')
            ->get();

        $totalAmount = Course::where('idcoursier', $idcoursier)
            ->whereBetween('datecourse', [$startDate, $endDate])
            ->whereIn('statutcourse', ['Terminée', 'Annulée'])
            ->sum(DB::raw('prixcourse + COALESCE(pourboire, 0)'));

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

        return $entretienValid && $vehiculeValid;
    }
}

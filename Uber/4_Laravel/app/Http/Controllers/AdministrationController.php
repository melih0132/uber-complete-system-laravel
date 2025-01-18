<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;

use App\Models\Coursier;

class AdministrationController extends Controller
{
    public function index()
    {
        $coursiers = Coursier::all()->filter(function ($coursier) {
            return $this->isCoursierEligible($coursier->idcoursier) && empty($coursier->iban);
        });

        return view('admin.index', compact('coursiers'));
    }

    public function searchCoursiers(Request $request)
    {
        $search = $request->query('query', '');

        $coursiers = Coursier::where('nomuser', 'LIKE', "%{$search}%")
            ->orWhere('prenomuser', 'LIKE', "%{$search}%")
            ->orWhere('idcoursier', 'LIKE', "%{$search}%")
            ->get();

        return response()->json($coursiers);
    }

    public function demanderIban(Request $request, $idcoursier)
    {
        $coursier = Coursier::find($idcoursier);

        if (!$coursier) {
            return redirect()->back()->with('error', 'Coursier introuvable.');
        }

        if (!$this->isCoursierEligible($coursier->idcoursier)) {
            return redirect()->back()->with('error', 'Ce coursier n’est pas éligible pour l’ajout d’un IBAN.');
        }

        // TODO : Ajouter une colonne dans coursier : demande_iban

        return redirect()->back()->with('success', 'Demande d’IBAN enregistrée.');
    }

    public function supprimerCoursier($idcoursier)
    {
        $coursier = Coursier::find($idcoursier);

        if (!$coursier) {
            return redirect()->back()->with('error', 'Coursier introuvable.');
        }

        if (!empty($coursier->iban)) {
            return redirect()->back()->with('error', 'Impossible de supprimer un coursier ayant déjà fourni son IBAN.');
        }

        $coursier->delete();

        return redirect()->back()->with('success', 'Le coursier a été supprimé de la base.');
    }

    private function isCoursierEligible($coursierId)
    {
        $coursier = Coursier::with(['entretien', 'vehicules'])->find($coursierId);

        if (!$coursier) {
            return false;
        }

        $entretienValid = $coursier->entretien &&
            $coursier->entretien->resultat === 'Retenu' &&
            $coursier->entretien->rdvlogistiquedate &&
            $coursier->entretien->rdvlogistiquelieu;

        $vehiculeValid = $coursier->vehicules->contains(function ($vehicule) {
            return $vehicule->statusprocessuslogistique === 'Validé';
        });

        return $entretienValid && $vehiculeValid;
    }
}

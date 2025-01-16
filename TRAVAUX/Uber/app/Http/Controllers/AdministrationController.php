<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Models\Coursier;

class AdministrationController extends Controller
{
    public function index()
    {
        $coursiers = Coursier::all();

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

    public function initierValidation(Request $request)
    {
        $request->validate([
            'idcoursier' => 'required|integer|exists:coursiers,idcoursier',
            'iban' => 'required|string|size:30|unique:coursiers,iban',
            'datedebutactivite' => 'required|date',
        ]);

        $coursier = Coursier::find($request->idcoursier);

        $coursier->iban = $request->iban;
        $coursier->datedebutactivite = $request->datedebutactivite;
        $coursier->save();

        return response()->json(['message' => 'Validation administrative initiée avec succès.']);
    }

    public function relancerCoursier($idcoursier)
    {
        $coursier = Coursier::find($idcoursier);

        if (!$coursier) {
            return response()->json(['error' => 'Coursier introuvable.'], 404);
        }

        if ($coursier->last_relance && Carbon::parse($coursier->last_relance)->diffInDays(now()) < 15) {
            return response()->json(['error' => 'Relance déjà effectuée récemment.'], 400);
        }

        $coursier->last_relance = now();
        $coursier->save();

        // Simulation de notification par email ou SMS ici

        return response()->json(['message' => 'Coursier relancé avec succès.']);
    }

    public function supprimerCoursier($idcoursier)
    {
        $coursier = Coursier::find($idcoursier);

        if (!$coursier) {
            return response()->json(['error' => 'Coursier introuvable.'], 404);
        }

        if (!$coursier->created_at || Carbon::parse($coursier->created_at)->diffInDays(now()) < 30) {
            return response()->json(['error' => 'Le délai de suppression n’est pas encore atteint.'], 400);
        }

        $coursier->delete();

        return response()->json(['message' => 'Coursier supprimé après un mois sans réponse.']);
    }
}

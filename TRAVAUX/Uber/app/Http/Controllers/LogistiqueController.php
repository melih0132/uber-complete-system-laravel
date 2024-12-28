<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entretien;
use App\Models\Coursier;
use App\Models\Vehicule;

class LogistiqueController extends Controller
{
    public function index()
    {
        $coursiers = Coursier::with(['entretien', 'vehicule'])
            ->whereHas('entretien', function ($query) {
                $query->where('resultat', 'Retenu');
            })
            ->get();

        return view('logistique.vehicules.index', compact('coursiers'));
    }

    private function getVehiculeEligible($coursierId, $status)
    {
        $coursier = Coursier::with(['vehicule', 'entretien'])
            ->whereHas('entretien', function ($query) {
                $query->where('resultat', 'Retenu');
            })
            ->findOrFail($coursierId);

        $vehicule = $coursier->vehicule;

        if (!$vehicule || $vehicule->statusprocessuslogistique !== $status) {
            throw new \Exception("Aucun véhicule avec le statut '{$status}' trouvé pour ce coursier.");
        }

        return $vehicule;
    }

    public function valider($id)
    {
        try {
            $vehicule = $this->getVehiculeEligible($id, 'En attente');
            $vehicule->statusprocessuslogistique = 'Validé';
            $vehicule->save();

            return redirect()->route('logistique.vehicules')
                ->with('success', 'Véhicule validé avec succès pour le coursier.');
        } catch (\Exception $e) {
            return redirect()->route('logistique.vehicules')
                ->with('error', $e->getMessage());
        }
    }

    public function refuser($id)
    {
        try {
            $vehicule = $this->getVehiculeEligible($id, 'En attente');
            $vehicule->statusprocessuslogistique = 'Refusé';
            $vehicule->save();

            return redirect()->route('logistique.vehicules')
                ->with('success', 'Véhicule refusé avec succès pour le coursier.');
        } catch (\Exception $e) {
            return redirect()->route('logistique.vehicules')
                ->with('error', $e->getMessage());
        }
    }

    public function demanderModification(Request $request, $id)
    {
        try {
            $vehicule = $this->getVehiculeEligible($id, 'En attente');

            $validated = $request->validate([
                'modifications_demandees' => 'required|string|max:255',
            ]);

            $vehicule->statusprocessuslogistique = 'Modifications demandées';
            $vehicule->save();

            $modifications = session('modifications', []);
            $modifications[] = [
                'idcoursier' => $vehicule->idcoursier,
                'idvehicule' => $vehicule->idvehicule,
                'coursier' => $vehicule->coursier->nomuser ?? 'Non spécifié',
                'modele' => $vehicule->modele ?? 'Non spécifié',
                'demande' => $validated['modifications_demandees'],
                'date' => now()->format('Y-m-d H:i:s'),
            ];

            session(['modifications' => $modifications]);

            return redirect()->route('logistique.vehicules')
                ->with('success', 'Demande de modification enregistrée avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('logistique.vehicules')
                ->with('error', $e->getMessage());
        }
    }

    public function supprimerModification($index)
    {
        $modifications = session('modifications', []);

        if (!isset($modifications[$index])) {
            return redirect()->back()
                ->with('error', 'Modification introuvable.');
        }

        $modification = $modifications[$index];
        $vehiculeId = $modification['idvehicule'] ?? null;

        unset($modifications[$index]);

        $modifications = array_values($modifications);

        session(['modifications' => $modifications]);

        if ($vehiculeId) {
            $vehicule = Vehicule::find($vehiculeId);
            if ($vehicule && $vehicule->statusprocessuslogistique === 'Modifications demandées') {
                $vehicule->statusprocessuslogistique = 'En attente';
                $vehicule->save();
            }
        }

        return redirect()->back()
            ->with('success', 'La modification demandée a été supprimée et le statut du véhicule est revenu à "En attente".');
    }


    public function showModifierForm($id)
    {
        $coursier = Coursier::with('vehicule')
            ->whereHas('entretien', function ($query) {
                $query->where('resultat', 'Retenu');
            })
            ->findOrFail($id);

        return view('logistique.vehicules.modifier', compact('coursier'));
    }

    public function afficherModifications()
    {
        $modifications = session('modifications', []);

        return view('logistique.modifications', compact('modifications'));
    }

    public function afficherDemandesParCoursier($id)
    {
        $modifications = collect(session('modifications', []))
            ->where('idcoursier', $id);

        $coursier = Coursier::findOrFail($id);

        return view('conducteurs.demandes', compact('modifications', 'coursier'));
    }
}

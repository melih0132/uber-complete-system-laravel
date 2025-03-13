<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coursier;
use App\Models\Vehicule;

use Illuminate\Support\Facades\DB;

class LogistiqueController extends Controller
{
    public function index(Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être un membre du service logistique');
        }

        if ($userSession['role'] !== 'logistique') {
            abort(403, 'Accès non autorisé');
        }

        $search = $request->query('search');

        $coursiers = Coursier::with(['vehicules' => function ($query) {
            $query->with('prestations')->whereIn('statusprocessuslogistique', ['En attente', 'Modifications demandées']);
        }])
            ->whereHas('vehicules', function ($query) {
                $query->whereIn('statusprocessuslogistique', ['En attente', 'Modifications demandées']);
            })
            ->whereHas('entretien', function ($query) {
                $query->where('resultat', 'Retenu');
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nomuser', 'like', "%{$search}%")
                        ->orWhere('prenomuser', 'like', "%{$search}%")
                        ->orWhere('idcoursier', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('logistique.vehicules.index', compact('coursiers'));
    }

    public function selectCoursier(Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être un membre du service logistique');
        }

        if ($userSession['role'] !== 'logistique') {
            abort(403, 'Accès non autorisé');
        }

        $search = $request->query('search');

        $coursiers = Coursier::whereHas('entretien', function ($query) {
            $query->where('resultat', 'Retenu');
        })
            ->when($search, function ($query, $search) {
                $query->where('nomuser', 'like', "%{$search}%")
                    ->orWhere('prenomuser', 'like', "%{$search}%")
                    ->orWhere('idcoursier', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('logistique.coursiers.select', compact('coursiers'));
    }

    public function showAddVehiculeForm(Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être un membre du service logistique');
        }

        if ($userSession['role'] !== 'logistique') {
            abort(403, 'Accès non autorisé');
        }

        $coursierId = $request->query('coursier');

        try {
            $coursier = Coursier::findOrFail($coursierId);
            return view('logistique.vehicules.create', compact('coursier'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('logistique.coursiers.select')
                ->with('error', 'Coursier introuvable. Veuillez sélectionner un coursier valide.');
        }
    }

    public function storeVehicule(Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être un membre du service logistique');
        }

        if ($userSession['role'] !== 'logistique') {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'idcoursier' => 'required|exists:coursier,idcoursier',
            'immatriculation' => 'required|regex:/^[A-Z]{2}-[0-9]{3}-[A-Z]{2}$/|unique:vehicule,immatriculation',
            'marque' => 'required|string|max:50',
            'modele' => 'required|string|max:50',
            'capacite' => 'required|integer|between:2,7',
            'accepteanimaux' => 'required|boolean',
            'estelectrique' => 'required|boolean',
            'estconfortable' => 'required|boolean',
            'estrecent' => 'required|boolean',
            'estluxueux' => 'required|boolean',
            'couleur' => 'required|string|max:20',
        ]);

        $vehicule = Vehicule::create([
            'idcoursier' => $validated['idcoursier'],
            'immatriculation' => $validated['immatriculation'],
            'marque' => $validated['marque'],
            'modele' => $validated['modele'],
            'capacite' => $validated['capacite'],
            'accepteanimaux' => $validated['accepteanimaux'],
            'estelectrique' => $validated['estelectrique'],
            'estconfortable' => $validated['estconfortable'],
            'estrecent' => $validated['estrecent'],
            'estluxueux' => $validated['estluxueux'],
            'couleur' => $validated['couleur'],
            'statusprocessuslogistique' => 'En attente',
        ]);

        $prestations = [1];

        if ($vehicule->estelectrique) {
            $prestations[] = 5;
        }
        if ($vehicule->estconfortable) {
            $prestations[] = 4;
        }
        if ($vehicule->estluxueux) {
            $prestations[] = 7;
        }
        if ($vehicule->capacite <= 6) {
            $prestations[] = 2;
        }
        if ($vehicule->capacite > 6) {
            $prestations[] = 3;
        }
        if ($vehicule->accepteanimaux) {
            $prestations[] = 6;
        }

        $data = array_map(fn($idPrestation) => [
            'idvehicule' => $vehicule->idvehicule,
            'idprestation' => $idPrestation,
        ], $prestations);

        DB::table('a_comme_type')->insert($data);

        return redirect()->route('logistique.vehicules')
            ->with('success', 'Véhicule enregistré avec ses prestations attribuées avec succès.');
    }

    public function valider($id)
    {
        try {
            $vehicule = Vehicule::findOrFail($id);
            if ($vehicule->statusprocessuslogistique !== 'En attente') {
                throw new \Exception('Le véhicule n\'est pas en attente.');
            }
            $vehicule->statusprocessuslogistique = 'Validé';
            $vehicule->demandemodification = null;
            $vehicule->save();

            return redirect()->route('logistique.vehicules')
                ->with('success', 'Véhicule validé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('logistique.vehicules')
                ->with('error', $e->getMessage());
        }
    }

    public function refuser($id)
    {
        try {
            $vehicule = Vehicule::findOrFail($id);

            if ($vehicule->statusprocessuslogistique !== 'En attente') {
                throw new \Exception('Le véhicule n\'est pas en attente.');
            }

            DB::transaction(function () use ($vehicule) {
                DB::table('a_comme_type')->where('idvehicule', $vehicule->idvehicule)->delete();
                $vehicule->delete();
            });

            return redirect()->route('logistique.vehicules')
                ->with('success', 'Véhicule refusé et supprimé avec succès pour le coursier.');
        } catch (\Exception $e) {
            return redirect()->route('logistique.vehicules')
                ->with('error', $e->getMessage());
        }
    }

    public function showModifierForm($id)
    {
        try {
            $vehicule = Vehicule::with('coursier')->findOrFail($id);

            $coursier = $vehicule->coursier;

            if (!$coursier) {
                throw new \Exception('Aucun coursier n\'est associé à ce véhicule.');
            }

            return view('logistique.vehicules.modifier', compact('vehicule', 'coursier'));
        } catch (\Exception $e) {
            return redirect()->route('logistique.vehicules')
                ->with('error', $e->getMessage());
        }
    }

    public function demanderModification(Request $request, $id)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être un membre du service logistique');
        }

        if ($userSession['role'] !== 'logistique') {
            abort(403, 'Accès non autorisé');
        }

        try {
            $validated = $request->validate([
                'demandemodification' => 'required|string|max:255',
            ]);

            $coursier = Coursier::with('vehicules')->findOrFail($id);

            $vehicule = $coursier->vehicules->firstWhere('statusprocessuslogistique', 'En attente');

            if (!$vehicule) {
                throw new \Exception('Aucun véhicule éligible trouvé pour demander une modification.');
            }

            $vehicule->statusprocessuslogistique = 'Modifications demandées';
            $vehicule->demandemodificationeffectue = false;
            $vehicule->demandemodification = $validated['demandemodification'];
            $vehicule->save();

            return redirect()->route('logistique.vehicules')
                ->with('success', 'Demande de modification enregistrée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('logistique.vehicules')
                ->with('error', $e->getMessage());
        }
    }

    public function afficherModifications(Request $request)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être un membre du service logistique');
        }

        if ($userSession['role'] !== 'logistique') {
            abort(403, 'Accès non autorisé');
        }

        $vehicules = Vehicule::where('statusprocessuslogistique', 'Modifications demandées')
            ->with('coursier')
            ->orderByDesc('demandemodificationeffectue')
            ->get();

        return view('logistique.modifications', compact('vehicules'));
    }

    public function supprimerModification(Request $request, $id)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être un membre du service logistique');
        }

        if ($userSession['role'] !== 'logistique') {
            abort(403, 'Accès non autorisé');
        }

        try {
            $vehicule = Vehicule::findOrFail($id);

            if ($vehicule->statusprocessuslogistique === 'Modifications demandées') {
                $vehicule->statusprocessuslogistique = 'En attente';
                $vehicule->demandemodification = null;
                $vehicule->save();
            }

            return redirect()->route('logistique.modifications')
                ->with('success', 'La demande de modification a été supprimée et le statut du véhicule est revenu à "En attente".');
        } catch (\Exception $e) {
            return redirect()->route('logistique.modifications')
                ->with('error', $e->getMessage());
        }
    }

    public function afficherDemandesParCoursier($id)
    {
        $coursier = Coursier::with(['vehicules' => function ($query) {
            $query->where('statusprocessuslogistique', 'Modifications demandées');
        }])->findOrFail($id);

        $vehicules = $coursier->vehicules ?? collect();

        return view('conducteurs.demandes', compact('vehicules', 'coursier'));
    }

    public function markModificationAsCompleted(Request $request, $id)
    {
        $userSession = $request->session()->get('user');

        if (!$userSession) {
            abort(403, 'Accès non autorisé - vous devez être le coursier concerné');
        }

        if ($userSession['role'] !== 'coursier') {
            abort(403, 'Accès non autorisé');
        }

        try {
            $vehicule = Vehicule::findOrFail($id);

            if ($vehicule->statusprocessuslogistique !== 'Modifications demandées') {
                throw new \Exception('La demande de modification n\'est pas en cours ou est déjà terminée.');
            }

            $vehicule->demandemodificationeffectue = true;
            $vehicule->save();

            return redirect()->route('conducteurs.demandes', $vehicule->idcoursier)
                ->with('success', 'La demande a été marquée comme effectuée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('conducteurs.demandes', $id)
                ->with('error', $e->getMessage());
        }
    }
}

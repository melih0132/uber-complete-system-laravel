<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Coursier;
use App\Models\Entretien;
use App\Models\Vehicule;

use App\Models\Course;
use App\Models\Reservation;

class CoursierController extends Controller
{
    public function index(Request $request)
    {
        //AMIR
        $user = session('user');

        if (!$user) {
            return redirect()->route('login-coursier')->with('error', 'Vous devez être connecté pour accéder à cette section.');
        }

        if (!$this->isCoursierEligible($user['id'])) {
            return redirect()->route('myaccount')->with('error', 'Accès refusé : vous n\'êtes pas éligible.');
        }

        $coursier = Coursier::with('adresse.ville')->find($user['id']);
        if (!$coursier || !$coursier->adresse || !$coursier->adresse->ville) {
            return redirect()->route('myaccount')->with('error', 'Ville introuvable pour le coursier.');
        }

        $villeCoursier = $coursier->adresse->ville->nomville;

        $tasks = Course::with(['reservation.client', 'startAddress.ville', 'endAddress.ville'])
            ->where('statutcourse', 'En attente')
            ->whereHas('startAddress.ville', function ($query) use ($villeCoursier) {
                $query->where('nomville', $villeCoursier);
            })
            ->orderBy('idreservation')
            ->get();

        return view('conducteurs.course-en-attente', [
            'layout' => 'layouts.app',
            'tasks' => $tasks,
            'type' => 'courses',
        ]);
    }

    public function acceptTask(Request $request, $idreservation)
    {
        $user = session('user');

        if (!$user || $user['role'] !== 'coursier') {
            return redirect()->route('login-coursier')->with('error', 'Vous devez être connecté en tant que coursier pour accéder à cette section.');
        }

        try {
            DB::transaction(function () use ($idreservation, $user) {
                $course = Course::where('idreservation', $idreservation)
                    ->whereNull('idcoursier')
                    ->lockForUpdate()
                    ->first();

                if (!$course) {
                    throw new \Exception('Course introuvable ou déjà assignée.');
                }

                $course->update([
                    'idcoursier' => $user['id'],
                    'statutcourse' => 'En cours',
                ]);
            });

            return redirect()->route('coursier.courses.index')->with('success', 'Course acceptée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('coursier.courses.index')->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function cancelTask(Request $request, $idreservation)
    {
        $user = session('user');

        if (!$user || $user['role'] !== 'coursier') {
            return redirect()->route('login-coursier')->withErrors(['message' => 'Accès refusé.']);
        }

        try {
            DB::transaction(function () use ($idreservation, $user) {
                $course = Course::where('idreservation', $idreservation)
                    ->where('idcoursier', $user['id'])
                    ->where('statutcourse', 'En cours')
                    ->lockForUpdate()
                    ->first();

                if (!$course) {
                    throw new \Exception('Course introuvable ou déjà annulée.');
                }

                $course->update([
                    'statutcourse' => 'En attente',
                    'idcoursier' => null,
                ]);
            });

            return redirect()->route('coursier.courses.index')->with('success', 'Course annulée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('coursier.courses.index')->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function finishTask(Request $request, $idreservation)
    {
        $user = session('user');

        if (!$user || $user['role'] !== 'coursier') {
            return redirect()->route('login-coursier')->withErrors(['message' => 'Accès refusé.']);
        }

        try {
            DB::transaction(function () use ($idreservation, $user) {
                $course = Course::where('idreservation', $idreservation)
                    ->where('idcoursier', $user['id'])
                    ->where('statutcourse', 'En cours')
                    ->lockForUpdate()
                    ->first();

                if (!$course) {
                    throw new \Exception('Course introuvable ou déjà terminée.');
                }

                $course->update(['statutcourse' => 'Terminée']);
            });

            return redirect()->route('coursier.courses.index')->with('success', 'Course marquée comme terminée.');
        } catch (\Exception $e) {
            return redirect()->route('coursier.courses.index')->withErrors(['message' => $e->getMessage()]);
        }
    }


































    //MELIH
    private function isCoursierEligible($coursierId)
    {
        $coursier = Coursier::with(['entretien', 'vehicules'])->find($coursierId);

        if (!$coursier) {
            return false;
        }

        if (empty($coursier->iban)) {
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

    public function entretien(Request $request)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('myaccount')->with('error', 'Accès refusé.');
        }

        if (!$user || $user['role'] !== 'coursier') {
            return redirect()->route('login-coursier')->withErrors(['message' => 'Accès refusé.']);
        }

        $entretien = Entretien::where('idcoursier', $user['id'])->first();

        if (!$entretien) {
            return redirect()->route('myaccount')->with('error', 'Aucun entretien trouvé.');
        }

        switch ($entretien->status) {
            case 'En attente':
                return view('entretien.en-attente', compact('entretien'));

            case 'Planifié':
                return view('entretien.planifie', compact('entretien'));

            case 'Terminée':
                return view('entretien.termine', compact('entretien'));

            case 'Annulée':
                return view('entretien.annule', compact('entretien'));

            default:
                return redirect()->route('myaccount')->with('error', 'Statut d\'entretien inconnu.');
        }
    }

    public function validerEntretien($entretienId)
    {
        $entretien = Entretien::findOrFail($entretienId);

        $entretien->status = 'Planifié';
        $entretien->save();

        return redirect()->route('coursier.entretien')->with('success', 'Entretien planifié avec succès.');
    }

    public function annulerEntretien($entretienId)
    {
        $entretien = Entretien::findOrFail($entretienId);

        $entretien->status = 'Annulée';
        $entretien->save();

        return redirect()->route('coursier.entretien')->with('error', 'Entretien annulé.');
    }

    public function planifie(Request $request)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('myaccount')->with('error', 'Accès refusé.');
        }

        $entretien = Entretien::where('idcoursier', $user['id'])->where('status', 'Plannifié')->first();

        if (!$entretien) {
            return redirect()->route('coursier.entretien')->with('error', 'Aucun entretien planifié trouvé.');
        }

        return view('entretien.planifie', compact('entretien'));
    }

    public function afficherIban(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'coursier') {
            return redirect()->route('login')->withErrors(['Accès refusé.']);
        }

        $coursier = Coursier::find($sessionUser['id']);

        if (!$coursier) {
            return redirect()->route('myaccount')->withErrors(['Coursier introuvable.']);
        }

        return view('conducteurs.ajouter-iban', compact('coursier'));
    }

    public function saisirIban(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'coursier') {
            return redirect()->route('login')->withErrors(['Accès refusé.']);
        }

        $coursier = Coursier::find($sessionUser['id']);

        if (!$coursier) {
            return redirect()->route('myaccount')->withErrors(['Coursier introuvable.']);
        }

        // Validation de l'IBAN
        $request->validate([
            'iban' => 'required|string|size:27|unique:coursiers,iban',
        ]);

        // Enregistrement de l'IBAN
        $coursier->iban = $request->iban;
        $coursier->save();

        return redirect()->route('coursier.iban')->with('success', 'IBAN enregistré avec succès.');
    }
}

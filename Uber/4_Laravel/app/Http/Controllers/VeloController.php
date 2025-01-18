<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Stripe\Stripe;
use Stripe\Checkout\Session  as StripeSession;

use App\Models\Client;
use App\Models\Velo;
use App\Models\Reservation;
use App\Models\Adresse;

class VeloController extends Controller
{
    public function accueilVelo(Request $request)
    {
        return view('velo.index');
    }

    public function index(Request $request)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Accès refusé.');
        }

        $startAddress = $request->input('startAddress');
        $tripDate = $request->input('tripDate', Carbon::now()->format('Y-m-d'));
        $tripTime = $request->input('tripTime', Carbon::now()->format('H:i'));

        $duration = $request->input('duration', 0);
        $durationText = $this->getDurationText($duration);
        $price = $this->calculatePrice($duration);

        try {
            $tripDateFormatted = Carbon::parse($tripDate)->format('d-m-Y');
            $tripTime = Carbon::parse($tripTime)->format('H:i');
            $jourSemaine = $this->getJourSemaine($tripDate);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Date ou heure invalide.');
        }

        $city = strtoupper(trim(explode(',', $startAddress)[0] ?? ''));

        $bicycles = Velo::with('adresse.ville')
            ->whereHas('adresse.ville', function ($query) use ($city) {
                $query->where(DB::raw('UPPER(nomville)'), $city);
            })
            ->where('estdisponible', true)
            ->get();

        return view('velo.index', [
            'bicycles' => $bicycles,
            'startAddress' => $startAddress,
            'tripDate' => $tripDateFormatted,
            'tripTime' => $tripTime,
            'jourSemaine' => $jourSemaine,
            'duration' => $duration,
            'durationText' => $durationText,
            'price' => $price,
            'city' => $city,
        ]);
    }

    public function convertDurationToHoursMinutes($duration)
    {
        $hours = floor($duration / 60);
        $minutes = $duration % 60;

        $formattedDuration = '';
        if ($hours > 0) {
            $formattedDuration .= $hours . ' heure' . ($hours > 1 ? 's' : '');
        }
        if ($minutes > 0) {
            if ($formattedDuration) {
                $formattedDuration .= ' et ';
            }
            $formattedDuration .= $minutes . ' minute' . ($minutes > 1 ? 's' : '');
        }

        return $formattedDuration ?: '0 minute';
    }


    private function getJourSemaine($date)
    {
        $jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        return $jours[Carbon::parse($date)->dayOfWeek];
    }

    public function showDetailsVelo($id)
    {
        $velo = Velo::with('adresse')->find($id);

        if (!$velo) {
            return redirect()->route('velo.index')->with('error', 'Vélo introuvable.');
        }

        return view('velo.velo-details', ['velo' => $velo]);
    }

    private function getDurationText($duration)
    {
        switch ($duration) {
            case 1:
                return '0 à 30 minutes';
            case 2:
                return '1 heure';
            case 3:
                return '1 à 3 heures';
            case 4:
                return '3 à 8 heures';
            case 5:
                return '1 journée';
            default:
                return 'Non spécifiée';
        }
    }

    private function calculatePrice($duration)
    {
        switch ($duration) {
            case 1:
                return 3;
            case 2:
                return 5;
            case 3:
                return 10;
            case 4:
                return 15;
            case 5:
                return 30;
            default:
                return 0;
        }
    }

    public function showReservationDetails($id, Request $request)
    {
        $bicycle = Velo::with('adresse')->find($id);

        if (!$bicycle) {
            return redirect()->back()->with('error', 'Vélo introuvable.');
        }

        $reservation = DB::table('velo_reservation')
            ->where('idvelo', $id)
            ->first();

        if (!$reservation) {
            return redirect()->back()->with('error', 'Aucune réservation trouvée pour ce vélo.');
        }

        $duration = $reservation->dureereservation;
        $priceReservation = $reservation->prixreservation;

        $durationText = $this->getDurationText($duration);
        $formattedDuration = $this->convertDurationToHoursMinutes($duration);

        $tripDate = $request->input('tripDate', Carbon::now()->format('Y-m-d'));
        $tripTime = $request->input('tripTime', Carbon::now()->format('H:i'));

        try {
            $tripDate = Carbon::parse($tripDate)->format('Y-m-d');
            $tripTime = Carbon::parse($tripTime)->format('H:i');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Date ou heure invalide.');
        }

        $request = session()->put('reservation', [
            'idreservation' => $reservation->idreservation_velo,
            'veloId' => $bicycle->idvelo,
            'tripDate' => $tripDate,
            'tripTime' => $tripTime,
            'price' => $reservation->prixreservation,
            'duration' => $reservation->dureereservation,
        ]);

        return view('velo.reservation-details', [
            'velos' => [
                'startAddress' => $bicycle->adresse->libelleadresse ?? 'Adresse non disponible',
                'veloId' => $bicycle->idvelo,
                'numerovelo' => $bicycle->numerovelo,
                'disponibilite' => $bicycle->estdisponible ? 'Disponible' : 'Indisponible',
            ],
            'tripDate' => $tripDate,
            'tripTime' => $tripTime,
            'duration' => $duration,
            'durationText' => $durationText,
            'formattedDuration' => $formattedDuration,
            'priceReservation' => $priceReservation,
        ]);
    }
    public function validateReservation(Request $request, $id)
    {
        $userSession = $request->session()->get('user');
        $client = Client::find($userSession['id']);
        $velo = Velo::find($id);

        if (!$velo) {
            return redirect()->back()->with('error', 'Vélo non trouvé.');
        };

        if (!$velo->estdisponible) {
            return redirect()->back()->with('error', 'Le vélo est déjà réservé.');
        };

        $tripDate = $request->input('tripDate', Carbon::now()->format('Y-m-d'));
        $tripTime = $request->input('tripTime', Carbon::now()->format('H:i'));

        try {
            $tripDate = Carbon::parse($tripDate)->format('Y-m-d');
            $tripTime = Carbon::parse($tripTime)->format('H:i');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Date ou heure invalide.');
        };

        $reservation = Reservation::create([
            'idclient' => $client->idclient,
            'idvelo' => $velo->idvelo,
            'datereservation' => $tripDate,
            'heurereservation' => $tripTime,
            'prixreservation' => $this->calculatePrice($request->input('duration', 0)),
            'dureereservation' => $request->input('duration', 0),
        ]);

        if (!$reservation) {
            return redirect()->back()->with('error', 'Erreur lors de la création de la réservation.');
        };

        return redirect()->route('velo.confirmation')->with('success', 'Réservation validée. Procédez au paiement.');
    }
    public function paiementReservation()
    {
        $reservation = session('reservation');

        $totalAmount = $reservation['price'] * 100;

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $stripeSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Réservation Vélo',
                            ],
                            'unit_amount' => $totalAmount,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('velo.confirmation', ['id' => $reservation['veloId']]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('velo.index'),
            ]);

            return redirect($stripeSession->url);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Erreur Stripe : ' . $e->getMessage()]);
        }
    }

    public function confirmation($id, Request $request)
    {
        $reservation = $request->session()->get('reservation');

        if (!$reservation) {
            return redirect()->route('velo.index')->with('error', 'Réservation introuvable.');
        }

        $velo = Velo::find($id);

        if (!$velo) {
            return redirect()->route('velo.index')->with('error', 'Vélo non trouvé.');
        }

        $velo->estdisponible = false;
        $velo->save();

        $reservationData = Reservation::find($reservation['idreservation']);
        $reservationData->update(['status' => 'Paid']);

        $request->session()->forget('reservation');

        return view('velo.confirmation', [
            'velo' => $velo,
            'tripDate' => $reservation['tripDate'],
            'tripTime' => $reservation['tripTime'],
            'price' => $reservation['price'],
            'durationText' => $this->getDurationText($reservation['duration']),
            'duration' => $reservation['duration'],
        ]);
    }

    public function choixCarte(Request $request)
    {
        $userSession = $request->session()->get('user');
        $clientId = $userSession['id'];

        $cartes = DB::table('carte_bancaire as cb')
            ->join('appartient_2 as a2', 'cb.idcb', '=', 'a2.idcb')
            ->join('client as c', 'a2.idclient', '=', 'c.idclient')
            ->where('a2.idclient', $clientId)
            ->select('cb.idcb', 'cb.numerocb', 'cb.dateexpirecb', 'cb.typecarte', 'cb.typereseaux')
            ->get();

        return view('velo.paiement', [
            'cartes' => $cartes,
        ]);
    }

    public function finaliserPaiement(Request $request)
    {
        $carte_id = $request->input('carte_id');

        if (!$carte_id) {
            return redirect()->route('velo.paiement')->with('error', 'Veuillez sélectionner une carte bancaire.');
        }
        return view('velo.fin-reservation');
    }
}

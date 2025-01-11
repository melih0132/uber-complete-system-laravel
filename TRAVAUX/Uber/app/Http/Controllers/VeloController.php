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
    {//dd($request->all());
        $user = session('user');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Accès refusé.');
        }

        $startAddress = $request->input('startAddress');
        $tripDate = $request->input('tripDate');
        $tripTime = $request->input('tripTime');
        $duration = $request->input('duration');
        $durationText = $this->getDurationText($request->input('duration', 0));
        $tripDate = Carbon::parse($tripDate)->format('Y-m-d');

        if (empty($tripTime)) {
            $tripTime = Carbon::now('Europe/Paris')->format('H:i:sP');
            $tripTime = $this->roundToPreviousHalfHour($tripTime);
        } else {
            $tripTime = Carbon::createFromFormat('H:i', $tripTime, 'Europe/Paris')->format('H:i');
        }

        $tripDateFormatted = Carbon::parse($tripDate)->format('d-m-Y');
        $jourSemaine = $this->getJourSemaine($tripDate);

        $parts = explode(',', $startAddress);
        $city = count($parts) >= 2 ? trim($parts[count($parts) - 2]) : trim($parts[0]);
        $city = strtoupper($city);

        $bicycles = DB::table('velo as v')
            ->join('adresse as ad', 'v.idadresse', '=', 'ad.idadresse')
            ->join('ville as vi', 'ad.idville', '=', 'vi.idville')
            ->join('velo_reservation as rv' , 'rv.idreservation', 'v.idvelo')
            ->where(DB::raw('UPPER(vi.nomville)'), $city)
            ->where('v.estdisponible', true)
            ->select('v.*', 'ad.libelleadresse as startAddress')
            ->get();

        return view('velo.index', [
            'bicycles' => $bicycles,
            'startAddress' => $startAddress,
            'tripDate' => $tripDateFormatted,
            'tripTime' => $tripTime,
            'jourSemaine' => $jourSemaine,
            'duration'=> $duration,
            'durationText' => $durationText,
            'city' => $city,
        ]);

    }

    private function getJourSemaine($date)
    {
        $jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        return $jours[Carbon::parse($date)->dayOfWeek];
    }

    private function roundToPreviousHalfHour($time)
    {
        $parsedTime = Carbon::createFromFormat('H:i:sP', $time);
        $parsedTime->minute($parsedTime->minute < 30 ? 0 : 30);
        $parsedTime->second(0);
        return $parsedTime->format('H:i');
    }

    private function getDurationText($duration)
    {
        switch($duration) {
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
    public function showDetailsVelo($id)
    {
        $velo = Velo::with('adresse')->find($id);

        if (!$velo) {
            return redirect()->route('velo.index')->with('error', 'Vélo introuvable.');
        }

        return view('velo.velo-details', ['velo' => $velo]);
    }


    private function calculatePrice($duration)
    {
        $pricePerHour = 10;
        return ceil($duration / 60) * $pricePerHour;
    }


    public function showReservationDetails($id, Request $request)
    {
        $bicycle = Velo::with('adresse')->find($id);
        if (!$bicycle) {
            return redirect()->back()->with('error', 'Vélo introuvable.');
        }

        $duration = $request->input('duration', 0);

        $durationLabel = $this->getDurationText($duration);

        $price = $this->calculatePrice($duration);

        return view('velo.reservation-details', [
            'velos' => [
                'startAddress' => $bicycle->adresse->libelleadresse ?? 'Adresse non disponible',
                'veloId' => $bicycle->idvelo,
                'numerovelo' => $bicycle->numerovelo,
                'disponibilite' => $bicycle->estdisponible ? 'Disponible' : 'Indisponible',
            ],
            'tripDate' => Carbon::parse($request->input('tripDate'))->format('Y-m-d'),
            'tripTime' => Carbon::parse($request->input('tripTime'))->format('H:i'),
            'duration' => $request->input('duration'),
            'durationLabel' => $durationLabel,
            'price' => $price,
        ]);
    }

    public function validateReservation(Request $request, $id)
    {
        $userSession = $request->session()->get('user');
        $client = Client::find($userSession['id']);
        $velo = Velo::find($id);
        if (!$velo) {
            return redirect()->back()->with('error', 'Vélo non trouvé');
        }

        if (!$velo->estdisponible) {
            return redirect()->back()->with('error', 'Le vélo est déjà réservé.');
        }

        $tripDate = $request->input('tripDate');
        $tripTime = $request->input('tripTime');
        try {
            $tripDate = trim($tripDate);
            $tripTime = trim($tripTime);
            $tripDateTime = Carbon::createFromFormat('Y-m-d H:i', $tripDate . ' ' . $tripTime, 'Europe/Paris');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Format de date ou d\'heure invalide.');
        }

        $currentDateTime = Carbon::now('Europe/Paris');
        if ($tripDateTime->lessThan($currentDateTime)) {
            return redirect()->back()->with('error', 'La date ou l\'heure de réservation est invalide ou déjà passée.');
        }

        $velo->estdisponible = false;
        $velo->save();

        Reservation::create([
            'idclient' => $client->idclient,
            'idplanning' => $client->idclient,
            'idvelo' => $velo->idvelo,
            'datereservation' => $tripDateTime->format('Y-m-d'),
            'heurereservation' => $tripDateTime->format('H:i'),
            'pourqui' => $request->input('pourqui', 'moi'),
        ]);

        return view('velo.confirmation', [
            'message' => 'Votre réservation de vélo a été enregistrée.',
            'tripDate' => $tripDateTime->format('Y-m-d'),
            'tripTime' => $tripDateTime->format('H:i'),
            'velo' => $velo,
        ]);
    }

    public function confirmation($id, Request $request)
    { //dd($request);
        $velo = Velo::find($id);

        if (!$velo) {
            return redirect()->route('velo.index')->with('error', 'Vélo non trouvé.');
        }

        return view('velo.confirmation', [
            'velo' => $velo,
            'tripDate' => $request->input('tripDate'),
            'tripTime' => $request->input('tripTime'),
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

    /* public function paiementCarte()
    {
        $commandes = Session::get('commandes');
        if (!$commandes) {
            return redirect()->route('velo.index')->withErrors(['message' => 'Aucune reservation.']);
        }

        $total = collect($commandes)->sum('prixcommande');

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $stripeSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Commande UberEats',
                        ],
                        'unit_amount' => $total * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('commande.confirmation', ['id' => 'replace_with_actual_id']) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('panier.index'),
            ]);

            return redirect($stripeSession->url);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Erreur Stripe : ' . $e->getMessage()]);
        }
    } */
    public function finaliserPaiement(Request $request)
    {
        return redirect()->route('velo.fin-reservation')->with('success', 'Votre paiement a été effectué avec succès.');
    }

}

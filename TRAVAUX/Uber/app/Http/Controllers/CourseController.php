<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\Client as turcclient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\LieuFavori;
use App\Models\Reservation;
use App\Models\Course;
use App\Models\Coursier;
use App\Models\Adresse;
use App\Models\Ville;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $startAddress = $request->input('startAddress');
        $endAddress = $request->input('endAddress');
        $tripDate = $request->input('tripDate');
        $tripTime = $request->input('tripTime');

        $baseTime = 5;

        $tripDate = Carbon::parse($tripDate)->format('Y-m-d');

        $dateNow = Carbon::now('Europe/Paris')->format('d-m-Y');;
        $now = Carbon::now('Europe/Paris')->format('H:i:sP');
        $now = $this->roundToPreviousHalfHour($now);



        if (empty($tripTime)) {
            $tripTime = Carbon::now('Europe/Paris')->format('H:i:sP');
            $tripTime = $this->roundToPreviousHalfHour($tripTime);
        } else {
            $tripTime = Carbon::createFromFormat('H:i', $tripTime, 'Europe/Paris')->format('H:i');
        }

        $tripDate = Carbon::parse($tripDate)->format('d-m-Y');

        $jourSemaine = $this->getJourSemaine($tripDate);

        $parts = explode(',', $startAddress);
        if (count($parts) >= 2) {
            $city = trim($parts[count($parts) - 2]);
        } elseif (count($parts) == 1) {

            $city = trim($parts[0]);
        } else {
            return redirect()->back()->withErrors(['error' => 'Invalid start address format.']);
        }

        $prestations = DB::table('type_prestation as tp')
            ->join('a_comme_type as act', 'act.idprestation', '=', 'tp.idprestation')
            ->join('vehicule as v', 'v.idvehicule', '=', 'act.idvehicule')
            ->join('coursier as cou', 'cou.idcoursier', '=', 'v.idcoursier')
            ->join('horaires_coursier as hc', 'hc.idcoursier', '=', 'cou.idcoursier')
            ->join('adresse as ad', 'ad.idadresse', '=', 'cou.idadresse')
            ->join('ville as vi', 'ad.idville', '=', 'vi.idville')
            ->join('code_postal as cp', 'vi.idcodepostal', '=', 'cp.idcodepostal')
            ->where('vi.nomville', $city)
            ->where('hc.joursemaine', $jourSemaine)
            ->where('hc.heuredebut', '<=', $tripTime)
            ->where('hc.heurefin', '>', $tripTime)
            ->distinct()
            ->select('tp.*')
            ->get();

        $distance = $this->getDistanceFromApi($startAddress, $endAddress);
        $baseTime = $this->getTravelTimeFromApi($startAddress, $endAddress);
        $polyline = $this->getPolylineFromApi($startAddress, $endAddress);

        $startCoords = $this->getCoordinatesFromAddress($startAddress);
        $endCoords = $this->getCoordinatesFromAddress($endAddress);

        $calculatedPrestations = $prestations->map(function ($prestation) use ($distance, $baseTime) {
            $priceData = $this->calculatePrice($prestation, $distance, $baseTime);
            $prestation->calculated_price = $priceData['calculated_price'];
            $prestation->adjusted_time = $priceData['adjusted_time'];
            $prestation->distance = $distance;
            return $prestation;
        })->values();

        session()->put('ville', $city);




        return view('course', [
            'prestations' => $calculatedPrestations,
            'endAddress' => $endAddress,
            'startAddress' => $startAddress,
            'tripDate' => $tripDate,
            'tripTime' => $tripTime,
            'jourSemaine' => $jourSemaine,
            'distance' => $distance,
            'time' => $baseTime,
            'city' => $city,
            'polyline' => $polyline,
            'startCoords' => $startCoords,
            'endCoords' => $endCoords,
            'now' => $now,
            'dateNow' => $dateNow
        ]);



    }

    public function showDetails(Request $request)
    {
        $user = session('user');

        $client = turcclient::where('idclient', $user['id'])->first();


        $course = $request->all();

        return view('courses.show-details', [
            'course' => $course,
            'client' => $client
        ]);
    }

    // il faut empêcher le spam (retour en arrière)
    public function validateCourse(Request $request)
    {
        $sessionUser = $request->session()->get('user');
        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')->withErrors([
                'Vous devez être connecté en tant que client pour commander une course.'
            ]);
        }

        $idreservation = session('idreservation');

        $courseData = Course::where('idreservation',$idreservation)->first();


        $tripTimeNow = Carbon::now('Europe/Paris');
        $tripTimeNow = Carbon::parse($tripTimeNow);
        $tripTimeNow = $tripTimeNow->format('Y-m-d H:i');

        $heurecourse = Carbon::createFromFormat('H:i:s', $courseData['heurecourse']);
        $formattedTime = $heurecourse->format('H:i');



        $tripDateTime = Carbon::createFromFormat('Y-m-d H:i', $courseData['datecourse'] . ' ' . $formattedTime);

        $statut = DB::table('course')->where('idreservation', $idreservation)
        ->select('statutcourse')
        ->first();

        if ($tripDateTime->greaterThan($tripTimeNow)) {
            return view('courses.reservation-confirmed', [
                'message' => 'Votre réservation de course a été enregistrée.',
            ]);
        }
        else{

        return view('courses.complete', [
            'course' => $courseData,
            'statutcourse' => $statut->statutcourse,
            'idreservation' => $idreservation,
            'message' => 'Votre course a été validée et commencera bientôt.'
        ]);
    }
    }

    public function cancelCourse(Request $request)
    {
        $validatedData = $request->validate([
            'idreservation' => 'required|integer|exists:course,idreservation',
        ]);

        $courseUpdated = Course::where('idreservation', $validatedData['idreservation'])
            ->update(['statutcourse' => 'Annulée']);

        if ($courseUpdated) {
            return redirect()->route('accueil')->with('success', 'La course a été annulée avec succès.');
        }

        return redirect()->back()->withErrors('Erreur lors de l\'annulation de la course.');
    }

    public function addTipAndRate(Request $request)
    {
        $validatedData = $request->validate([
            'idreservation' => 'required|integer|exists:course,idreservation',
        ]);


        Course::where('idreservation', $validatedData['idreservation'])
            ->update(['statutcourse' => 'Terminée']);


        return view('courses.add-tip-and-rate', [
            'idreservation' => $validatedData['idreservation'],
        ]);


    }

    public function getFavorites(Request $request)
    {
        $user = $request->session()->get('user');

        if (!$user || $user['role'] !== 'client') {
            return response()->json([], 403); // Non autorisé
        }

        $favorites = DB::table('lieu_favori')
            ->join('adresse', 'lieu_favori.idadresse', '=', 'adresse.idadresse')
            ->select('lieu_favori.nomlieu', 'adresse.libelleadresse')
            ->where('lieu_favori.idclient', $user['id'])
            ->get();

        return response()->json($favorites);
    }

    public function view(Request $request, $idreservation)
    {
        // Validation des champs
        $validated = $request->validate([
            'notecourse' => 'required|integer|min:1|max:5', // Note obligatoire entre 1 et 5
            'pourboire' => 'nullable|numeric|min:0|max:80', // Pourboire optionnel entre 0 et 80
        ]);

        // Trouver la course associée à la réservation
        $course = Course::where('idreservation', $idreservation)->firstOrFail();

        // Mise à jour de la course
        $course->notecourse = $validated['notecourse'];
        $course->pourboire = $validated['pourboire'] ?? 0;
        $course->save();
    }




    public function searchDriver(Request $request)
    {

        $sessionUser = $request->session()->get('user');
        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')->withErrors([
                'Vous devez être connecté en tant que client pour commander une course.'
            ]);
        }

        $city = session('ville');



        $idclient = $sessionUser['id'];

        $validatedData = $request->validate([
            'course' => 'required|json',
        ]);

        $course = json_decode($validatedData['course'], true);

        $requiredFields = ['tripDate', 'startAddress', 'endAddress', 'idprestation', 'calculated_price', 'distance', 'adjusted_time', 'tripTime'];
        foreach ($requiredFields as $field) {
            if (empty($course[$field])) {
                return response()->json(['error' => "Le champ {$field} est requis et doit être valide."], 422);
            }
        }

        $tripDate = date('Y-m-d', strtotime($course['tripDate']));
        $tripTimeNow = Carbon::now('Europe/Paris');

        $startVille = Ville::firstOrCreate([
            'nomville' => $city,
            'idpays' => 1
        ]);

        $startAddress = Adresse::firstOrCreate([
            'libelleadresse' => $course['startAddress'],
            'idville' => $startVille['idville'] ?? null,
        ]);





        $endAddress = Adresse::firstOrCreate([
            'libelleadresse' => $course['endAddress'],
            'idville' => $course['endCityId'] ?? null,
        ]);

        $reservation = Reservation::create([
            'idclient' => $idclient,
            'idplanning' => $idclient,
            'pourqui' => 'moi',
            'datereservation' => $tripTimeNow->toDateString(),
            'heurereservation' => $tripTimeNow->addHour()->toTimeString(),
        ]);


        Course::create([
            'idcoursier' => null,
            'idcb' => 1,
            'idreservation' => $reservation->idreservation,
            'idprestation' => $course['idprestation'],
            'prixcourse' => $course['calculated_price'],
            'distance' => $course['distance'],
            'idadresse' => $startAddress->idadresse,
            'adr_idadresse' => $endAddress->idadresse,
            'temps' => $course['adjusted_time'],
            'datecourse' => $tripDate,
            'heurecourse' => $course['tripTime'],
            'statutcourse' => 'En attente',
        ]);






        session()->put('idreservation', $reservation->idreservation);
        return redirect()->route('course.createCourse');


    }

    public function createCourse(){

        $idreservation = session('idreservation');

        $courseData = Course::where('idreservation',$idreservation)->first();

        $coursier = Coursier::where('idcoursier', $courseData['idcoursier'])->first();

        return view('courses.searchDriver', [
            'course' => $courseData,
            'idreservation' => $idreservation,
            'coursier' => $coursier]);
    }

    public function getJourSemaine($dateString)
    {
        $date = new \DateTime($dateString);
        $jourSemaine = $date->format('w');
        $jours = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi'
        ];

        return $jours[$jourSemaine];
    }

    private function calculatePrice($prestation, $distance, $baseTime)
    {
        $priceConfig = [
            'UberX' => [2.00, 1.50, 0.25, 1.0],
            'UberXL' => [3.00, 2.00, 0.30, 1.2],
            'UberVan' => [4.00, 2.50, 0.35, 1.5],
            'Confort' => [3.50, 2.00, 0.40, 0.9],
            'Green' => [2.50, 1.30, 0.20, 1.1],
            'UberPet' => [3.50, 1.70, 0.30, 1.3],
            'Berline' => [5.00, 3.00, 0.50, 0.8],
            'default' => [1.00, 1.00, 0.20, 1.0],
        ];

        $config = $priceConfig[$prestation->libelleprestation] ?? $priceConfig['default'];

        [$basePrice, $distanceRate, $timeRate, $timeMultiplier] = $config;

        $price = $basePrice + ($distance * $distanceRate) + ($baseTime * $timeRate);
        $adjustedTime = ceil($baseTime * $timeMultiplier);

        return [
            'calculated_price' => number_format($price, 2, '.', ''),
            'adjusted_time' => $adjustedTime,
        ];
    }

    private function roundToPreviousHalfHour($time)
    {
        $carbonTime = Carbon::createFromFormat('H:i:sP', $time, 'Europe/Paris');
        $carbonTime->minute(($carbonTime->minute < 30) ? 0 : 30);
        return $carbonTime->format('H:i');
    }

    public function getDistanceFromApi($startAddress, $endAddress)
    {
        $client = new Client();
        $apiKey = 'a2404e3a-1aef-4546-a2e8-7477f836a79d';

        $startCoords = $this->getCoordinatesFromAddress($startAddress);
        $endCoords = $this->getCoordinatesFromAddress($endAddress);

        $url = "https://graphhopper.com/api/1/route?point=" . $startCoords['lat'] . "," . $startCoords['lon'] .
            "&point=" . $endCoords['lat'] . "," . $endCoords['lon'] .
            "&vehicle=car&locale=fr&calc_points=false&key=" . $apiKey;

        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['paths'][0]['distance'])) {
                $distanceInKm = $data['paths'][0]['distance'] / 1000;

                return number_format($distanceInKm, 1, '.', '');
            }
        } catch (\Exception $e) {
            error_log("Erreur lors de l'appel à l'API GraphHopper : " . $e->getMessage());
        }

        return 0;
    }

    private function getCoordinatesFromAddress($address)
    {
        $client = new Client();
        $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . "&format=json&limit=1";

        try {
            $response = $client->get($url, [
                'headers' => ['User-Agent' => 'LaravelApp'] //
            ]);
            $data = json_decode($response->getBody()->getContents(), true);

            if (!empty($data)) {
                return [
                    'lat' => $data[0]['lat'],
                    'lon' => $data[0]['lon']
                ];
            }
        } catch (\Exception $e) {
            error_log("Erreur lors de la géolocalisation : " . $e->getMessage());
        }

        return null;
    }

    public function getTravelTimeFromApi($startAddress, $endAddress)
    {
        $client = new Client();
        $apiKey = 'a2404e3a-1aef-4546-a2e8-7477f836a79d';

        $startCoords = $this->getCoordinatesFromAddress($startAddress);
        $endCoords = $this->getCoordinatesFromAddress($endAddress);

        if (!$startCoords || !$endCoords) {
            error_log("Unable to get coordinates for the given addresses.");
            return 0;
        }

        $url = "https://graphhopper.com/api/1/route?point=" . $startCoords['lat'] . "," . $startCoords['lon'] .
            "&point=" . $endCoords['lat'] . "," . $endCoords['lon'] .
            "&vehicle=car&locale=fr&calc_points=false&key=" . $apiKey;

        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['paths'][0]['time'])) {
                $timeInMinutes = $data['paths'][0]['time'] / 60000;
                return round($timeInMinutes, 1);
            }
        } catch (\Exception $e) {
            error_log("Erreur lors de l'appel à l'API GraphHopper : " . $e->getMessage());
        }

        return 0;
    }

    private function getPolylineFromApi($startAddress, $endAddress)
    {
        $client = new Client();
        $apiKey = 'a2404e3a-1aef-4546-a2e8-7477f836a79d';

        $startCoords = $this->getCoordinatesFromAddress($startAddress);
        $endCoords = $this->getCoordinatesFromAddress($endAddress);

        if (!$startCoords || !$endCoords) {
            error_log("Unable to get coordinates for the given addresses.");
            return null;
        }

        $url = "https://graphhopper.com/api/1/route?point={$startCoords['lat']},{$startCoords['lon']}&point={$endCoords['lat']},{$endCoords['lon']}&vehicle=car&locale=fr&key={$apiKey}";

        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['paths'][0]['points'])) {
                return $data['paths'][0]['points'];
            }
        } catch (\Exception $e) {
            error_log("Error fetching polyline from GraphHopper: " . $e->getMessage());
        }

        return null;
    }

    public function decodePolyline($encoded)
    {
        $points = [];
        $index = 0;
        $len = strlen($encoded);
        $lat = 0;
        $lng = 0;

        while ($index < $len) {
            $b = 0;
            $shift = 0;
            $result = 0;

            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);

            $dlat = ($result & 1) ? ~(($result >> 1)) : ($result >> 1);
            $lat += $dlat;

            $shift = 0;
            $result = 0;

            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);

            $dlng = ($result & 1) ? ~(($result >> 1)) : ($result >> 1);
            $lng += $dlng;

            $points[] = [($lat / 1e5), ($lng / 1e5)];
        }

        return $points;
    }
}

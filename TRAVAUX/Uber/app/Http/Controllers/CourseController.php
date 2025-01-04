<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Reservation;
use App\Models\Course;
use App\Models\Adresse;

class CourseController extends Controller
{
    // j'ai pas touché
    public function index(Request $request)
    {
        $startAddress = $request->input('startAddress');
        $endAddress = $request->input('endAddress');
        $tripDate = $request->input('tripDate');
        $tripTime = $request->input('tripTime');

        $baseTime = 5;

        $tripDate = Carbon::parse($tripDate)->format('Y-m-d');

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
            'endCoords' => $endCoords
        ]);
    }

    public function showDetails(Request $request)
    {
        $course = $request->all();

        return view('courses.show-details', [
            'course' => $course,
        ]);
    }

    // il faut empêcher le spam (retour en arrière)
    public function validateCourse(Request $request)
    {
        $sessionUser = $request->session()->get('user');
        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')->withErrors(['Vous devez être connecté en tant que client pour commander une course.']);
        }

        $idclient = $sessionUser['id'];

        $validatedData = $request->validate([
            'course' => 'required|json',
        ]);

        $course = json_decode($validatedData['course'], true);

        if (!is_array($course) || empty($course['tripDate']) || empty($course['startAddress']) || empty($course['endAddress']) || empty($course['idprestation']) || empty($course['calculated_price']) || empty($course['distance']) || empty($course['adjusted_time']) || empty($course['tripTime'])) {
            return response()->json(['error' => 'Données de course invalides ou incomplètes.'], 422);
        }

        $tripDate = date('Y-m-d', strtotime($course['tripDate']));
        $tripTimeNow = date('H:i:s');

        $startAddress = Adresse::firstOrCreate(['libelleadresse' => $course['startAddress']]);
        $endAddress = Adresse::firstOrCreate(['libelleadresse' => $course['endAddress']]);

        $reservation = Reservation::create([
            'idclient' => $idclient,
            'idplanning' => $idclient,
            'pourqui' => 'moi',
            'datereservation' => now()->toDateString(),
            'heurereservation' => $tripTimeNow,
        ]);

        $courseData = Course::create([
            'idcoursier' => 1, // à faire passer au service course //
            'idcb' => 1, // ceci est faux aussi donc
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

        return view('courses.complete', [
            'course' => $courseData,
            'idreservation' => $reservation->idreservation
        ]);
    }

    public function cancelCourse(Request $request)
    {
        $validatedData = $request->validate([
            'idreservation' => 'required|integer|exists:course,idreservation',
        ]);

        // ish ish ish, ils manquent des trucs
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

        // pour historique course il faut
        Course::where('idreservation', $validatedData['idreservation'])
            ->update(['statutcourse' => 'Terminée']);

        // VOUS COMPTEZ LES AJOUTER DANS LA BASE LE POURBPORE...
        return view('courses.add-tip-and-rate', [
            'idreservation' => $validatedData['idreservation'],
        ]);
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

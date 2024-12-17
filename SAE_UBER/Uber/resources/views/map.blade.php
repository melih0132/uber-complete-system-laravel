<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte avec Itinéraire - GraphHopper</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">
    
</head>

<body>
    <h1>Carte avec Itinéraire</h1>
    <input type="text" id="startAddress" placeholder="Adresse de départ">
    <input type="text" id="endAddress" placeholder="Adresse d'arrivée">
    <button onclick="calculateRoute()">Calculer l'itinéraire</button>
    <div id="map" style="height: 500px;"></div>

    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>

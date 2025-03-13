@extends('accueil')

@section('css2')
    <link rel="stylesheet" href="{{ asset('css/course.blade.css') }}">
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const map = L.map("map").setView([45.8992, 6.1284], 13); // Coordonnées par défaut (Annecy)
        L.tileLayer("https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> ' +
                '&copy; <a href="https://carto.com/attributions">CARTO</a>',
        }).addTo(map);

        const startCoords = {!! json_encode($startCoords) !!};
        const endCoords = {!! json_encode($endCoords) !!};
        const startAddress = {!! json_encode($startAddress) !!};
        const endAddress = {!! json_encode($endAddress) !!};
        const polyline = {!! json_encode($polyline) !!};

        // Ajouter le marqueur de départ
        if (startCoords) {
            addMarker(map, startCoords, `<b>Départ :</b><br>${startAddress}`);
        }

        // Ajouter le marqueur d'arrivée
        if (endCoords) {
            addMarker(map, endCoords, `<b>Arrivée :</b><br>${endAddress}`);
        }

        // Décoder et afficher la polyline
        if (polyline) {
            const decodedPolyline = decodePolyline(polyline);
            const polylineLayer = L.polyline(decodedPolyline, {
                color: 'blue',
                weight: 4
            }).addTo(map);
            map.fitBounds(polylineLayer.getBounds());
        }

        // Fonction pour ajouter un marqueur
        function addMarker(map, coords, popupText) {
            L.marker([coords.lat, coords.lon])
                .addTo(map)
                .bindPopup(popupText)
                .openPopup();
        }

        // Fonction pour décoder une polyline
        function decodePolyline(encoded) {
            let points = [];
            let index = 0,
                lat = 0,
                lng = 0;

            while (index < encoded.length) {
                let result = 0,
                    shift = 0,
                    b;

                do {
                    b = encoded.charCodeAt(index++) - 63;
                    result |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                lat += (result & 1) ? ~(result >> 1) : (result >> 1);

                result = shift = 0;
                do {
                    b = encoded.charCodeAt(index++) - 63;
                    result |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                lng += (result & 1) ? ~(result >> 1) : (result >> 1);

                points.push([lat / 1e5, lng / 1e5]);
            }

            return points;
        }
    });
</script>

@section('TypePrestation')
    <section>

        <div class="container">
            @if ($tripDate < $dateNow)
            <h2 class="text-center mt-4">Veuillez modifier la période de la course choisi.</h2>
            @elseif ($tripDate == $dateNow && $tripTime < $now)
            <h2 class="text-center mt-4">Veuillez modifier l'horaire de la course choisi.</h2>
            @elseif ($prestations->isNotEmpty())
                <h2>Pour une course le {{ $jourSemaine }} {{ $tripDate }} de {{ $startAddress }} à {{ $endAddress }}
                    :
                </h2>
                <ul>
                    @foreach ($prestations as $prestation)
                        <li class="li-prestation my-4">
                            <div class="d-flex align-items-start flex-column">
                                <div class="libelle-prestation">{{ $prestation->libelleprestation }}</div>
                                <p class="p-prestation">{{ $prestation->descriptionprestation }}.</p>
                                <div class="details">
                                    <p class="p-prestation">Distance : <b>{{ $distance }} km</b></p>
                                    <p class="p-prestation">Temps estimé :
                                        <b>
                                            @php
                                                $adjusted_time = $prestation->adjusted_time ?? 0; // Récupérer les minutes ou 0 si non défini
                                                $hours = floor($adjusted_time / 60); // Calcul des heures
                                                $minutes = $adjusted_time % 60; // Calcul des minutes restantes
                                                $formatted_time = sprintf('%2dh%02d minutes', $hours, $minutes); // Formatage en hh:mm
                                            @endphp
                                            {{ $formatted_time }}
                                        </b>
                                    </p>
                                    <p class="p-prestation">Prix estimé : <b>{{ $prestation->calculated_price }} €</b></p>

                                    <form method="POST" action="{{ route('course.details') }}">
                                        @csrf
                                        @foreach (['startAddress', 'endAddress', 'tripTime', 'tripDate', 'adjusted_time', 'calculated_price', 'distance', 'libelleprestation', 'idprestation', 'descriptionprestation', 'imageprestation'] as $field)
                                            <input type="hidden" value="{{ $$field ?? $prestation->$field }}"
                                                name="{{ $field }}">
                                        @endforeach
                                        <button type="submit" class="btn-panier mt-2 mx-2">Réserver</button>
                                    </form>
                                </div>
                            </div>
                            <img alt="Courses" class="img-prestation" src="img/{{ $prestation->imageprestation }}">
                        </li>
                    @endforeach
                </ul>
            @else
                <h2 class="text-center mt-4">Aucune prestation disponible pour cette course.</h2>
            @endif
        </div>
    </section>
@endsection

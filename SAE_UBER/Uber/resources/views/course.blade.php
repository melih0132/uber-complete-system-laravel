@extends('accueil')

@section('css2')
    <link rel="stylesheet" href="{{ asset('css/course.blade.css') }}">
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const map = L.map("map").setView([45.8992, 6.1284], 13); // Default coordinates (Annecy)
        L.tileLayer("https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> ' +
                '&copy; <a href="https://carto.com/attributions">CARTO</a>',
        }).addTo(map);

        // Get data from the server
        const startCoords = {!! json_encode($startCoords) !!};
        const endCoords = {!! json_encode($endCoords) !!};
        const startAddress = {!! json_encode($startAddress) !!};
        const endAddress = {!! json_encode($endAddress) !!};
        const polyline = {!! json_encode($polyline) !!};

        // Set start marker
        if (startCoords) {
            L.marker([startCoords.lat, startCoords.lon])
                .addTo(map)
                .bindPopup(`<b>Départ :</b><br>${startAddress}`)
                .openPopup();
        }

        // Set end marker
        if (endCoords) {
            L.marker([endCoords.lat, endCoords.lon])
                .addTo(map)
                .bindPopup(`<b>Arrivée :</b><br>${endAddress}`)
                .openPopup();
        }

        // Decode and display the polyline
        if (polyline) {
            const decodedPolyline = decodePolyline(polyline);
            L.polyline(decodedPolyline, { color: 'blue', weight: 4 }).addTo(map);
            map.fitBounds(L.polyline(decodedPolyline).getBounds());
        }

        // Function to decode the polyline
        function decodePolyline(encoded) {
            let points = [];
            let index = 0, len = encoded.length;
            let lat = 0, lng = 0;

            while (index < len) {
                let b, shift = 0, result = 0;
                do {
                    b = encoded.charCodeAt(index++) - 63;
                    result |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                let dlat = (result & 1) ? ~(result >> 1) : (result >> 1);
                lat += dlat;

                shift = 0;
                result = 0;
                do {
                    b = encoded.charCodeAt(index++) - 63;
                    result |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                let dlng = (result & 1) ? ~(result >> 1) : (result >> 1);
                lng += dlng;

                points.push([lat / 1e5, lng / 1e5]);
            }
            return points;
        }
    });
</script>

@section('Prestation')
    <section>
        <div class="container">

            @if ($prestations && $prestations->isNotEmpty())
                <h2>Pour une course {{ $jourSemaine }} {{ $tripDate }} de {{ $startAddress }} à {{ $endAddress }} :
                </h2>
                {{-- {{dd($tripDate)}} --}}
                <ul>
                    @foreach ($prestations as $prestation)
                        <li class="li-prestation my-4">
                            <div class="d-flex align-items-start flex-column">
                                <div class="libelle-prestation">{{ $prestation->libelleprestation }}</div>
                                <p class="p-prestation">
                                    {{ $prestation->descriptionprestation }}.
                                </p>
                                <div class="details">
                                    <p class="p-prestation">Distance : <b>{{ $distance }} km</b></p>
                                    <p class="p-prestation">Temps estimé : <b>{{ $prestation->adjusted_time }} minutes</b>
                                    </p>
                                    <p class="p-prestation">Prix estimé : <b>{{ $prestation->calculated_price }} €</b></p>

                                    <form method="POST" action="{{ route('course.detail') }}">
                                        @csrf
                                        <input type="hidden"  value="{{$startAddress}}" name="startAddress">
                                        <input type="hidden"  value="{{$endAddress}}" name="endAddress">
                                        <input type="hidden"  value="{{$tripTime}}" name="tripTime">
                                        <input type="hidden"  value="{{$tripDate}}" name="tripDate">
                                        <input type="hidden"  value="{{$prestation->adjusted_time}}" name="adjusted_time">
                                        <input type="hidden"  value="{{$prestation->calculated_price}}" name="calculated_price">
                                        <input type="hidden"  value="{{$distance}}" name="distance">
                                        <input type="hidden"  value="{{$prestation->libelleprestation}}" name="libelleprestation">
                                        <input type="hidden"  value="{{$prestation->idprestation}}" name="idprestation">
                                        <input type="hidden"  value="{{$prestation->descriptionprestation}}" name="descriptionprestation">
                                        <input type="hidden"  value="{{$prestation->imageprestation}}" name="imageprestation">
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

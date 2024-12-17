<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Carte Leaflet</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css">
    <style>
        /* Assurez-vous que le div #map a une hauteur définie */
        #map {
            height: 500px;
            width: 100%;
            border: 1px solid red; /* Pour visualiser les limites du div */
        }
    </style>
</head>
<body>
    <h1>Carte Test</h1>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof L === 'undefined') {
                console.error("Leaflet n'est pas chargé. Vérifiez l'inclusion des scripts Leaflet.");
                alert("Erreur : La bibliothèque Leaflet n'est pas chargée.");
                return;
            } else {
                console.log("Leaflet est chargé.");
            }

            try {
                const map = L.map("map").setView([45.8992, 6.1284], 13);
                L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 19,
                    attribution:
                        '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                }).addTo(map);
                console.log("Carte Leaflet initialisée avec succès.");
            } catch (error) {
                console.error("Erreur lors de l'initialisation de la carte Leaflet :", error);
                alert("Erreur lors de l'initialisation de la carte. Consultez la console pour plus de détails.");
            }
        });
    </script>
</body>
</html>

let map; // Variable globale pour la carte
let startMarker = null; // Marqueur pour l'adresse de départ
let endMarker = null; // Marqueur pour l'adresse d'arrivée


let currentRouteLayer = null; // Couche pour l'itinéraire
let currentTripData = {}; // Données de l'itinéraire actuel
let calculatedDistance = null; // Variable globale pour la distance

let startCoords = null;
let endCoords = null;

document.addEventListener("DOMContentLoaded", () => {
    // Initialisation de la carte
    map = L.map("map").setView([45.8992, 6.1284], 13); // Центр карты (пример: Annecy)
    L.tileLayer(
      "https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png",
      {
        maxZoom: 19,
        attribution:
          '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> ' +
          '&copy; <a href="https://carto.com/attributions">CARTO</a>',
      }
    ).addTo(map);
  });

  // Функция для установки маркера начальной точки
  function setStartMarker(lat, lon, address) {
    if (startMarker) {
      map.removeLayer(startMarker); // Удаляем существующий маркер
    }

    // Добавляем новый маркер
    startMarker = L.marker([lat, lon])
      .addTo(map)
      .bindPopup(`<b>Départ :</b><br>${address}`) // Текст всплывающего окна
      .openPopup();

    map.setView([lat, lon], 15); // Центрируем карту на маркер
    startCoords = { lat, lon }; // Сохраняем координаты
  }

  // Функция для установки маркера конечной точки
  function setEndMarker(lat, lon, address) {
    if (endMarker) {
      map.removeLayer(endMarker); // Удаляем существующий маркер
    }

    // Добавляем новый маркер
    endMarker = L.marker([lat, lon])
      .addTo(map)
      .bindPopup(`<b>Arrivée :</b><br>${address}`) // Текст всплывающего окна
      .openPopup();

    map.setView([lat, lon], 15); // Центрируем карту на маркер
    endCoords = { lat, lon }; // Сохраняем координаты
  }




// Fonction pour obtenir des suggestions d'adresses
async function fetchSuggestions(inputElement, suggestionsListId) {
    const query = inputElement.value.trim();
    const suggestionsList = document.getElementById(suggestionsListId);

    // Effacer les suggestions précédentes
    suggestionsList.innerHTML = "";

    if (query.length < 3) return; // Commencer la recherche seulement après avoir saisi 3 caractères

    try {
      const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&countrycodes=fr`;
      const response = await axios.get(url);
      const results = response.data;

      // Ajouter les suggestions à la liste
      results.forEach((result) => {
        const address = result.address;

        // Extraire les données pour un format correct
        const houseNumber = address.house_number || ""; // Numéro de maison
        const road = address.road || ""; // Rue
        const cityDistrict = address.city_district || ""; // Quartier/Arrondissement
        const suburb = address.suburb || ""; // Banlieue
        const town = address.town || ""; // Ville (plus petite)
        const village = address.village || ""; // Village
        const city = address.city || ""; // Ville principale
        const postcode = address.postcode || ""; // Code postal

        // Определить самый детализированный адрес
        const detailedCity = cityDistrict || suburb || town || village || city;

        // Форматировать адрес
        const formattedAddress = [
          houseNumber, // Numéro de maison
          road,        // Rue
          detailedCity, // Детализированное название населённого пункта
          postcode,    // Code postal
        ]
          .filter((part) => part) // Supprimer les parties vides
          .join(", ");

        if (formattedAddress) {
          const li = document.createElement("li");
          li.textContent = formattedAddress;
          li.classList.add("suggestion-item"); // Ajouter une classe pour le style

          // Gestion de la sélection de l'adresse
          li.addEventListener("click", () => {
            inputElement.value = formattedAddress; // Insérer l'adresse sélectionnée dans le champ
            suggestionsList.innerHTML = ""; // Effacer la liste des suggestions

            const lat = parseFloat(result.lat); // Latitude
            const lon = parseFloat(result.lon); // Longitude

            if (inputElement.id === "startAddress") {
              setStartMarker(lat, lon, formattedAddress);
            } else if (inputElement.id === "endAddress") {
              setEndMarker(lat, lon, formattedAddress);
            }
          });

          suggestionsList.appendChild(li);
        }
      });
    } catch (error) {
      console.error("Erreur lors de la récupération des adresses:", error);
    }
  }




// Fonction principale pour calculer l'itinéraire
// Fonction principale pour calculer l'itinéraire
async function voirPrix() {
    try {
        console.log("function voirPrix() started");

        // Проверяем, существуют ли маркеры
        if (!startMarker || !endMarker) {
            console.error("Start or end marker is missing.");

            return;
        }

        const startCoords = startMarker.getLatLng();
        const endCoords = endMarker.getLatLng();

        // Проверяем наличие координат
        if (!startCoords || !endCoords) {
            console.error("Coordinates for start or end marker are invalid.");
           
            return;
        }

        const graphhopperApiKey = "a2404e3a-1aef-4546-a2e8-7477f836a79d";
        const url = `https://graphhopper.com/api/1/route?point=${startCoords.lat},${startCoords.lng}&point=${endCoords.lat},${endCoords.lng}&vehicle=car&locale=fr&key=${graphhopperApiKey}`;

        console.log("GraphHopper API URL:", url);

        const response = await axios.get(url);
        if (!response || !response.data || !response.data.paths || response.data.paths.length === 0) {
            console.error("Invalid response from GraphHopper API");

            return;
        }

        const path = response.data.paths[0];
        const distanceKm = (path.distance / 1000).toFixed(2); // Distance en kilomètres
        calculatedDistance = distanceKm;

        console.log(`distance entre 2 points: ${calculatedDistance} km`);

        // Удалить предыдущий маршрут, если он существует
        if (currentRouteLayer) {
            map.removeLayer(currentRouteLayer);
        }

        // Декодировать полилинию и отобразить маршрут на карте
        const latLngs = path.points_encoded
            ? decodePolyline(path.points) // Если полилиния закодирована
            : path.points.coordinates.map(([lon, lat]) => [lat, lon]); // Если полилиния в GeoJSON

        currentRouteLayer = L.polyline(latLngs, { color: "blue", weight: 4 }).addTo(map);
        map.fitBounds(L.polyline(latLngs).getBounds());
    } catch (error) {
        console.error("Erreur lors du calcul de l'itinéraire:", error);

    }
}

// Функция для декодирования полилинии
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










  function updateDateLabel() {
    const dateInput = document.getElementById("tripDate");
    const dateLabel = document.getElementById("tripDateLabel");

    if (dateInput.value) {
        const date = new Date(dateInput.value);
        const formattedDate = date.toLocaleDateString("fr-FR", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });

        // Met à jour le label avec la date formatée
        dateLabel.textContent = formattedDate;
    }
}

// Assure que la fonction est appelée au chargement de la page
document.addEventListener("DOMContentLoaded", function() {
    updateDateLabel();
});


function generateTimeIntervals() {
  const intervals = [];
  for (let hour = 0; hour < 24; hour++) {
    for (let minute = 0; minute < 60; minute += 15) {
      const formattedTime = `${hour.toString().padStart(2, "0")}:${minute.toString().padStart(2, "0")}`;
      intervals.push(formattedTime);
    }
  }
  return intervals;
}

function populateTimeDropdown() {
  const dropdown = document.getElementById("customTimeDropdown");
  const intervals = generateTimeIntervals();

  dropdown.innerHTML = "";
  intervals.forEach((time) => {
    const li = document.createElement("li");
    li.textContent = time;
    li.onclick = () => {
      selectTime(time);
    };
    dropdown.appendChild(li);
  });
}

function selectTime(time) {
  const timeInput = document.getElementById("tripTime");
  const timeLabel = document.getElementById("tripTimeLabel");
  const dropdown = document.getElementById("customTimeDropdown");


  timeInput.value = time;
  const [hours, minutes] = time.split(":");
  timeLabel.textContent = `${hours}h${minutes}`;


  dropdown.classList.remove("show");
}

function toggleTimeDropdown() {
  const dropdown = document.getElementById("customTimeDropdown");
  dropdown.classList.toggle("show");
}

document.addEventListener("DOMContentLoaded", () => {
  populateTimeDropdown();

  document.getElementById("customTimePicker").onclick = toggleTimeDropdown;

  document.addEventListener("click", (event) => {
    const dropdown = document.getElementById("customTimeDropdown");
    const picker = document.getElementById("customTimePicker");
    if (!dropdown.contains(event.target) && !picker.contains(event.target)) {
      dropdown.classList.remove("show");
    }
  });
});


/* let details = document.querySelector("details");
details.addEventListener("click", function() {
    details.classList.toggle("visible");
}) */
document.addEventListener("DOMContentLoaded", () => {
  const stars = document.querySelectorAll(".star-rating .fa");
  const ratingInput = document.getElementById("rating");

  stars.forEach((star) => {
    star.addEventListener("click", () => {
      const rating = star.getAttribute("data-value");
      ratingInput.value = rating;

      // Reset des étoiles
      stars.forEach((s) => s.classList.remove("checked"));

      // Ajouter une classe 'checked' jusqu'à l'étoile cliquée
      star.classList.add("checked");
      let previousStar = star.previousElementSibling;
      while (previousStar) {
        previousStar.classList.add("checked");
        previousStar = previousStar.previousElementSibling;
      }
    });
  });
});



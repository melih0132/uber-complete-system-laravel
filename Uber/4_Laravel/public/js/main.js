let map; // Variable globale pour la carte
let startMarker = null; // Marqueur pour l'adresse de départ
let endMarker = null; // Marqueur pour l'adresse d'arrivée

let currentRouteLayer = null; // Couche pour l'itinéraire
let currentTripData = {}; // Données de l'itinéraire actuel
let calculatedDistance = null; // Variable globale pour la distance

let startCoords = null;
let endCoords = null;

// Debounce timer for fetchSuggestions
let debounceTimer;

// Initialize the map and other functionalities on DOMContentLoaded
document.addEventListener("DOMContentLoaded", () => {
  // Initialisation de la carte
  map = L.map("map").setView([45.8992, 6.1284], 13); // Centre carte (example: Annecy)
  L.tileLayer(
    "https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png",
    {
      maxZoom: 19,
      attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> ' +
        '&copy; <a href="https://carto.com/attributions">CARTO</a>',
    },
  ).addTo(map);

  // Initialiser d'autres fonctionnalités après le chargement de la carte8/
  populateTimeDropdown();
  document.getElementById("customTimePicker").onclick = toggleTimeDropdown;

  // Gestion des clics en dehors du dropdown pour le fermer
  document.addEventListener("click", (event) => {
    const dropdown = document.getElementById("customTimeDropdown");
    const picker = document.getElementById("customTimePicker");
    if (!dropdown.contains(event.target) && !picker.contains(event.target)) {
      dropdown.classList.remove("show");
    }
  });

  // Charger les favoris
  fetchFavorites();

  // Mettre à jour le label de la date au chargement
  updateDateLabel();
});

function setStartMarker(lat, lon, address) {
  if (startMarker) {
    map.removeLayer(startMarker); // Supprimer un marqueur existant
  }

  // Ajouter un nouveau marqueur
  startMarker = L.marker([lat, lon])
    .addTo(map)
    .bindPopup(`<b>Départ :</b><br>${address}`) // Texte contextuel
    .openPopup();
  console.log("ee", address);
  map.setView([lat, lon], 15); // Centrez la carte sur le marqueur
  startCoords = { lat, lon };
}

function setEndMarker(lat, lon, address) {
  if (endMarker) {
    map.removeLayer(endMarker);
  }

  endMarker = L.marker([lat, lon])
    .addTo(map)
    .bindPopup(`<b>Arrivée :</b><br>${address}`)
    .openPopup();

  map.setView([lat, lon], 15);
  endCoords = { lat, lon };
}

// Fonction pour obtenir des suggestions d'adresses avec debounce
async function fetchSuggestions(inputElement, suggestionsListId) {
  const query = inputElement.value.trim();
  const suggestionsList = document.getElementById(suggestionsListId);

  // Effacer les suggestions précédentes
  suggestionsList.innerHTML = "";

  if (query.length < 2) return; // Commencer la recherche seulement après avoir saisi 2 caractères

  // Debounce to limit API calls
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(async () => {
    try {
      const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(
        query,
      )}&format=json&addressdetails=1&countrycodes=fr`;
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

        // Déterminer l'adresse la plus détaillée
        const detailedCity = cityDistrict || suburb || town || village || city;

        const formattedAddress = [
          houseNumber, // Numéro de maison
          road, // Rue
          detailedCity, // Nom détaillé de la localité
          postcode, // Code postal
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
  }, 300); // Adjust the debounce delay as needed
}

// Fonction principale pour calculer l'itinéraire
async function voirPrix(event) {
  event.preventDefault(); // Empêcher le rechargement de la page

  try {
    console.log("function voirPrix() started");

    // Vérifiez si les marqueurs existent
    if (!startMarker || !endMarker) {
      console.error("Start or end marker is missing.");
      alert("Veuillez sélectionner les adresses de départ et d'arrivée.");
      return;
    }

    const startCoords = startMarker.getLatLng();
    const endCoords = endMarker.getLatLng();

    // Vérifiez la validité des coordonnées
    if (!startCoords || !endCoords) {
      console.error("Coordinates for start or end marker are invalid.");
      alert(
        "Les coordonnées des adresses de départ ou d'arrivée sont invalides.",
      );
      return;
    }

    const graphhopperApiKey = "a2404e3a-1aef-4546-a2e8-7477f836a79d";
    const url = `https://graphhopper.com/api/1/route?point=${startCoords.lat},${startCoords.lng}&point=${endCoords.lat},${endCoords.lng}&vehicle=car&locale=fr&key=${graphhopperApiKey}&points_encoded=true`;

    console.log("GraphHopper API URL:", url);

    const response = await axios.get(url);
    if (
      !response ||
      !response.data ||
      !response.data.paths ||
      response.data.paths.length === 0
    ) {
      console.error("Invalid response from GraphHopper API");
      alert("Impossible de calculer l'itinéraire. Veuillez réessayer.");
      return;
    }

    const path = response.data.paths[0];
    const distanceKm = (path.distance / 1000).toFixed(2); // Distance en kilomètres
    calculatedDistance = distanceKm;

    console.log(`distance entre 2 points: ${calculatedDistance} km`);

    // Afficher la distance dans l'interface utilisateur
    document.getElementById("distanceResult").textContent =
      `Distance: ${calculatedDistance} km`;

    // Supprimer le précédent itinéraire, s'il existe
    if (currentRouteLayer) {
      map.removeLayer(currentRouteLayer);
    }

    // Décoder la polyline et afficher l'itinéraire sur la carte
    const latLngs = path.points_encoded
      ? decodePolyline(path.points) // Si la polyline est encodée
      : path.points.coordinates.map(([lon, lat]) => [lat, lon]); // Si la polyline est en GeoJSON

    currentRouteLayer = L.polyline(latLngs, { color: "blue", weight: 4 }).addTo(
      map,
    );
    map.fitBounds(L.polyline(latLngs).getBounds());
  } catch (error) {
    console.error("Erreur lors du calcul de l'itinéraire:", error);
    alert("Une erreur est survenue lors du calcul de l'itinéraire.");
  }
}

// Fonction pour décoder une polyline encodée
function decodePolyline(encoded) {
  let points = [];
  let index = 0,
    len = encoded.length;
  let lat = 0,
    lng = 0;

  while (index < len) {
    let b,
      shift = 0,
      result = 0;
    do {
      b = encoded.charCodeAt(index++) - 63;
      result |= (b & 0x1f) << shift;
      shift += 5;
    } while (b >= 0x20);
    let dlat = result & 1 ? ~(result >> 1) : result >> 1;
    lat += dlat;

    shift = 0;
    result = 0;
    do {
      b = encoded.charCodeAt(index++) - 63;
      result |= (b & 0x1f) << shift;
      shift += 5;
    } while (b >= 0x20);
    let dlng = result & 1 ? ~(result >> 1) : result >> 1;
    lng += dlng;

    points.push([lat / 1e5, lng / 1e5]);
  }
  return points;
}

// Fonction pour mettre à jour le label de la date
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

// Générer des intervalles de temps de 15 minutes
function generateTimeIntervals() {
  const intervals = [];
  for (let hour = 0; hour < 24; hour++) {
    for (let minute = 0; minute < 60; minute += 15) {
      const formattedTime = `${hour.toString().padStart(2, "0")}:${minute
        .toString()
        .padStart(2, "0")}`;
      intervals.push(formattedTime);
    }
  }
  return intervals;
}

// Remplir le dropdown de temps personnalisé
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

// Sélectionner un temps et mettre à jour l'affichage
function selectTime(time) {
  const timeInput = document.getElementById("tripTime");
  const timeLabel = document.getElementById("tripTimeLabel");
  const dropdown = document.getElementById("customTimeDropdown");

  timeInput.value = time;
  const [hours, minutes] = time.split(":");
  timeLabel.textContent = `${hours}h${minutes}`;

  dropdown.classList.remove("show");
}

// Basculer l'affichage du dropdown de temps personnalisé
function toggleTimeDropdown() {
  const dropdown = document.getElementById("customTimeDropdown");
  dropdown.classList.toggle("show");
}

// Fonction de chargement des favoris
function fetchFavorites() {
  axios
    .get("/favorites-suggestions") // Requête au serveur
    .then((response) => {
      const favorites = response.data;
      if (!Array.isArray(favorites)) {
        console.error(
          "Les données favorites ne sont pas au bon format:",
          favorites,
        );
        return;
      }

      // Remplir les listes déroulantes pour le départ et l'arrivée
      populateFavoritesDropdown(
        favorites,
        "startFavoritesDropdown",
        "startAddress",
        "start",
      );
      populateFavoritesDropdown(
        favorites,
        "endFavoritesDropdown",
        "endAddress",
        "end",
      );

      // pour gérer les dropdowns des favoris
      toggleDropdown("startFavoritesToggle", "startFavoritesDropdown");
      toggleDropdown("endFavoritesToggle", "endFavoritesDropdown");
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des lieux favoris:", error);
    });
}

//Fonction pour remplir la liste déroulante des favoris
function populateFavoritesDropdown(favorites, dropdownId, inputId, type) {
  const dropdown = document.getElementById(dropdownId);
  dropdown.innerHTML = "";

  if (!favorites || favorites.length === 0) {
    dropdown.innerHTML = `<li class="no-favorites">Aucun endroit favori</li>`;
    return;
  }

  favorites.forEach((favorite) => {
    if (!favorite.nomlieu || !favorite.libelleadresse) {
      console.warn("Données incorrectes pour le favori:", favorite);
      return;
    }

    // Formation du texte : adresse, ville
    const addressText = `${favorite.libelleadresse}, ${favorite.nomville}`;

    const item = document.createElement("li");
    item.textContent = `${favorite.nomlieu} - ${addressText}`;
    item.classList.add("suggestion-item"); // Ajouter une classe pour le style

    item.addEventListener("click", async () => {
      document.getElementById(inputId).value =
        favorite.libelleadresse + ", " + favorite.nomville;
      dropdown.classList.remove("show");

      try {
        // Geocoding pour obtenir les coordonnées de l'adresse
        const geocodeUrl = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(
          favorite.libelleadresse,
        )}&format=json&addressdetails=1&limit=1&countrycodes=fr`;
        const response = await axios.get(geocodeUrl);
        const results = response.data;

        if (results.length === 0) {
          console.error(
            "Aucune coordonnée trouvée pour l'adresse:",
            favorite.libelleadresse,
          );
          alert("Impossible de trouver l'emplacement sélectionné.");
          return;
        }

        const result = results[0];
        const lat = parseFloat(result.lat);
        const lon = parseFloat(result.lon);
        const adresse = favorite.libelleadresse + ", " + favorite.nomville;

        if (type === "start") {
          setStartMarker(lat, lon, adresse);
        } else if (type === "end") {
          setEndMarker(lat, lon, adresse);
        }
      } catch (error) {
        console.error("Erreur lors du géocodage de l'adresse favorite:", error);
        alert(
          "Une erreur est survenue lors de la localisation de l'adresse sélectionnée.",
        );
      }
    });

    dropdown.appendChild(item);
  });
}

// Fonction pour gérer l'affichage des dropdowns de favoris
function toggleDropdown(buttonId, dropdownId) {
  const button = document.getElementById(buttonId);
  const dropdown = document.getElementById(dropdownId);

  if (!button || !dropdown) {
    console.error("Éléments non trouvés:", { buttonId, dropdownId });
    return;
  }

  button.addEventListener("click", () => {
    console.log(`Cliquez sur le bouton ${buttonId}`); // Vérification
    dropdown.classList.toggle("show"); // Ajouter ou supprimer la classe 'show'
  });
}

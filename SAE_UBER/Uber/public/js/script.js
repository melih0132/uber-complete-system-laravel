document.addEventListener("DOMContentLoaded", () => {
  const villeInput = document.getElementById("recherche_ville");
  const suggestionsList = document.getElementById("suggestions-ville");

  villeInput.addEventListener("input", async () => {
    const query = villeInput.value.trim();

    // Efface les suggestions si le champ est vide ou si la recherche est trop courte
    if (query.length < 3) {
      suggestionsList.innerHTML = "";
      return;
    }

    try {
      const url = `https://nominatim.openstreetmap.org/search?city=${encodeURIComponent(query)}&format=json&addressdetails=1`;
      const response = await axios.get(url);
      const results = response.data;

      // Efface les suggestions précédentes
      suggestionsList.innerHTML = "";

      results.forEach((result) => {
        const city =
          result.address.city || result.address.town || result.address.village;
        if (city) {
          const li = document.createElement("li");
          li.textContent = city;
          li.classList.add("suggestion-item");

          // Lorsqu'une suggestion est cliquée, insérez-la dans le champ
          li.addEventListener("click", () => {
            villeInput.value = city;
            suggestionsList.innerHTML = ""; // Efface les suggestions
          });

          suggestionsList.appendChild(li);
        }
      });

      // Affiche un message si aucune suggestion n'est trouvée
      if (results.length === 0) {
        const noResultsLi = document.createElement("li");
        noResultsLi.textContent = "Aucune ville trouvée.";
        noResultsLi.style.color = "#aaa";
        suggestionsList.appendChild(noResultsLi);
      }
    } catch (error) {
      console.error("Erreur lors de la récupération des villes:", error);
    }
  });

  // Cacher les suggestions si on clique en dehors
  document.addEventListener("click", (event) => {
    if (
      !villeInput.contains(event.target) &&
      !suggestionsList.contains(event.target)
    ) {
      suggestionsList.innerHTML = "";
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const banner = document.getElementById("cookie-banner");
  const settingsBanner = document.getElementById("cookie-settings-banner");
  const acceptButton = document.getElementById("cookie-accept");
  const rejectButton = document.getElementById("cookie-reject");
  const settingsButton = document.getElementById("cookie-settings");
  const closeSettingsButton = document.getElementById("cookie-close-settings");

  const settingsAcceptButton = settingsBanner.querySelector("#cookie-accept");
  const settingsRejectButton = settingsBanner.querySelector("#cookie-reject");
  const advertisingCheckbox = document.getElementById("advertising-checkbox");
  const statisticsCheckbox = document.getElementById("statistics-checkbox");
  const descriptionParagraph = document.getElementById("cookie-settings-description");

  function updateDescription() {
    if (statisticsCheckbox.checked) {
      descriptionParagraph.textContent =
        "Les cookies d'analyse permettent à Uber d'analyser vos visites et actions sur ses applications et sites Web ainsi que ceux d'entreprises tierces, afin de mieux comprendre vos centres d'intérêt et de vous proposer des annonces plus pertinentes sur d'autres applications et sites Web.";
    } else if (advertisingCheckbox.checked) {
      descriptionParagraph.textContent =
        "Les cookies de ciblage publicitaire permettent à Uber de partager vos données avec des partenaires publicitaires, y compris des services de réseaux sociaux, et ce afin de vous envoyer des publicités plus pertinentes via d'autres applications et sites Web, et aux fins déterminées par ces partenaires.";
    } else {
      descriptionParagraph.textContent =
        "Les cookies essentiels sont nécessaires aux fonctionnalités fondamentales de notre site ou de nos services, telles que la connexion au compte, l'authentification et la sécurité du site.";
    }
  }
  function hideBanner() {
    banner.style.display = "none";
  }

  function showSettingsBanner() {
    settingsBanner.style.display = "block";
    settingsBanner.classList.add("show");
  }

  function hideSettingsBanner() {
    settingsBanner.classList.remove("show");
    setTimeout(() => {
      settingsBanner.style.display = "none";
    });
  }
  advertisingCheckbox.addEventListener("change", updateDescription);
  statisticsCheckbox.addEventListener("change", updateDescription);

  acceptButton.addEventListener("click", function () {
    console.log("Cookies acceptés depuis la bannière principale.");
    hideBanner();
  });

  rejectButton.addEventListener("click", function () {
    console.log("Cookies refusés depuis la bannière principale.");
    hideBanner();
  });

  settingsButton.addEventListener("click", function () {
    console.log("Ouverture des paramètres des cookies.");
    showSettingsBanner();
    setTimeout(() => {
      hideBanner();
    });
  });

  settingsAcceptButton.addEventListener("click", function () {
    console.log("Cookies acceptés depuis les paramètres.");
    hideSettingsBanner();
  });

  settingsRejectButton.addEventListener("click", function () {
    console.log("Cookies refusés depuis les paramètres.");
    hideSettingsBanner();
  });

  closeSettingsButton.addEventListener("click", function () {
    console.log("Fermeture des paramètres des cookies.");
    hideSettingsBanner();
    banner.style.display = "block";
  });
  updateDescription();
});
//recup adresse ip
document.addEventListener("DOMContentLoaded", async () => {
  const cookieBanner = document.getElementById("cookie-banner");
  const settingsBanner = document.getElementById("cookie-settings-banner");

  const acceptButtonMain = document.getElementById("cookie-accept");
  const rejectButtonMain = document.getElementById("cookie-reject");
  const acceptButtonSettings = settingsBanner.querySelector("#cookie-accept");
  const rejectButtonSettings = settingsBanner.querySelector("#cookie-reject");
  const closeSettingsButton = document.getElementById("cookie-close-settings");

  const pageURL = window.location.pathname;

  async function getIPAddress() {
    try {
      const response = await axios.get("https://api64.ipify.org?format=json");
      return response.data.ip;
    } catch (error) {
      console.error("Impossible de récupérer l'adresse IP :", error);
      throw error;
    }
  }

  async function handleCookieConsent(consent) {
    try {
      const userIP = await getIPAddress();
      localStorage.setItem(`user_cookie_consent_${pageURL}_${userIP}`, consent); // Nouvelle clé
      cookieBanner.classList.add("hidden");
      settingsBanner.style.display = "none";
    } catch (error) {
      console.error("Erreur lors de l'enregistrement du consentement :", error);
    }
  }

  async function checkCookieConsent() {
    try {
      const userIP = await getIPAddress();
      const consentGiven = localStorage.getItem(
        `user_cookie_consent_${pageURL}_${userIP}`,
      ); // Nouvelle clé
      if (consentGiven) {
        cookieBanner.classList.add("hidden");
        settingsBanner.style.display = "none";
      } else {
        cookieBanner.classList.remove("hidden");
      }
    } catch (error) {
      console.error("Erreur lors de la vérification du consentement :", error);
    }
  }

  acceptButtonMain.addEventListener("click", () =>
    handleCookieConsent("accepted"),
  );
  rejectButtonMain.addEventListener("click", () =>
    handleCookieConsent("rejected"),
  );

  acceptButtonSettings.addEventListener("click", () =>
    handleCookieConsent("accepted"),
  );
  rejectButtonSettings.addEventListener("click", () =>
    handleCookieConsent("rejected"),
  );
  closeSettingsButton.addEventListener("click", () => {
    settingsBanner.style.display = "none";
    cookieBanner.classList.remove("hidden");
  });

  checkCookieConsent();
});

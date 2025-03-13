function checkPasswordStrength() {
  const password = document.getElementById("motdepasseuser").value;
  const strengthDisplay = document.getElementById("password-strength");
  const registerBtn = document.getElementById("registerBtn");

  // Sélection des cases à cocher
  const lengthCheck = document.getElementById("lengthCheck");
  const uppercaseCheck = document.getElementById("uppercaseCheck");
  const numberCheck = document.getElementById("numberCheck");
  const specialCharCheck = document.getElementById("specialCharCheck");

  let strength = "";
  let color = "";

  const hasLowerCase = /[a-z]/.test(password);
  const hasUpperCase = /[A-Z]/.test(password);
  const hasNumbers = /[0-9]/.test(password);
  const hasSpecialChar = /[!@#\$%\^&\*]/.test(password);
  const uniqueChars = new Set(password).size;

  // Mise à jour des cases à cocher
  lengthCheck.checked = password.length >= 8;
  uppercaseCheck.checked = hasUpperCase;
  numberCheck.checked = hasNumbers;
  specialCharCheck.checked = hasSpecialChar;

  // Vérification de la force du mot de passe
  if (password.length < 8 || uniqueChars <= 3) {
    strength = "Faible";
    color = "red";
  } else if (
    password.length >= 8 &&
    uniqueChars > 3 &&
    (hasLowerCase || hasUpperCase || hasNumbers || hasSpecialChar)
  ) {
    strength = "Moyen";
    color = "orange";

    if (
      password.length > 10 &&
      hasLowerCase &&
      hasUpperCase &&
      hasNumbers &&
      hasSpecialChar &&
      uniqueChars > 6
    ) {
      strength = "Fort";
      color = "green";
    }
  } else {
    strength = "Très Faible";
    color = "darkred";
  }

  strengthDisplay.textContent = `Force du mot de passe : ${strength}`;
  strengthDisplay.style.color = color;

  // Activation du bouton si toutes les conditions sont remplies
  registerBtn.disabled = !(
    lengthCheck.checked &&
    uppercaseCheck.checked &&
    numberCheck.checked &&
    specialCharCheck.checked
  );
}

function limitInputLength(input, maxLength) {
  if (input.value.length > maxLength) {
    input.value = input.value.slice(0, maxLength);
  }
}

function validateNumericInput(input) {
  input.value = input.value.replace(/\D/g, "");
}

function validatePhoneNumberInput(input) {
  input.value = input.value.replace(/[^0-9+]/g, "");
  if (input.value.indexOf("+") > 0) {
    input.value = input.value.replace(/\+/g, "");
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const dateNaissanceInput = document.getElementById("datenaissance");
  const today = new Date();
  const maxDate = new Date(
    today.getFullYear() - 18,
    today.getMonth(),
    today.getDate(),
  );
  const formattedMaxDate = maxDate.toISOString().split("T")[0];
  dateNaissanceInput.setAttribute("max", formattedMaxDate);
});

document.addEventListener("DOMContentLoaded", function () {
  const toggleEntrepriseBtn = document.getElementById("toggleEntreprise");
  const entrepriseSection = document.getElementById("entrepriseSection");

  toggleEntrepriseBtn.addEventListener("click", function () {
    entrepriseSection.classList.toggle("d-none");
    this.textContent = entrepriseSection.classList.contains("d-none")
      ? "Ajouter des informations sur l'entreprise (optionnel)"
      : "Cacher les informations sur l'entreprise";
  });
});

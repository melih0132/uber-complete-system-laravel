// Vérification de la force du mot de passe
function checkPasswordStrength() {
  const password = document.getElementById("motdepasseuser").value;
  const strengthDisplay = document.getElementById("password-strength");
  let strength = "";
  let color = "";

  const hasLowerCase = /[a-z]/.test(password);
  const hasUpperCase = /[A-Z]/.test(password);
  const hasNumbers = /[0-9]/.test(password);
  const hasSpecialChar = /[!@#\$%\^&\*]/.test(password);
  const uniqueChars = new Set(password).size;

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
}

// Limiter la longueur de l'entrée
function limitInputLength(input, maxLength) {
  if (input.value.length > maxLength) {
    input.value = input.value.slice(0, maxLength);
  }
}

// Valider les champs numériques (uniquement des chiffres)
function validateNumericInput(input) {
  input.value = input.value.replace(/\D/g, "");
}

// Valider les numéros de téléphone (uniquement des chiffres et un "+")
function validatePhoneNumberInput(input) {
  input.value = input.value.replace(/[^0-9+]/g, "");
  if (input.value.indexOf("+") > 0) {
    input.value = input.value.replace(/\+/g, "");
  }
}

// Limiter la date de naissance à aujourd'hui - 18 ans
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

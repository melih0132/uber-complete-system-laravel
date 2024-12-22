@extends('layouts.app')

@section('title', 'Inscription')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center mt-5 card px-3">
            <h1>Créer un compte</h1>
            <div id="selectionFields">
                <fieldset>
                    <legend class="label">Sélection :</legend>
                    <div class="form-selection">
                        <label for="personal">Pour vous</label>
                        <input type="radio" id="personal" name="selectionfield" value="personal">
                    </div>
                    <div class="form-selection">
                        <label for="company">Pour votre entreprise</label>
                        <input type="radio" id="company" name="selectionfield" value="company">
                    </div>
                </fieldset>
            </div>

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                <div class="form-grid">

                    <!-- Informations personnelles -->
                    <div class="form-section">
                        <h5>Informations personnelles :</h5>

                        <div>
                            <label for="role">Choisissez votre rôle :</label>
                            <select name="role" id="role" required>
                                <option value="client">Client</option>
                                <option value="coursier">Coursier</option>
                            </select>
                        </div>

                        <label for="nomuser">Nom :</label>
                        <input type="text" name="nomuser" id="nomuser" required maxlength="50">

                        <label for="prenomuser">Prénom :</label>
                        <input type="text" name="prenomuser" id="prenomuser" required maxlength="50">

                        <label for="genreuser">Genre :</label>
                        <select name="genreuser" id="genreuser" required>
                            <option value="Monsieur">Monsieur</option>
                            <option value="Madame">Madame</option>
                        </select>

                        <label for="datenaissance">Date de naissance :</label>
                        <input type="date" name="datenaissance" id="datenaissance" required>

                        <label for="telephone">Téléphone :</label>
                        <input type="text" name="telephone" id="telephone" required pattern="^(06|07)[0-9]{8}$"
                            title="Numéro de téléphone valide (06 ou 07 suivi de 8 chiffres)">

                        <label for="emailuser">Email :</label>
                        <input type="email" name="emailuser" id="emailuser" required>

                        <label for="motdepasseuser">Mot de passe :</label>
                        <input type="password" id="motdepasseuser" name="motdepasseuser" required minlength="8"
                            oninput="checkPasswordStrength()">
                        <div id="password-strength" style="margin-top: 5px; font-weight: bold;"></div>

                        <label for="motdepasseuser_confirmation">Confirmation du mot de passe :</label>
                        <input type="password" name="motdepasseuser_confirmation" id="motdepasseuser_confirmation" required>

                        <div class="d-inline-flex align-items-center my-3" id="bonPlanFields">
                            <label>Souhaitez-vous recevoir des bons plans ?</label>
                            <input class="check-box mx-2" type="checkbox" name="souhaiterecevoirbonplan" id="souhaiterecevoirbonplan"
                                value="1">
                        </div>

                        <!-- Adresse pour tous les utilisateurs -->
                        <h5>Adresse :</h5>
                        <label for="libelleadresse">Adresse :</label>
                        <input type="text" name="libelleadresse" id="libelleadresse" required maxlength="100">

                        <label for="nomville">Ville :</label>
                        <input type="text" name="nomville" id="nomville" required maxlength="50">

                        <label for="codepostal">Code Postal :</label>
                        <input type="text" name="codepostal" id="codepostal" title="Code postal valide (5 chiffres)">
                    </div>

                    <!-- Informations pour les entreprises -->
                    <div id="companyFields" class="form-section" style="display: none;" data-role="coursier">
                        <h5>Informations sur l'entreprise :</h5>

                        <label for="nomentreprise">Nom de l'entreprise :</label>
                        <input type="text" name="nomentreprise" id="nomentreprise" maxlength="50">

                        <label for="siretentreprise">SIRET de l'entreprise :</label>
                        <input type="text" name="siretentreprise" id="siretentreprise" pattern="^\d{14}$"
                            title="SIRET valide (14 chiffres)">

                        <label for="taille">Taille :</label>
                        <select name="taille" id="taille">
                            <option value="PME">PME</option>
                            <option value="ETI">ETI</option>
                            <option value="GE">GE</option>
                        </select>

                        <label for="adresse">Adresse :</label>
                        <input type="text" name="adresse" id="adresse" maxlength="100" placeholder="Numéro et rue">

                        <label for="ville">Ville :</label>
                        <input type="text" name="ville" id="ville" maxlength="50" placeholder="Ville">

                        <label for="cp">Code postal :</label>
                        <input type="text" name="cp" id="cp" title="Code postal valide (5 chiffres)">
                    </div>

                    <!-- Informations spécifiques aux coursiers -->
                    <div id="professionalFields" class="form-section" style="display: none;" data-role="coursier">
                        <h5>Informations pour les coursiers :</h5>

                        <label for="numerocartevtc">Numéro de carte VTC :</label>
                        <input type="text" name="numerocartevtc" id="numerocartevtc" pattern="^\d{12}$"
                            title="Numéro VTC valide (12 chiffres)">

                        <label for="iban">IBAN :</label>
                        <input type="text" name="iban" id="iban" pattern="^[A-Z0-9]{15,34}$"
                            title="IBAN valide (15 à 34 caractères alphanumériques)">

                        <label for="datedebutactivite">Date de début d'activité :</label>
                        <input type="date" name="datedebutactivite" id="datedebutactivite">
                    </div>
                </div>

                <div class="d-flex flex-column justify-content-center">
                    <button type="submit" class="btn-login" id="submit-button">S'inscrire</button>
                    <a href="{{ url('/login') }}" class="login-link text-center my-3">Me connecter</a>
                </div>

            </form>

            <script>
                document.getElementById('role').addEventListener('change', function() {
                    var role = this.value;
                    var professionalFields = document.getElementById('professionalFields');
                    var companyFields = document.getElementById('companyFields');
                    var selectionFiels = document.getElementById('selectionFields');
                    var bonPlan = document.getElementById('bonPlanFields');

                    if (role === 'coursier') {
                        bonPlan.style.display = 'none'
                        professionalFields.style.display = 'block';
                        companyFields.style.display = 'block';
                        selectionFiels.style.display = 'none';
                        disableFields(companyFields, false);
                        disableFields(professionalFields, false);
                    } else {
                        professionalFields.style.display = 'none';
                        companyFields.style.display = 'none';
                        selectionFiels.style.display = 'block';
                        disableFields(companyFields, true);
                        disableFields(professionalFields, true);
                    }
                });

                document.addEventListener("DOMContentLoaded", function() {
                    const personalRadio = document.getElementById("personal");
                    const companyRadio = document.getElementById("company");
                    const companyFields = document.getElementById("companyFields");
                    var selectionFiels = document.getElementById('selectionFields');

                    function toggleCompanyFields() {
                        if (companyRadio.checked) {
                            companyFields.style.display = "block";
                            selectionFiels.style.display = "none"
                        } else if (personalRadio.checked) {
                            companyFields.style.display = "none";
                            selectionFiels.style.display = "block"
                        }
                    }

                    personalRadio.addEventListener("change", toggleCompanyFields);
                    companyRadio.addEventListener("change", toggleCompanyFields);

                    toggleCompanyFields()
                });


                document.getElementsByName('registration_type').forEach(radio => {
                    radio.addEventListener('change', function() {
                        var companyFields = document.getElementById('companyFields');

                        if (this.value === 'company') {
                            companyFields.style.display = 'block';
                        } else {
                            companyFields.style.display = 'none';
                        }
                    });
                });

                function checkPasswordStrength() {
                    const password = document.getElementById('motdepasseuser').value;
                    const strengthDisplay = document.getElementById('password-strength');
                    const submitButton = document.getElementById('submit-button');
                    let strength = '';
                    let color = '';
                    let isStrong = false;

                    const hasLowerCase = /[a-z]/.test(password);
                    const hasUpperCase = /[A-Z]/.test(password);
                    const hasNumbers = /[0-9]/.test(password);
                    const hasSpecialChar = /[!@#\$%\^&\*]/.test(password);
                    const uniqueChars = new Set(password).size;
                    if (password.length < 8 || uniqueChars <= 3) {
                        strength = 'Faible';
                        color = 'red';
                    } else if (
                        password.length >= 8 &&
                        uniqueChars > 3 &&
                        (hasLowerCase || hasUpperCase || hasNumbers || hasSpecialChar)
                    ) {
                        strength = 'Moyen';
                        color = 'orange';

                        if (
                            password.length > 10 &&
                            hasLowerCase &&
                            hasUpperCase &&
                            hasNumbers &&
                            hasSpecialChar &&
                            uniqueChars > 6
                        ) {
                            strength = 'Fort';
                            color = 'green';
                            isStrong = true;
                        }
                    } else {
                        strength = 'Très Faible';
                        color = 'darkred';
                    }

                    strengthDisplay.textContent = `Force du mot de passe : ${strength}`;
                    strengthDisplay.style.color = color;

                    submitButton.disabled = !isStrong;
                }
            </script>

        </div>
    </div>

@endsection

@extends('layouts.app')

@section('title', 'Inscription Coursier')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">Inscription Coursier</h1>
        <form action="{{ route('register') }}" method="POST" class="form-register d-flex flex-column justify-content-center">
            @csrf
            <input type="hidden" name="role" value="coursier">

            <!-- Informations personnelles -->
            <h5>Informations personnelles :</h5>
            <div class="form-group">
                <label for="nomuser">Nom :</label>
                <input type="text" name="nomuser" id="nomuser" class="form-control" required maxlength="50">
            </div>

            <div class="form-group">
                <label for="prenomuser">Prénom :</label>
                <input type="text" name="prenomuser" id="prenomuser" class="form-control" required maxlength="50">
            </div>

            <label for="genreuser">Genre :</label>
            <select name="genreuser" id="genreuser" required>
                <option value="Monsieur">Monsieur</option>
                <option value="Madame">Madame</option>
            </select>

            <label for="datenaissance">Date de naissance :</label>
            <input type="date" name="datenaissance" id="datenaissance" required>

            <div class="form-group">
                <label for="telephone">Téléphone :</label>
                <input type="text" name="telephone" id="telephone" class="form-control" required
                    pattern="^(06|07)[0-9]{8}$" title="Numéro de téléphone valide (06 ou 07 suivi de 8 chiffres)">
            </div>

            <div class="form-group">
                <label for="emailuser">Email :</label>
                <input type="email" name="emailuser" id="emailuser" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="motdepasseuser">Mot de passe :</label>
                <input type="password" id="motdepasseuser" name="motdepasseuser" class="form-control" required
                    minlength="8" oninput="checkPasswordStrength()">
                <div id="password-strength" style="margin-top: 5px; font-weight: bold;"></div>
            </div>

            <div class="form-group">
                <label for="motdepasseuser_confirmation">Confirmation du mot de passe :</label>
                <input type="password" name="motdepasseuser_confirmation" id="motdepasseuser_confirmation"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label for="numerocartevtc">Numéro de carte VTC :</label>
                <input type="text" name="numerocartevtc" id="numerocartevtc" class="form-control" pattern="^\d{12}$"
                    title="Numéro VTC valide (12 chiffres)" required>
            </div>

            <!-- Informations sur l'entreprise (obligatoires pour les coursiers) -->
            <h5>Informations sur votre entreprise :</h5>

            <div class="form-group">
                <label for="nomentreprise">Nom de l'entreprise :</label>
                <input type="text" name="nomentreprise" id="nomentreprise" class="form-control" required maxlength="100">
            </div>

            <div class="form-group">
                <label for="siretentreprise">SIRET de l'entreprise :</label>
                <input type="text" name="siretentreprise" id="siretentreprise" class="form-control" pattern="^\d{14}$"
                    title="SIRET valide (14 chiffres)" required>
            </div>

            <div class="form-group">
                <label for="taille">Taille de l'entreprise :</label>
                <select name="taille" id="taille" class="form-control" required>
                    <option value="PME">PME</option>
                    <option value="ETI">ETI</option>
                    <option value="GE">GE</option>
                </select>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse de l'entreprise :</label>
                <input type="text" name="adresse" id="adresse" class="form-control" required maxlength="100">
            </div>

            <div class="form-group">
                <label for="ville">Ville :</label>
                <input type="text" name="ville" id="ville" class="form-control" required maxlength="50">
            </div>

            <div class="form-group">
                <label for="codepostal">Code Postal :</label>
                <input type="text" name="codepostal" id="codepostal" class="form-control"
                    title="Code postal valide (5 chiffres)" required>
            </div>

{{--             <!-- Informations professionnelles -->
            <h5>Informations professionnelles :</h5>
            <div class="form-group">
                <label for="numerocartevtc">Numéro de carte VTC :</label>
                <input type="text" name="numerocartevtc" id="numerocartevtc" class="form-control" pattern="^\d{12}$"
                    title="Numéro VTC valide (12 chiffres)" required>
            </div>

            <div class="form-group">
                <label for="iban">IBAN :</label>
                <input type="text" name="iban" id="iban" class="form-control" pattern="^[A-Z0-9]{15,34}$"
                    title="IBAN valide (15 à 34 caractères alphanumériques)" required>
            </div>

            <div class="form-group">
                <label for="datedebutactivite">Date de début d'activité :</label>
                <input type="date" name="datedebutactivite" id="datedebutactivite" class="form-control" required>
            </div> --}}

            <button type="submit" class="btn-login">S'inscrire</button>
        </form>
    </div>

    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('motdepasseuser').value;
            const strengthDisplay = document.getElementById('password-strength');
            let strength = '';
            let color = '';

            const hasLowerCase = /[a-z]/.test(password);
            const hasUpperCase = /[A-Z]/.test(password);
            const hasNumbers = /[0-9]/.test(password);
            const hasSpecialChar = /[!@#\$%\^&\*]/.test(password);
            const uniqueChars = new Set(password).size;

            if (password.length < 8 || uniqueChars <= 3) {
                strength = 'Faible';
                color = 'red';
            } else if (password.length >= 8 && uniqueChars > 3 &&
                (hasLowerCase || hasUpperCase || hasNumbers || hasSpecialChar)) {
                strength = 'Moyen';
                color = 'orange';

                if (password.length > 10 && hasLowerCase && hasUpperCase &&
                    hasNumbers && hasSpecialChar && uniqueChars > 6) {
                    strength = 'Fort';
                    color = 'green';
                }
            } else {
                strength = 'Très Faible';
                color = 'darkred';
            }

            strengthDisplay.textContent = `Force du mot de passe : ${strength}`;
            strengthDisplay.style.color = color;
        }
    </script>
@endsection

@extends('layouts.app')

@section('title', 'Inscription')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Inscription</h1>
        <form action="{{ route('register') }}" method="POST" class="form-register d-flex flex-column justify-content-center">
            @csrf

            <div class="form-group mb-3">
                <label for="nomuser">Nom :</label>
                <input type="text" name="nomuser" id="nomuser" class="form-control" required maxlength="50"
                    placeholder="Votre nom">
            </div>

            <div class="form-group mb-3">
                <label for="prenomuser">Prénom :</label>
                <input type="text" name="prenomuser" id="prenomuser" class="form-control" required maxlength="50"
                    placeholder="Votre prénom">
            </div>

            <div class="form-group mb-3">
                <label for="genreuser">Genre :</label>
                <select name="genreuser" id="genreuser" class="form-control" required>
                    <option value="" disabled selected>Choisissez votre genre</option>
                    <option value="Monsieur">Monsieur</option>
                    <option value="Madame">Madame</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="datenaissance">Date de naissance :</label>
                <input type="date" name="datenaissance" id="datenaissance" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="telephone">Téléphone :</label>
                <input type="text" name="telephone" id="telephone" class="form-control" required
                    pattern="^(06|07)[0-9]{8}$" title="Numéro de téléphone valide (06 ou 07 suivi de 8 chiffres)"
                    placeholder="06xxxxxxxx">
            </div>

            <div class="form-group mb-3">
                <label for="emailuser">Email :</label>
                <input type="email" name="emailuser" id="emailuser" class="form-control" required
                    placeholder="exemple@mail.com">
            </div>

            <div class="form-group mb-3">
                <label for="motdepasseuser">Mot de passe :</label>
                <input type="password" id="motdepasseuser" name="motdepasseuser" class="form-control" required
                    minlength="8" placeholder="Mot de passe sécurisé" oninput="checkPasswordStrength()">
                <div id="password-strength" class="mt-2" style="font-weight: bold;"></div>
            </div>

            <div class="form-group mb-3">
                <label for="motdepasseuser_confirmation">Confirmation du mot de passe :</label>
                <input type="password" name="motdepasseuser_confirmation" id="motdepasseuser_confirmation"
                    class="form-control" required placeholder="Confirmez votre mot de passe">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="souhaiterecevoirbonplan" id="souhaiterecevoirbonplan"
                    value="1">
                <label class="form-check-label" for="souhaiterecevoirbonplan">
                    Souhaitez-vous recevoir des bons plans ?
                </label>
            </div>

            <div class="form-group mb-3">
                <label for="libelleadresse">Adresse :</label>
                <input type="text" name="libelleadresse" id="libelleadresse" class="form-control" required
                    maxlength="100" placeholder="Adresse complète">
            </div>

            <div class="form-group mb-3">
                <label for="nomville">Ville :</label>
                <input type="text" name="nomville" id="nomville" class="form-control" required maxlength="50"
                    placeholder="Ville">
            </div>

            <div class="form-group mb-3">
                <label for="codepostal">Code Postal :</label>
                <input type="text" name="codepostal" id="codepostal" class="form-control" required pattern="^\d{5}$"
                    title="Code postal valide (5 chiffres)" placeholder="75000">
            </div>

            {{-- NE SURTOUT PAS ENLEVER --}}
            <input type="hidden" name="role" value="client">

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

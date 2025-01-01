@extends('layouts.app')

@section('title', 'Inscription Coursier')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/js.js') }}"></script>
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Inscription Driver</h1>

        <form action="{{ route('register') }}" method="POST" class="form-register d-flex flex-column justify-content-center">
            @csrf

            <h5 class="mb-3">Informations personnelles :</h5>
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
                <small>Vous devez avoir au moins 18 ans.</small>
            </div>

            <div class="form-group mb-3">
                <label for="telephone">Téléphone :</label>
                <input type="text" name="telephone" id="telephone" class="form-control" required
                    pattern="^(06|07)[0-9]{8}$|^\+?[1-9][0-9]{1,14}$"
                    title="Numéro de téléphone valide (06 ou 07 suivi de 8 chiffres ou format international)"
                    placeholder="06XXXXXXXX" inputmode="tel" oninput="validatePhoneNumberInput(this)">
                <small>Exemple : 0612345678 ou +33123456789</small>
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

            <div class="form-group mb-3">
                <label for="numerocartevtc">Numéro de carte VTC :</label>
                <input type="text" name="numerocartevtc" id="numerocartevtc" class="form-control" required
                    pattern="^\d{12}$" title="Numéro VTC valide (12 chiffres)" placeholder="123456789012" maxlength="12"
                    inputmode="numeric" oninput="validateNumericInput(this)">
                <small>Veuillez entrer exactement 12 chiffres.</small>
            </div>

            <h5 class="mb-3">Informations sur votre entreprise :</h5>
            <div class="form-group mb-3">
                <label for="nomentreprise">Nom de l'entreprise :</label>
                <input type="text" name="nomentreprise" id="nomentreprise" class="form-control" required maxlength="100"
                    placeholder="Nom de l'entreprise">
            </div>

            <div class="form-group mb-3">
                <label for="siretentreprise">SIRET de l'entreprise :</label>
                <input type="text" name="siretentreprise" id="siretentreprise" class="form-control" required
                    pattern="^\d{14}$" title="SIRET valide (14 chiffres)" placeholder="12345678901234" maxlength="14"
                    inputmode="numeric" oninput="validateNumericInput(this)">
                <small>Veuillez entrer exactement 14 chiffres.</small>
            </div>

            <div class="form-group mb-3">
                <label for="taille">Taille de l'entreprise :</label>
                <select name="taille" id="taille" class="form-control" required>
                    <option value="" disabled selected>Choisissez une taille</option>
                    <option value="PME">PME</option>
                    <option value="ETI">ETI</option>
                    <option value="GE">GE</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="adresse">Adresse de l'entreprise :</label>
                <input type="text" name="adresse" id="adresse" class="form-control" required maxlength="100"
                    placeholder="Adresse complète">
            </div>

            <div class="form-group mb-3">
                <label for="ville">Ville :</label>
                <input type="text" name="ville" id="ville" class="form-control" required maxlength="50"
                    placeholder="Ville">
            </div>

            <div class="form-group mb-3">
                <label for="codepostal">Code Postal :</label>
                <input type="text" name="codepostal" id="codepostal" class="form-control" required pattern="^\d{5}$"
                    title="Code postal valide (5 chiffres)" placeholder="75000" maxlength="5" inputmode="numeric"
                    oninput="validateNumericInput(this)">
                <small>Veuillez entrer un code postal valide (5 chiffres).</small>
            </div>

            @if (session('success') || session('error'))
                <div class="alert-message @if (session('success')) success @elseif(session('error')) error @endif"
                    role="alert">
                    {{ session('success') ?? session('error') }}
                </div>
            @endif

            {{-- NE SURTOUT PAS ENLEVER --}}
            <input type="hidden" name="role" value="coursier">

            <button type="submit" class="btn-login">S'inscrire</button>
        </form>
    </div>
@endsection

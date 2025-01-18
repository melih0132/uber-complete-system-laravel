@extends('layouts.app')

@section('title', 'Inscription Responsable d\'Enseigne')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.blade.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/js.js') }}"></script>
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Inscription Responsable d'Enseigne</h1>

        <form action="{{ route('register') }}" method="POST" class="form-register">
            @csrf

            <!-- Informations personnelles -->
            <div class="form-section">
                <h5>Informations personnelles</h5>

                <div class="form-group">
                    <label for="nomuser">Nom :</label>
                    <input type="text" name="nomuser" id="nomuser" class="form-control" required maxlength="50"
                        placeholder="Nom du responsable d'enseigne">
                </div>

                <div class="form-group">
                    <label for="prenomuser">Prénom :</label>
                    <input type="text" name="prenomuser" id="prenomuser" class="form-control" required maxlength="50"
                        placeholder="Prénom du responsable d'enseigne">
                </div>
            </div>

            <!-- Coordonnées personnelles -->
            <div class="form-section">
                <h5>Coordonnées personnelles</h5>

                <div class="form-group">
                    <label for="telephone">Téléphone :</label>
                    <input type="text" name="telephone" id="telephone" class="form-control" required
                        pattern="^(06|07)[0-9]{8}$|^\+?[1-9][0-9]{1,14}$"
                        title="Numéro de téléphone valide (06 ou 07 suivi de 8 chiffres ou format international)"
                        placeholder="06XXXXXXXX" inputmode="tel" oninput="validatePhoneNumberInput(this)">
                    <small>Exemple : 0612345678 ou +33123456789</small>
                </div>

                <div class="form-group">
                    <label for="emailuser">Email :</label>
                    <input type="email" name="emailuser" id="emailuser" class="form-control" required maxlength="200"
                        placeholder="Email professionnel">
                </div>
            </div>

            <!-- Section Sécurité -->
            <div class="form-section">
                <h5>Sécurité</h5>

                <div class="form-group">
                    <label for="motdepasseuser">Mot de passe :</label>
                    <input type="password" id="motdepasseuser" name="motdepasseuser" class="form-control" required
                        minlength="8" placeholder="Saisissez un mot de passe sécurisé" oninput="checkPasswordStrength()">
                    <small>Votre mot de passe doit contenir au moins :</small>

                    <ul class="password-requirements">
                        <li><input type="checkbox" id="lengthCheck" disabled> 8 caractères minimum</li>
                        <li><input type="checkbox" id="uppercaseCheck" disabled> Une majuscule</li>
                        <li><input type="checkbox" id="numberCheck" disabled> Un chiffre</li>
                        <li><input type="checkbox" id="specialCharCheck" disabled> Un caractère spécial (;!?$#)</li>
                    </ul>

                    <div id="password-strength" class="mt-2"></div>
                </div>

                <div class="form-group">
                    <label for="motdepasseuser_confirmation">Confirmation du mot de passe :</label>
                    <input type="password" name="motdepasseuser_confirmation" id="motdepasseuser_confirmation"
                        class="form-control" required placeholder="Confirmez votre mot de passe">
                </div>
            </div>

            <!-- Consentement et CGU -->
            <div class="form-group">
                <label for="consentement_cgu">
                    En créant un compte Uber, vous acceptez les <a href="{{ route('cgu') }}" target="_blank">conditions
                        générales d'utilisation</a> et la
                    <a href="{{ route('privacy') }}" target="_blank">politique de confidentialité</a>.
                </label>
            </div>

            <!-- Notifications -->
            @if (session('success') || session('error'))
                <div class="alert-message @if (session('success')) success @elseif(session('error')) error @endif"
                    role="alert">
                    {{ session('success') ?? session('error') }}
                </div>
            @endif

            <!-- Actions -->
            <input type="hidden" name="role" value="responsable">

            <button type="submit" id="registerBtn" class="btn-login mt-2" disabled>S'inscrire</button>
        </form>
    </div>
@endsection

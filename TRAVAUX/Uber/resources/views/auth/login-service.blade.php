@extends('layouts.app')

@section('title', 'Connexion')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/js.js') }}"></script>
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Connexion Services</h1>

        <form method="POST" action="{{ route('auth') }}" class="form-login d-flex flex-column justify-content-center">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="form-control" placeholder="Entrez votre email">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" required class="form-control"
                    placeholder="Entrez votre mot de passe">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" required class="form-control">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Choisir un rôle</option>
                    <option value="rh" {{ old('role') === 'rh' ? 'selected' : '' }}>RH</option>
                    <option value="logistique" {{ old('role') === 'logistique' ? 'selected' : '' }}>Logistique</option>
                    <option value="administratif" {{ old('role') === 'administratif' ? 'selected' : '' }}>Administratif</option>
                    <option value="facturation" {{ old('role') === 'facturation' ? 'selected' : '' }}>Facturation</option>
                    <option value="course" {{ old('role') === 'course' ? 'selected' : '' }}>Course</option>
                    <option value="commande" {{ old('role') === 'commande' ? 'selected' : '' }}>Commande</option>
                    <option value="juridique" {{ old('role') === 'juridique' ? 'selected' : '' }}>Juridique</option>
                </select>
                @error('role')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            @if (session('success') || session('error'))
                <div class="alert-message @if (session('success')) success @elseif(session('error')) error @endif"
                    role="alert">
                    {{ session('success') ?? session('error') }}
                </div>
            @endif

            <button type="submit" class="btn-login">Connexion</button>

            <div class="text-center mt-3">
                <a href="{{ url('/interface-inscription') }}" class="login-link">Créer un compte</a>
            </div>
        </form>
    </div>
@endsection

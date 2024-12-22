@extends('layouts.app')

@section('title', 'Connexion')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Connexion</h1>
        <form method="POST" action="{{ route('auth') }}" class="d-flex flex-column justify-content-center">
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
                    <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                    <option value="coursier" {{ old('role') === 'coursier' ? 'selected' : '' }}>Coursier</option>
                </select>
                @error('role')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-login">Connexion</button>

            <div class="text-center mt-3">
                <a href="{{ route('register.form') }}" class="login-link">Créer un compte</a>
            </div>
        </form>
    </div>
@endsection

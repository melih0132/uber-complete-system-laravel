

@extends('layouts.app')

@section('title', 'Sécurité')

@section('css')

@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Sécurité</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('updatePassword') }}" class="securite-form">
            @csrf

            <div class="mb-3">
                <label for="current_password" class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password" id="current_password" required class="form-control" placeholder="Entrez votre mot de passe actuel">
                @error('current_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                <input type="password" name="new_password" id="new_password" required class="form-control" placeholder="Entrez votre nouveau mot de passe">
                @error('new_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Confirmez le nouveau mot de passe</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required class="form-control" placeholder="Confirmez votre nouveau mot de passe">
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour le mot de passe</button>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/security.js') }}"></script>
@endsection

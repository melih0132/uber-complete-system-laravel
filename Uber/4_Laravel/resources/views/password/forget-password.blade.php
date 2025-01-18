@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Fallait pas oublier</h1>

        <div class="text-center mt-3">
            <a href="{{ route('accueil') }}" class="login-link">Retourner à l'accueil</a>
        </div>
    </div>
@endsection

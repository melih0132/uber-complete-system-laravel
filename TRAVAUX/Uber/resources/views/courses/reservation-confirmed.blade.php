@extends('layouts.app')

@section('title', 'Réservation effectuée')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/note-pourboire.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="alert alert-success">
            {{ $message }}
        </div>
        <a href="{{ route('accueil') }}" class="btn btn-primary btn-recap text-decoration-none">Retour à l'accueil</a>
    </div>
@endsection

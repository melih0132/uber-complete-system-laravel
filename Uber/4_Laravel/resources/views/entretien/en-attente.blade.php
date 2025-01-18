@extends('layouts.app')

@section('title', 'Entretien')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="div-planifier p-4">
            <h1>Entretien en Attente</h1>

            <p>Votre entretien est actuellement en attente de validation.</p>
            <p>Vous avez été enregistré dans notre système. Un responsable RH vous contactera bientôt pour planifier un
                rendez-vous.</p>
            <a href="{{ route('myaccount') }}" class="btn-entretien text-decoration-none">Retour à mon compte</a>
        </div>
    </div>
@endsection

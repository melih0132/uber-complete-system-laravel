@extends('layouts.app')

@section('title', 'Entretien')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="div-planifier p-4">
            <h1>Entretien Annulé</h1>

            <p>Votre entretien a été annulé.</p>

            <strong>Date de l'entretien :</strong> {{ $entretien->dateentretien->format('d/m/Y H:i') }}<br>
            <strong>Statut :</strong> Annulé

            <div class="d-flex justify-content-center">
                <a href="{{ route('myaccount') }}" class="btn-entretien text-decoration-none">Retour à mon compte</a>
            </div>
        </div>
    </div>
@endsection

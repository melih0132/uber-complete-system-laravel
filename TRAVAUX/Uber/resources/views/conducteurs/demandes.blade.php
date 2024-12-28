@extends('layouts.app')

@section('title', 'Mes Demandes de Modifications')

@section('content')
    <div class="container">
        <h1>Demandes de Modifications pour le Coursier : {{ $coursier->nomuser }}</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($modifications->isNotEmpty())
            <table class="table">
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Demande</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modifications as $modification)
                        <tr>
                            <td>{{ $modification['modele'] }}</td>
                            <td>{{ $modification['demande'] }}</td>
                            <td>{{ $modification['date'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucune demande de modification enregistrée pour votre véhicule.</p>
        @endif

        <a href="{{ route('logistique.vehicules') }}" class="btn btn-primary mt-3">Retour</a>
    </div>
@endsection

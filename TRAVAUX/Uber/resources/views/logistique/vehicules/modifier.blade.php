@extends('layouts.app')

@section('title', 'Demande de Modification')

@section('content')
    <div class="container">
        <h1>Demande de Modification</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('logistique.vehicules.modifier', $coursier->idcoursier) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="modifications_demandees">Demande de modifications pour le véhicule :</label>
                <textarea name="modifications_demandees" id="modifications_demandees" rows="5" class="form-control" placeholder="Décrivez les modifications nécessaires" required></textarea>
            </div>

            <button type="submit" class="btn btn-warning mt-3">Envoyer la demande</button>
        </form>

        <a href="{{ route('logistique.vehicules') }}" class="btn btn-primary mt-3">Retour</a>
    </div>
@endsection

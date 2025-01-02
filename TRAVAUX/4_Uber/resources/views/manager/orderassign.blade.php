@extends('layouts.app')

@section('title', 'Assigner un chauffeur')

@section('content')
    <div class="container">
        <h1>Assigner un chauffeur pour la commande #{{ $commande->idcommande }}</h1>
        <form action="{{ route('order.assign.driver', $commande->idcommande) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="idcoursier">Sélectionner un chauffeur</label>
                <select id="idcoursier" name="idcoursier" class="form-control" required>
                    <option value="" disabled selected>-- Choisissez un chauffeur --</option>
                    @foreach ($coursiers as $coursier)
                        <option value="{{ $coursier->idcoursier }}">
                            {{ $coursier->prenom }} {{ $coursier->nom }} ({{ $coursier->telephone }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Assigner le chauffeur</button>
        </form>
    </div>
@endsection

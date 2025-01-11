@extends('layouts.app')

@section('title', 'Ajout Véhicule')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Sélectionner un coursier</h1>

        <form method="GET" action="{{ route('logistique.coursiers.select') }}" class="mb-4 mx-3">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control"
                    placeholder="Rechercher un coursier par nom ou ID" value="{{ request('search') }}"
                    aria-label="Rechercher un coursier">
                <button type="submit" class="btn-entretien mx-2">Rechercher</button>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col" class="text-center">ID</th>
                    <th scope="col" class="text-center">Nom</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coursiers as $coursier)
                    <tr>
                        <td class="text-center">{{ $coursier->idcoursier }}</td>
                        <td class="text-center">{{ $coursier->prenomuser }} {{ $coursier->nomuser }}</td>
                        <td class="text-center">
                            <a href="{{ route('logistique.vehicules.create', ['coursier' => $coursier->idcoursier]) }}"
                                class="btn-entretien text-decoration-none">Attribuer un véhicule</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Aucun coursier trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $coursiers->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Anonymisation | Uber')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1>Anonymisation des données des clients</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search Bar -->
        <form method="GET" action="{{ route('juridique.anonymisation') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Rechercher un client (par prénom, nom ou email)" value="{{ request('search') }}">
                <button type="submit" class="btn-uber mx-2">Rechercher</button>
            </div>
        </form>

        <!-- Client Table -->
        <form action="{{ route('juridique.anonymisation') }}" method="POST">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>Sélectionner</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Dernière date de connexion</th>
                        <th>Demande de suppression</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td>
                                <input type="checkbox" name="client_ids[]" value="{{ $client->idclient }}" />
                            </td>
                            <td>{{ $client->prenomuser }}</td>
                            <td>{{ $client->nomuser }}</td>
                            <td>{{ $client->emailuser }}</td>
                            <td>{{ $client->telephone }}</td>
                            <td>{{ $client->last_connexion ? $client->last_connexion->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>{{ $client->demande_suppression ? 'Oui' : 'Non' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Aucun client trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn-uber">Anonymiser les données sélectionnées</button>
                {{ $clients->links('pagination::bootstrap-4') }}
            </div>
        </form>
    </div>
@endsection

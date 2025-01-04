@extends('layouts.ubereats')

@section('title', 'Mes Commandes')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/commande.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Mes Commandes</h2>

        @if ($commandes->isEmpty())
            <div class="alert alert-info">
                <p>Vous n'avez pas encore passé de commande.</p>
            </div>
        @else
            <table class="table table-striped table-hover mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Commande</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Prix Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commandes as $commande)
                        <tr>
                            <td>{{ $commande->idcommande }}</td>
                            <td>{{ $commande->tempscommande ? $commande->tempscommande->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <span
                                    class="badge
                                    @if ($commande->statutcommande === 'En attente') badge-warning
                                    @elseif ($commande->statutcommande === 'Confirmée') badge-success
                                    @else badge-secondary @endif">
                                    {{ $commande->statutcommande }}
                                </span>
                            </td>
                            <td>{{ number_format($commande->prixcommande, 2, ',', ' ') }} €</td>
                            <td>
                                <a href="{{ route('commande.show', ['idcommande' => $commande->idcommande]) }}"
                                    class="btn btn-primary btn-sm">
                                    Voir détails
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

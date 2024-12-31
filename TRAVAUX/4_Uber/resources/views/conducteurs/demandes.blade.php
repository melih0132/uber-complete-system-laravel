@extends('layouts.app')

@section('title', 'Aménagements demandés')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="div-planifier">
            <h1 class="text-center">Demandes d'aménagement(s) :</h1>

            @if (!empty($vehicules) && count($vehicules) > 0)
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Véhicule</th>
                            <th>Demande</th>
                            <th>Effectuée ?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vehicules as $vehicule)
                            <tr>
                                <td>{{ $vehicule->marque ?? 'Non spécifié' }} {{ $vehicule->modele ?? 'Non spécifié' }}
                                    ({{ $vehicule->immatriculation ?? 'Non spécifiée' }})
                                </td>
                                <td>{{ $vehicule->demandemodification ?? 'Non spécifiée' }}</td>
                                <td>
                                    @if (!$vehicule->demandemodificationeffectue)
                                        <form action="{{ route('vehicules.completeModification', $vehicule->idvehicule) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-entretien mx-2">
                                                Marquer comme effectuée
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">Effectuée</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="p-coursier text-center">Aucune demande de modification enregistrée pour votre véhicule.</p>
            @endif
        </div>
    </div>
@endsection

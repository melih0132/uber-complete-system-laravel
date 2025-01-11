@extends('layouts.app')

@section('title', 'Mes Cartes Bancaires')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/myaccount.blade.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
    <div class="container my-5">
        <div class="account mt-5">

            <h1 class="mb-4 text-center">Mes Cartes Bancaires</h1>
            <div class="row">

                <div class="col-md-3">
                    <ul id="sidebar-menu" class="list-group shadow-sm">
                        <li class="list-item-flex rounded-0">
                            <a href="{{ url('/myaccount') }}" class="text-decoration-none d-flex align-items-center">
                                <i class="fas fa-user me-2"></i> Revenir sur le compte
                            </a>
                        </li>
                        <li class="list-group-item active">
                            <a href="{{ url('/carte-bancaire') }}" class="text-decoration-none d-flex align-items-center">
                                <i class="fas fa-credit-card me-2" aria-hidden="true"></i> Carte Bancaire
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="col-md-9">
                    @if ($cartes->isEmpty())
                        <div class="d-flex flex-column justify-content-center align-items-center text-center p-5">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <p class="text-muted" style="font-size: 1.2rem;">Aucune carte bancaire ajoutée pour l’instant.
                            </p>
                            <a href="{{ route('carte-bancaire.create') }}"
                                class="btn-compte text-decoration-none px-4 py-2">
                                <i class="fas fa-plus me-2"></i>Ajouter une carte bancaire
                            </a>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach ($cartes as $carte)
                                <div class="col-md-6">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body">
                                            <h5 class="card-title" style="font-size: 1rem; font-weight: bold;">
                                                Carte se terminant par <span class="text-dark">****
                                                    {{ substr($carte->numerocb, -4) }}</span>
                                            </h5>
                                            <p class="card-text text-muted mb-2" style="font-size: 0.9rem;">
                                                Expiration :
                                                {{ \Carbon\Carbon::parse($carte->dateexpirecb)->format('m/Y') }}
                                            </p>
                                            <p class="card-text">
                                                <span class="badge bg-light text-dark px-2 py-1"
                                                    style="font-size: 0.85rem;">
                                                    {{ ucfirst($carte->typecarte) }}
                                                </span>
                                                <span class="badge bg-dark text-white px-2 py-1"
                                                    style="font-size: 0.85rem;">
                                                    {{ ucfirst($carte->typereseaux) }}
                                                </span>
                                            </p>
                                            <form action="{{ route('carte-bancaire.destroy', $carte->id ?? $carte->idcb) }}"
                                                method="POST" class="sup-item"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn sup-icon" title="Supprimer cette carte">
                                                    <i class="fas fa-trash-alt fa-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-5">
                            <a href="{{ route('carte-bancaire.create') }}"
                                class="btn-compte text-decoration-none px-4 py-2">Ajouter une carte bancaire</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

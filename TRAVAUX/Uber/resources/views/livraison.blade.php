@extends('layouts.ubereats')

@section('title', 'Panier')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/panier.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <h2 class="text-center mt-5">Choisissez votre mode de livraison</h1>
        <div class="d-flex justify-content-center">

            <form method="POST" action="{{ route('mode.livraison') }}">
                @csrf
                <div class="my-2">
                    <button type="submit" name="mode" value="retrait" class="btn-livraison">
                        Retrait
                    </button>
                    <button type="submit" name="mode" value="livraison" class="btn-livraison">
                        Livraison
                    </button>
                </div>

                <!-- Afficher le champ d'adresse uniquement si "Livraison" est sélectionné -->
                @if(session('mode') == 'livraison')
                <div class="mt-3">
                    <label for="adresse_livraison">Adresse de livraison :</label>
                    <input type="text" id="adresse_livraison" name="adresse_livraison" class="form-control" placeholder="Entrez votre adresse">
                </div>
                @endif
            </form>
        </div>
        <div class="d-flex justify-content-center">
            <h2 class="text-center mt-5">Choisissez votre carte bancaire</h1>
            {{-- <div class="">
                @if ($cartes->isEmpty())
                    <div class="d-flex flex-column justify-content-center align-items-center text-center p-5">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <p class="text-muted" style="font-size: 1.2rem;">Aucune carte bancaire ajoutée pour l’instant.
                        </p>
                        <a href="{{ route('carte-bancaire.create') }}" class="btn-compte text-decoration-none px-4 py-2">
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
                                            <span class="badge bg-light text-dark px-2 py-1" style="font-size: 0.85rem;">
                                                {{ ucfirst($carte->typecarte) }}
                                            </span>
                                            <span class="badge bg-dark text-white px-2 py-1" style="font-size: 0.85rem;">
                                                {{ ucfirst($carte->typereseaux) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-5">
                        <a href="{{ route('carte-bancaire.create') }}"
                            class="btn-compte text-decoration-none px-4 py-2">Ajouter une carte
                            bancaire
                        </a>
                    </div>
                @endif
            </div> --}}

        </div>


    </div>
@endsection

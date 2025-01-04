@extends('layouts.ubereats')

@section('title', 'Panier')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/panier.blade.css') }}">
@endsection

@section('content')
    <h1 class="text-center mt-5">Votre Panier</h1>

    @if (count($produits) > 0)
        <div class="panier-detail">
            <div class="table-responsive">
                <table class="table text-center align-middle">
                    <thead>
                        <tr style="background-color: #f8f8f8; color: #333;">
                            <th>Produit</th>
                            <th>Prix Unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalPanier = 0; @endphp
                        @foreach ($produits as $produit)
                            @php
                                $totalProduit = $produit->prixproduit * $quantites[$produit->idproduit];
                                $totalPanier += $totalProduit;
                            @endphp
                            <tr>
                                <td>{{ $produit->nomproduit }}</td>

                                <td>{{ number_format($produit->prixproduit, 2) }} €</td>

                                <td>
                                    <form action="{{ route('panier.mettreAJour', $produit->idproduit) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="quantite" class="combobox" onchange="this.form.submit()">
                                            @for ($i = 1; $i <= 99; $i++)
                                                <option value="{{ $i }}"
                                                    {{ $quantites[$produit->idproduit] == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </form>
                                </td>

                                <td>{{ number_format($totalProduit, 2) }} €</td>

                                <td>
                                    <form action="{{ route('panier.supprimer', $produit->idproduit) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-panier">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        <tr style="background-color: #f8f8f8; font-weight: bold;">
                            <td colspan="3">Total</td>
                            <td colspan="2">{{ number_format($totalPanier, 2) }} €</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mx-3 mb-4 text-center">
                <form action="{{ route('panier.vider') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-panier mx-2">Vider le Panier</button>
                </form>
                <a href="{{ url()->previous() }}" class="btn-panier mx-2 text-decoration-none">Continuer vos achats</a>

                @php
                    $user = session('user');
                @endphp
                @if ($user)
                    {{-- <form action="{{ route('panier.commander') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-panier mx-2">Finaliser la commande</button>
                    </form> --}}
                    <a href="{{ url('/panier/livraison') }}" class="btn-panier mx-2 text-decoration-none">Finaliser la commande</a>
                @else
                    <a href="{{ url('/login') }}" class="btn-panier mx-2 text-decoration-none">Finaliser la commande</a>
                @endif
            </div>
        </div>
    @else
        <div class="text-center mt-5">
            <p class="text-muted">Votre panier est vide.</p>
        </div>
    @endif
@endsection

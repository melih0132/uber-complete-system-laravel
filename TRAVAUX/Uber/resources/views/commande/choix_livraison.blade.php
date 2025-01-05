@extends('layouts.ubereats')

@section('title', 'Choix Commande')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/panier.blade.css') }}">
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container">
        <h1>Choisissez votre mode de livraison</h1>
        <form method="POST" action="{{ route('commande.choixLivraisonStore') }}">
            @csrf
            <div class="radio-group">
                <label>
                    <input type="radio" name="modeLivraison" value="livraison" required>
                    Livraison à domicile
                </label>
                <label>
                    <input type="radio" name="modeLivraison" value="retrait">
                    Retrait sur place
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Continuer</button>
        </form>
    </div>
@endsection

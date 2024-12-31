@extends('layouts.app')

@section('title', 'Mon compte')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mon-compte.blade.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
    <div class="container my-5">
        <div class="account mt-5">
            <h1 class="mb-4 text-center">Mes lieux favoris</h1>
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <ul class="list-group shadow-sm">
                        <a href="{{ url('/mon-compte') }}" class="text-decoration-none">
                            <li class="list-group-item rounded-0">
                                <i class="fas fa-user me-2"></i> Informations sur le compte
                            </li>
                        </a>
                        <li class="list-group-item">
                            <i class="fas fa-taxi me-2"></i> Courses
                        </li>
                        <a href="{{ url('/carte-bancaire') }}" class="text-decoration-none">
                            <li class="list-group-item rounded-0">
                                <i class="fas fa-credit-card me-2"></i> Carte Bancaire
                            </li>
                        </a>
                        <a href="{{ url('/favoris') }}" class="text-decoration-none">
                            <li class="list-group-item active rounded-0">
                                <i class="fas fa-star me-2"></i> Lieux favoris
                            </li>
                        </a>
                        <li class="list-group-item">
                            <i class="fas fa-shield-alt me-2"></i> Sécurité
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-user-shield me-2"></i> Confidentialité et données
                        </li>
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="col-md-9">
                    <div class="mb-4">
                        <h4>Ajouter un lieu favori</h4>
                        <form action="{{ url('/favoris') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="lieu" class="form-label">Nom du lieu</label>
                                <input type="text" class="form-control w-25" id="lieu" name="lieu" placeholder="Entrez le nom d'un lieu favori" required>
                            </div>
                            <button type="submit" class="btn-compte">Ajouter</button>
                        </form>
                    </div>
                    <div id="savedPlaces" class="mt-4">
                        <h5>Lieux favoris :</h5>
                        <ul id="placesList" class="list-group">
                            @foreach ($places as $place)
                                <li class="list-group-item">{{ $place->lieu }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

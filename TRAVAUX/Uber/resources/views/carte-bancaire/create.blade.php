@extends('layouts.app')

@section('title', 'Ajouter une Carte Bancaire')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mon-compte.blade.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
    <div class="container my-5">
        <div class="account mt-5">

            <h1 class="mb-4 text-center">Ajouter une Carte Bancaire</h1>

            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <ul class="list-group shadow-sm">
                        <a href="{{ url('/mon-compte') }}" class="text-decoration-none">
                            <li class="list-group-item rounded-0">
                                <i class="fas fa-user me-2"></i> Informations sur le compte
                            </li>
                        </a>
                        <li class="list-group-item" data-target="content-courses">
                            <i class="fas fa-taxi me-2" aria-hidden="true"></i>Courses
                        </li>
                        <a href="{{ url('/carte-bancaire') }}" class="text-decoration-none">
                            <li class="list-group-item active rounded-0">
                                <i class="fas fa-credit-card me-2"></i> Carte Bancaire
                            </li>
                        </a>
                        <li class="list-item-flex rounded-0">
                            <a href="{{ url('/favoris') }}" class="text-decoration-none d-flex align-items-center">
                                <i class="fas fa-star me-2" aria-hidden="true"></i> Lieux favoris
                            </a>
                        </li>
                        <li class="list-group-item" data-target="content-securite">
                            <i class="fas fa-shield-alt me-2"></i>Sécurité
                        </li>
                        <li class="list-group-item" data-target="content-confidentialite">
                            <i class="fas fa-user-shield me-2"></i>Confidentialité et données
                        </li>
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="col-md-9">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="list-style: none; padding-left: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('carte-bancaire.store') }}" method="POST" class="px-5">
                        @csrf

                        <!-- Numéro de Carte -->
                        <div class="mb-3">
                            <label for="numerocb" class="form-label">Numéro de la carte</label>
                            <input type="text" id="numerocb" name="numerocb"
                                class="form-control @error('numerocb') is-invalid @enderror" value="{{ old('numerocb') }}"
                                placeholder="1234 5678 9012 3456" maxlength="16" required>
                            @error('numerocb')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date d'expiration -->
                        <div class="mb-3">
                            <label for="dateexpirecb" class="form-label">Date d'expiration</label>
                            <input type="month" id="dateexpirecb" name="dateexpirecb"
                                class="form-control @error('dateexpirecb') is-invalid @enderror"
                                value="{{ old('dateexpirecb') }}" required min="<?php echo date('Y-m'); ?>">
                            @error('dateexpirecb')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cryptogramme -->
                        <div class="mb-3">
                            <label for="cryptogramme" class="form-label">Cryptogramme (3 chiffres)</label>
                            <input type="text" id="cryptogramme" name="cryptogramme"
                                class="form-control @error('cryptogramme') is-invalid @enderror"
                                value="{{ old('cryptogramme') }}" placeholder="123" maxlength="3" required>
                            @error('cryptogramme')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de Carte -->
                        <div class="mb-3">
                            <label for="typecarte" class="form-label">Type de carte</label>
                            <select id="typecarte" name="typecarte"
                                class="form-select @error('typecarte') is-invalid @enderror" required>
                                <option value="" disabled selected>Choisissez le type</option>
                                <option value="Crédit" {{ old('typecarte') == 'Crédit' ? 'selected' : '' }}>Crédit</option>
                                <option value="Débit" {{ old('typecarte') == 'Débit' ? 'selected' : '' }}>Débit</option>
                            </select>
                            @error('typecarte')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de Réseau -->
                        <div class="mb-3">
                            <label for="typereseaux" class="form-label">Type de réseau</label>
                            <select id="typereseaux" name="typereseaux"
                                class="form-select @error('typereseaux') is-invalid @enderror" required>
                                <option value="" disabled selected>Choisissez le réseau</option>
                                <option value="Visa" {{ old('typereseaux') == 'Visa' ? 'selected' : '' }}>Visa</option>
                                <option value="MasterCard" {{ old('typereseaux') == 'MasterCard' ? 'selected' : '' }}>
                                    MasterCard</option>
                            </select>
                            @error('typereseaux')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4 text-center">
                            <button class="btn btn-success" type="submit">Ajouter la carte</button>
                            <a href="{{ route('carte-bancaire.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            🎉 {{ session('success') }}
        </div>
    @endif
@endsection

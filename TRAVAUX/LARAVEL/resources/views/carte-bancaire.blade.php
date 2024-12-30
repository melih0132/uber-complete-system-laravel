@extends('layouts.app')

@section('title', 'Mon compte')

@section('css')
    <!-- Include the external CSS file -->
    <link rel="stylesheet" href="{{ asset('css/carte-bancaire.blade.css') }}">
@endsection

@section('content')
    <div class="container my-5">
        <div class="div-cb">
            <h1 class="mb-4 text-center">Carte bancaire</h1>

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
                            <li class="list-group-item active rounded-0">
                                <i class="fas fa-credit-card me-2"></i> Carte Bancaire
                            </li>
                        </a>
                        <a href="{{ url('/favoris') }}" class="text-decoration-none">
                            <li class="list-group-item rounded-0">
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
                    <!-- Global Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="list-style: none; padding-left:0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('carte_bancaire.store') }}" method="POST" class="px-5">
                        @csrf

                        <!-- Card Number Field -->
                        <div>
                            <label for="numerocb">Numéro de la carte</label>
                            <input type="text" id="numerocb" name="numerocb" class="w-50" value="{{ old('numerocb') }}"
                                class="@error('numerocb') input-error @enderror" placeholder="Entrez le numéro de carte"
                                maxlength="16">
                        </div>

                        <!-- Expiration Date Field -->
                        <div>
                            <label for="dateexpirecb">Date d'expiration</label>
                            <input type="date" id="dateexpirecb" name="dateexpirecb" class="w-50"  value="{{ old('dateexpirecb') }}"
                                class="@error('dateexpirecb') input-error @enderror">
                        </div>

                        <!-- Cryptogram Field -->
                        <div>
                            <label for="cryptogramme">Cryptogramme (3 chiffres)</label>
                            <input type="text" id="cryptogramme" name="cryptogramme" class="w-50" value="{{ old('cryptogramme') }}"
                                class="@error('cryptogramme') input-error @enderror" placeholder="XXX" maxlength="3">
                        </div>

                        <!-- Card Type Field -->
                        <div>
                            <label for="typecarte">Type de carte</label>
                            <select id="typecarte" name="typecarte" class="@error('typecarte') input-error @enderror w-50">
                                <option value="Crédit" {{ old('typecarte') == 'Crédit' ? 'selected' : '' }}>Crédit</option>
                                <option value="Débit" {{ old('typecarte') == 'Débit' ? 'selected' : '' }}>Débit</option>
                            </select>
                        </div>

                        <!-- Network Type Field -->
                        <div>
                            <label for="typereseaux">Type de réseau</label>
                            <select id="typereseaux" name="typereseaux" class="@error('typereseaux') input-error @enderror w-50">
                                <option value="Visa" {{ old('typereseaux') == 'Visa' ? 'selected' : '' }}>Visa</option>
                                <option value="MasterCard" {{ old('typereseaux') == 'MasterCard' ? 'selected' : '' }}>
                                    MasterCard
                                </option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <button class="btn-cb" type="submit">Ajouter la carte</button>
                        </div>
                    </form>

                </div>
            </div>


        </div>

    </div>


    @if (session('success'))
        <div class="success-container">
            <p>🎉 Carte ajoutée avec succès : {{ session('success') }}</p>
        </div>
    @endif

@endsection

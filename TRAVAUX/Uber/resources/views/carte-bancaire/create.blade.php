@extends('layouts.app')

@section('title', 'Ajouter une Carte Bancaire')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/myaccount.blade.css') }}">
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
                                placeholder="1234 5678 9012 3456" maxlength="19" required
                                pattern="\d{4}\s\d{4}\s\d{4}\s\d{4}" inputmode="numeric">
                            <small>Exemple : 1234 5678 9012 3456</small>
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
                            <small>Format mm-aaaa</small>
                            @error('dateexpirecb')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cryptogramme -->
                        <div class="mb-3">
                            <label for="cryptogramme" class="form-label">Cryptogramme (3 chiffres)</label>
                            <input type="text" id="cryptogramme" name="cryptogramme"
                                class="form-control @error('cryptogramme') is-invalid @enderror"
                                value="{{ old('cryptogramme') }}" placeholder="123" maxlength="3" required pattern="\d{3}"
                                inputmode="numeric">
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

                        <div class="mt-4 text-center">
                            <button class="btn-cb" type="submit">Ajouter la carte</button>
                            <a href="{{ route('carte-bancaire.index') }}" class="btn-cb text-decoration-none">Annuler</a>
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

@section('js')
    <script>
        document.getElementById('numerocb').addEventListener('input', function(e) {
            // Supprime tous les espaces existants
            let input = e.target.value.replace(/\s+/g, '');
            // Regroupe les chiffres par groupes de 4
            e.target.value = input.match(/.{1,4}/g)?.join(' ') || input;
        });
    </script>
    <script>
        document.getElementById('numerocb').addEventListener('input', function(e) {
            let input = e.target.value.replace(/\D+/g, ''); // Supprime tout sauf les chiffres
            e.target.value = input.match(/.{1,4}/g)?.join(' ') || input; // Formate par groupes de 4 chiffres
        });

        document.getElementById('cryptogramme').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D+/g, ''); // Supprime tout sauf les chiffres
        });
    </script>
@endsection

@extends('layouts.ubereats')

@section('title', 'Choix de la Carte Bancaire')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/panier.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1>Choisissez une carte bancaire</h1>

        {{-- Affichage des messages de succès ou d'erreur --}}
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

        {{-- Contenu principal --}}
        @if ($cartes->isEmpty())
            <p class="empty-message">Aucune carte enregistrée. Veuillez ajouter une carte pour continuer.</p>
        @endif


        <form method="POST" action="{{ route('commande.enregistrer') }}" class="my-4">
            @csrf
            <div class="card-selection mt-4">
                @foreach ($cartes as $carte)
                    <label>
                        <input type="radio" id="carte_{{ $carte->idcb }}" name="carte_id" value="{{ $carte->idcb }}">
                        <span>
                            **** **** **** {{ substr($carte->numerocb, -4) }} - Exp.
                            {{ date('m/Y', strtotime($carte->dateexpirecb)) }}
                        </span>
                    </label>
                @endforeach
            </div>
            <button type="submit" class="btn-panier">Utiliser cette carte</button>
        </form>

        <a href="{{ route('carte-bancaire.create') }}" class="btn-panier text-decoration-none">Ajouter une nouvelle carte
            bancaire</a>
    </div>
@endsection

@section('js')
    <script>
        const radioInputs = document.querySelectorAll('input[name="carte_id"]');
        const newCardForm = document.getElementById('new-card-form');


        radioInputs.forEach(radio => {
            radio.addEventListener('change', () => {

                if (radio.checked) {
                    newCardForm.style.display = 'none';
                }
            });
        });


        if (!Array.from(radioInputs).some(radio => radio.checked)) {
            newCardForm.style.display = 'block';
        }

        const cardNumberInput = document.getElementById('numerocb');

        cardNumberInput.addEventListener('input', (event) => {
            let input = event.target.value;

            input = input.replace(/\s+/g, '');

            input = input.replace(/(\d{4})/g, '$1 ').trim();

            event.target.value = input;
        });
    </script>
@endsection

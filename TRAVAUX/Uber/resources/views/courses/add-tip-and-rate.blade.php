@extends('layouts.app')

@section('title', 'Note | Pourboire')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/note-pourboire.blade.css') }}">

@endsection

@section('content')
    <section>
        <div class="container">
            <div class="d-flex justify-content-center">
                <img src="/img/UberLogo.png" alt="Uber Logo" class="img-recap">
            </div>
            <h2 class="text-center mt-3">Merci d'avoir utilisé Uber !</h2>

            <div class="d-flex justify-content-center pt-3">
                <form action="{{ route('invoice.view', ['idreservation' => $idreservation]) }}" target="_blank"
                    method="POST" class="mx-3">
                    @csrf
                    <div class="rating-system mb-4">
                        <label for="note" class="form-label">Note de la course :</label>
                        <div class="star-rating pb-3 px-2">
                            <i class="fa fa-star" data-value="1"></i>
                            <i class="fa fa-star" data-value="2"></i>
                            <i class="fa fa-star" data-value="3"></i>
                            <i class="fa fa-star" data-value="4"></i>
                            <i class="fa fa-star" data-value="5"></i>
                        </div>
                        <input type='hidden' id="rating" name="notecourse" value="">
                    </div>

                    <div class="mb-4">
                        <label for="pourboire" class="form-label">Pourboire (optionnel) :</label>
                        <input type="number" id="pourboire" name="pourboire" class="form-control" step="0.1"
                            min="0.0" max="80" placeholder="0.0 €">
                    </div>

                    <div class="mb-4">
                        <label for="locale" class="form-label">Choisissez votre langue :</label>
                        <select name="locale" id="locale" class="form-control">
                            <option value="fr" {{ app()->getLocale() === 'fr' ? 'selected' : '' }}>Français</option>
                            <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                            <option value="pt" {{ app()->getLocale() === 'pt' ? 'selected' : '' }}>Português</option>
                            <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>العربية</option>
                            <option value="uk" {{ app()->getLocale() === 'uk' ? 'selected' : '' }}>Українська</option>
                            <option value="tr" {{ app()->getLocale() === 'tr' ? 'selected' : '' }}>Türkçe</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button type="submit" class="btn btn-secondary btn-recap">
                            <i class="fas fa-file-invoice"></i> Recevoir ma facture
                        </button>

                        <a href="{{ route('accueil') }}" class="btn btn-primary btn-recap text-decoration-none">
                            <i class="fas fa-home"></i> Retour à l'accueil
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </section>
    <script>
        document.querySelectorAll('.star-rating i').forEach(star => {
    star.addEventListener('click', function () {
        const rating = this.getAttribute('data-value');
        document.getElementById('rating').value = rating;


        document.querySelectorAll('.star-rating i').forEach(s => {
            s.classList.remove('selected');
            if (s.getAttribute('data-value') <= rating) {
                s.classList.add('selected');
            }
        });
    });
});
    </script>
@endsection

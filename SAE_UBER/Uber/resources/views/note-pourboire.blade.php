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
                <!-- Formulaire pour ajouter une note et un pourboire -->
                <form action="{{ route('course.addTipRate') }}" method="POST" class="mx-3">
                    @csrf
                    <input type="hidden" name="idreservation" value="{{ $course['idreservation'] }}">

                    <div class="rating-system mb-4">
                        <label for="note" class="form-label">Note de la course :</label>
                        <div class="star-rating pb-3 px-2">
                            <i class="fa fa-star" data-value="1"></i>
                            <i class="fa fa-star" data-value="2"></i>
                            <i class="fa fa-star" data-value="3"></i>
                            <i class="fa fa-star" data-value="4"></i>
                            <i class="fa fa-star" data-value="5"></i>
                        </div>
                        <input type="hidden" id="rating" name="notecourse" value="0">
                    </div>

                    <div class="mb-4">
                        <label for="pourboire" class="form-label">Pourboire (optionnel) :</label>
                        <input type="number" id="pourboire" name="pourboire" class="form-control" step="0.1"
                            min="0.0" max="80" placeholder="0.0 €">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-recap my-4">Envoyer la note et le
                            pourboire</button>
                    </div>
                </form>

                <!-- Formulaire pour recevoir la facture -->
                <form action="{{ route('invoice.view', ['idreservation' => $course['idreservation']]) }}" method="POST"
                    class="mx-3">
                    @csrf

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

                    <div class="text-center">
                        <button type="submit" class="btn btn-secondary btn-recap my-4">Recevoir ma facture</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

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
            <form action="{{ route('facture', ['idreservation' => $request->idreservation]) }}"  method="POST">
                @csrf
                <div class="rating-system">
                    <label for="rating">Note de la course :</label>
                    <div class="star-rating pb-3 px-2">
                        <i class="fa fa-star" data-value="1"></i>
                        <i class="fa fa-star" data-value="2"></i>
                        <i class="fa fa-star" data-value="3"></i>
                        <i class="fa fa-star" data-value="4"></i>
                        <i class="fa fa-star" data-value="5"></i>
                    </div>
                    <input type="hidden" id="rating" name="rating" value="0">
                </div>

                <label for="tip">Pourboire (optionnel) :</label>
                <input type="number" name="tip" step="0.1" min="0.0" max="80" placeholder="0.0 €">

                <button type="submit" class="btn-recap my-4">Recevoir ma facture</button>
            </form>
        </div>
    </div>

</section>


@endsection

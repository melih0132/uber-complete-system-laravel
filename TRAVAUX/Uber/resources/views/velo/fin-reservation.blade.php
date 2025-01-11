@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Merci pour votre réservation !</h1>


        <a href="{{ route('velo.index') }}" class="btn btn-secondary mt-4">Retour</a>
    </div>
@endsection

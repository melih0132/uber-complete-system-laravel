@extends('layouts.app')

@section('title', 'Details')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
    <section>
        <div class="container">
            <h1 class="text-decoration-underline">Détails de la réservation</h1>
            <div>
                <ul class="liste">
                    <li><img alt="Courses" class="img-prestation" src="../img/{{ $course['imageprestation'] }}"></li>
                    <li><strong>Adresse de départ :</strong> {{ $course['startAddress'] }}</li>
                    <li><strong>Adresse d'arrivée :</strong> {{ $course['endAddress'] }}</li>
                    <li><strong>Date de la course :</strong> {{ $course['tripDate'] }}</li>
                    <li><strong>Heure de la course :</strong> {{ $course['tripTime'] }}</li>
                    <li><strong>Nom du client :</strong> Monsieur Jean DUPONT</li>
                    <li><strong>Prix de la course :</strong> {{ $course['calculated_price'] }} €</li>
                    <li><strong>Distance :</strong> {{ $course['distance'] }} km</li>
                    <li><strong>Temps estimé :</strong> {{ $course['adjusted_time'] }} minutes</li>
                </ul>
            </div>

            <div class="d-flex justify-content-center">
                {{-- @if ($newCourse)
                    <form method="POST" class="mx-2" action="{{ route('course.rateInvoice') }}">
                        @csrf
                        <input type="hidden" name="course" value="{{ json_encode($course) }}">
                        <button type="submit" class="btn-annuler">Terminer Course</button>
                    </form>

                    <form method="POST" class="mx-2">
                        @csrf
                        <input type="hidden" name="course" value="{{ json_encode($course) }}">
                        <button type="submit" class="btn-annuler"
                            onclick="window.history.back(); return false;">ANNULER</button>
                    </form>
                @else --}}
                <form method="POST" class="mx-2" action="{{ route('course.validate') }}">
                    @csrf
                    <input type="hidden" name="course" value="{{ json_encode($course) }}">
                    <button type="submit" class="btn-annuler">VALIDER</button>
                </form>

                <form method="POST" class="mx-2">
                    @csrf
                    <button type="submit" class="btn-annuler"
                        onclick="window.history.back(); return false;">ANNULER</button>
                </form>
                {{-- @endif --}}
            </div>
        </div>
    </section>
@endsection

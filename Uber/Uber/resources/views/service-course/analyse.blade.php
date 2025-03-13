@extends('layouts.app')

@section('title', 'Analyses et Performances')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/serviceCourse.css') }}">
@endsection

@section('content')
    <h1>Analyses et Performances</h1>
    <h2>Statistiques</h2>
    <div class="buttons-container">
        <span><a href="{{ route('serviceCourse.statistiquesCourses') }}" class="uber-btn text-decoration-none">Course</a></span>
        <span><a href="{{ route('serviceCourse.statistiquesMontants') }}" class="uber-btn text-decoration-none">Montant</a></span>
        <span><a href="{{ route('serviceCourse.statistiquesPrestations') }}" class="uber-btn text-decoration-none">Vehicule</a></span>
        <span><a href="{{ route('serviceCourse.statistiquesGeo') }}" class="uber-btn text-decoration-none">Géographique</a></span>
    </div>

@endsection

@extends('layouts.app')

@section('title', 'Uber Velo')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/accueil-uber.blade.css') }}">
@endsection

@section('content')
<div class="row p-4">
    <div class="col-12 col-sm-6">
        <h1>Allez où vous voulez avec Uber</h1>
        <form action="{{ route('course.index') }}" method="POST">
            @csrf
            <!-- Adresse de départ -->
            <input type="text" id="startAddress" name="startAddress" placeholder="Adresse de départ"
                value="{{ old('startAddress', $startAddress ?? '') }}" oninput="fetchSuggestions(this, 'startSuggestions')"
                required>
            <ul id="startSuggestions" class="suggestions-list"></ul>

            <!-- Adresse d'arrivée -->
            <input type="text" id="endAddress" name="endAddress" class="mt-3" placeholder="Adresse d'arrivée"
                value="{{ old('endAddress', $endAddress ?? '') }}" oninput="fetchSuggestions(this, 'endSuggestions')"
                required>
            <ul id="endSuggestions" class="suggestions-list"></ul>

            <div class="date-container">
                <div class="date-time-container mt-3 mr-3" onclick="document.getElementById('tripDate').showPicker()">
                    <label id="tripDateLabel" data-icon="📅" class="mr-1">
                        {{ old('tripDate', isset($tripDate) ? \Carbon\Carbon::parse($tripDate)->translatedFormat('d F Y') : 'Aujourd\'hui') }}
                    </label>
                    <input type="date" id="tripDate" name="tripDate"
                        value="{{ old('tripDate', $tripDate ?? date('Y-m-d')) }}" onchange="updateDateLabel()">
                </div>

                <div id="customTimePicker" class="date-time-container mt-3">
                    <label id="tripTimeLabel" data-icon="⏰">
                        {{ old('tripTime', isset($tripTime) ? $tripTime : 'Maintenant') }}
                    </label>
                    <input type="hidden" id="tripTime" name="tripTime" value="{{ old('tripTime', $tripTime ?? '') }}">
                    <ul id="customTimeDropdown" class="dropdown-list"></ul>
                </div>

            </div>

            <!-- Distance -->
            <div id="distanceResult" class="mt-3"></div>

            <!-- Calculer l'itinéraire -->

            @if (session('user') && session('user.role') === 'client')
                <button type="submit" class="mt-4" onclick="voirPrix();">Voir les prestations</button>
            @else
                <a href="{{ url('/login') }}" class="mt-4">Voir les prestations</a>
            @endif
        </form>
    </div>
    <div class="col-12 col-sm-6">
        <img alt="Course" class="img-fluid w-100" src="img/uber-velo.png">
    </div>
</div>
@endsection

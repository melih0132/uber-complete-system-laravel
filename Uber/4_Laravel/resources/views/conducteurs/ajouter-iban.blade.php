@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm p-4 mt-4">
            <h2 class="mb-4"><i class="fas fa-university"></i> Saisir votre IBAN</h2>

            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <p class="text-muted">
                Bonjour <strong>{{ $coursier->nomuser }} {{ $coursier->prenomuser }}</strong>, veuillez entrer votre IBAN
                pour
                finaliser votre enregistrement.
            </p>

            <form method="POST" action="{{ route('coursier.ajouter.iban') }}" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label for="iban" class="form-label"><i class="fas fa-credit-card"></i> Votre IBAN</label>
                    <input type="text" id="iban" name="iban" class="form-control"
                        value="{{ old('iban', $coursier->iban) }}" placeholder="FR76 1234 5678 9012 3456 7890 123" required>
                    @error('iban')
                        <div class="text-danger mt-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save"></i> Enregistrer mon IBAN
                </button>
            </form>
        </div>
    </div>
@endsection

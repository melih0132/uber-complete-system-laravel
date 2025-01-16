@extends('layouts.app')

@section('title', 'OTP')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/js.js') }}"></script>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Saisir le Code OTP</div>
                    <div class="card-body">
                        @if (session('info'))
                            <div class="alert alert-info">{{ session('info') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verifyOtp') }}">
                            @csrf
                            <div class="form-group">
                                <label for="codeotp">Code OTP</label>
                                <input type="text" name="codeotp" id="codeotp" class="form-control" required>
                            </div>
                            <button type="submit" class="btn-login mt-3">Vérifier</button>
                        </form>

                        <form method="POST" action="{{ route('resendOtp') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn-login">Renvoyer le Code OTP</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

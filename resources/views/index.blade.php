@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-6">

        <h1 class="mb-4 text-center">Welcome to the Transport App</h1>

        <div class="card mb-4">
            <div class="card-header">Adatbázis Felépítése</div>
            <div class="card-body">
                <p>Ide kattintva inicializálhatja az adatbázist</p>
                <form action="{{ route('setup.database') }}" method="POST">
                    @csrf
                    <button class="btn btn-primary w-100">Adatbázis Felépítése</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Bejelentkezés</div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Email:</label>
                        <input type="email" name="email" required class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Jelszó:</label>
                        <input type="password" name="password" required class="form-control">
                    </div>

                    <button class="btn btn-success w-100">Bejelentkezés</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Új Fuvarozó?</div>
            <div class="card-body">
                <a href="#" class="btn btn-secondary w-100 disabled">Regisztráció (Hamarosan)</a>
            </div>
        </div>

    </div>
</div>
@endsection

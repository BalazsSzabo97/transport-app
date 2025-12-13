@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-center" style="padding-top: 10px;">
        <div class="col-md-6">

            <h1 class="mb-4 text-center">Üdvözöljük a Fuvarozó Appban</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

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

                    @if ($errors->register->any())
                        <div class="alert alert-danger">
                            {{ $errors->register->first() }}
                        </div>
                    @endif

                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Teljes név:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Jelszó:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Jelszó megerősítése:</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-secondary w-100">Regisztráció</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('content')

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">API hozzáférés</h5>

            <p class="mb-2">API tokened aktív. Csak a hozzád rendelt munkákat érinti.</p>

            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#apiTokenModal">
                <i class="bi bi-eye-fill me-1"></i> Token megtekintése
            </button>

            <div class="modal fade" id="apiTokenModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title">API token</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                Ne oszd meg ezt a tokent másokkal!
                            </div>
                            <pre class="bg-light p-2 rounded">{{ auth('driver')->user()->token }}</pre>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($driver->vehicle)
        <div class="card mb-4">
            <div class="card-header">Regisztrált Jármű</div>
            <div class="card-body">
                <p><strong>Márka:</strong> {{ $driver->vehicle->brand }}</p>
                <p><strong>Típus:</strong> {{ $driver->vehicle->type }}</p>
                <p><strong>Rendszám:</strong> {{ $driver->vehicle->plate }}</p>
            </div>
        </div>
    @else
        <div class="card mb-4">
            <div class="card-header">Jármű Regisztrálása</div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('driver.vehicle.register') }}">
                    @csrf
                    <div class="mb-3">
                        <label>Márka:</label>
                        <input type="text" name="brand" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Típus:</label>
                        <input type="text" name="type" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Rendszám:</label>
                        <input type="text" name="plate" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Regisztrálás</button>
                </form>
            </div>
        </div>
    @endif


    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h1 class="mb-4">Fuvarozói Felület</h1>

    <h3>Kiosztott munkák</h3>

    @if($jobs->isEmpty())
        <div class="alert alert-info">
            Jelenleg nincs kiosztott munkája.
        </div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kiindulás</th>
                    <th>Érkezés</th>
                    <th>Címzett</th>
                    <th>Telefon</th>
                    <th>Státusz</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobs as $job)
                    <tr>
                        <td>{{ $job->from_address }}</td>
                        <td>{{ $job->to_address }}</td>
                        <td>{{ $job->recipient_name }}</td>
                        <td>{{ $job->recipient_phone }}</td>
                        <td>
                            <form method="POST" action="{{ route('driver.jobs.updateStatus', $job->id) }}">
                                @csrf
                                @method('PATCH')

                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">

                                    <option value="assigned" {{ $job->status === 'assigned' ? 'selected' : '' }}>
                                        Kiosztva
                                    </option>
                                    <option value="in_progress" {{ $job->status === 'in_progress' ? 'selected' : '' }}>
                                        Folyamatban
                                    </option>
                                    <option value="completed" {{ $job->status === 'completed' ? 'selected' : '' }}>
                                        Elvégezve
                                    </option>
                                    <option value="failed" {{ $job->status === 'failed' ? 'selected' : '' }}>
                                        Sikertelen
                                    </option>
                                </select>

                                <noscript>
                                    <button class="btn btn-sm btn-primary mt-1">Mentés</button>
                                </noscript>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
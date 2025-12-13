@extends('layouts.app')

@section('content')

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="mb-4">Adminisztrációs Felület</h1>

    <div class="d-flex justify-content-between mb-3 align-items-center">
        <h3>Munkák</h3>

        <div class="d-flex gap-2 align-items-center">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 mb-0">
                <label class="fw-bold mb-0">Státusz:</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Összes</option>
                    <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Kiosztva</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Folyamatban
                    </option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Elvégezve</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Sikertelen</option>
                </select>
                <noscript><button type="submit" class="btn btn-sm btn-primary">Szűrés</button></noscript>
            </form>

            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createJobModal">
                Új Munka Létrehozása
            </a>
        </div>
    </div>


    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kiindulási Cím</th>
                <th>Érkezési Cím</th>
                <th>Címzett</th>
                <th>Telefon</th>
                <th>Státusz</th>
                <th>Fuvarozó</th>
                <th>Műveletek</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($jobs as $job)
                <tr class="
                                        {{ !$job->driver_id ? 'table-warning' : '' }}
                                        {{ $job->status === 'failed' ? 'table-danger' : '' }}">
                    <td>{{ $job->id }}</td>
                    <td>{{ $job->from_address }}</td>
                    <td>{{ $job->to_address }}</td>
                    <td>{{ $job->recipient_name }}</td>
                    <td>{{ $job->recipient_phone }}</td>

                    <td>
                        <span class="status-badge 
                                            {{ $job->status === 'Kiosztva' ? 'status-new' : '' }}
                                            {{ $job->status === 'Folyamatban' ? 'status-in-progress' : '' }}
                                            {{ $job->status === 'Elvégezve' ? 'status-completed' : '' }}
                                            {{ $job->status === 'Sikertelen' ? 'status-failed' : '' }}">
                            {{ $statusLabels[$job->status] ?? $job->status }}
                        </span>
                    </td>

                    <td>
                        @if ($job->driver)
                            {{ $job->driver->name }}
                        @else
                            <strong>Nincs Kiosztva</strong>
                        @endif
                    </td>

                    <td>
                        @if(!$job->driver)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#assignJobModal{{ $job->id }}">
                                Kiosztás
                            </button>
                        @endif

                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editJobModal{{ $job->id }}">
                            Szerkesztés
                        </button>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @foreach ($jobs as $job)
        <div class="modal fade" id="assignJobModal{{ $job->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form method="POST" action="{{ route('admin.jobs.assign', $job->id) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Munka Kiosztása</h5>
                        </div>

                        <div class="modal-body">
                            <label>Válasszon Fuvarozót:</label>
                            <select name="driver_id" class="form-control" required>
                                <option value="">-- Válasszon --</option>
                                @foreach ($confirmedDrivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                            <button class="btn btn-primary">Kiosztás</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="modal fade" id="editJobModal{{ $job->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form method="POST" action="{{ route('admin.jobs.update', $job->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="modal-header">
                            <h5 class="modal-title">Munka Szerkesztése</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Kiindulási cím:</label>
                                <input type="text" name="from_address" class="form-control" value="{{ $job->from_address }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Érkezési cím:</label>
                                <input type="text" name="to_address" class="form-control" value="{{ $job->to_address }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Címzett neve:</label>
                                <input type="text" name="recipient_name" class="form-control" value="{{ $job->recipient_name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Címzett telefonszáma:</label>
                                <input type="text" name="recipient_phone" class="form-control"
                                    value="{{ $job->recipient_phone }}" required>
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">Mentés</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                            </div>
                        </div>
                    </form>

                    <div class="modal-footer">
                        <form method="POST" action="{{ route('admin.jobs.delete', $job->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Biztosan törölni szeretné ezt a munkát?')">
                                Törlés
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="createJobModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form method="POST" action="{{ route('admin.jobs.create') }}">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Új Munka Létrehozása</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Kiindulási cím:</label>
                                <input type="text" name="from_address" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Érkezési cím:</label>
                                <input type="text" name="to_address" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Címzett neve:</label>
                                <input type="text" name="recipient_name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Címzett telefonszáma:</label>
                                <input type="text" name="recipient_phone" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                            <button type="submit" class="btn btn-primary">Létrehozás</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    <h3 class="mt-5">Fuvarozók</h3>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Email</th>
                <th>Regisztráció Megerősítve</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drivers as $driver)
                <tr>
                    <td>{{ $driver->id }}</td>
                    <td>{{ $driver->name }}</td>
                    <td>{{ $driver->email }}</td>
                    <td>
                        @if($driver->is_confirmed)
                            <span class="badge bg-success">Igen</span>
                        @else
                            <span class="badge bg-warning">Nem</span>
                        @endif
                    </td>
                    <td>
                        @if(!$driver->is_confirmed)
                            <form method="POST" action="{{ route('admin.drivers.confirm', $driver->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Megerősítés
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Fuel Management</h3>
                    <p class="text-muted mb-0">Manage available fuel types</p>
                </div>
                <div>
                    <a href="{{ route('fuels.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Fuel
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('fuels.index') }}" class="row g-2 align-items-center mb-3">
                <div class="col-md-4 col-sm-6">
                    <input type="text" name="search" class="form-control" placeholder="Search fuel..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3 col-sm-4">
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        @foreach ([10, 20, 30, 100, 1000] as $size)
                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
                @if (request('search') || request('per_page'))
                    <div class="col-md-2">
                        <a href="{{ route('fuels.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                @endif
            </form>

            <div class="mb-2 text-muted small">
                Showing {{ $fuels->firstItem() }}–{{ $fuels->lastItem() }} of {{ $totalFuels }} fuels
            </div>


            <!-- Desktop Table -->
            <div class="card d-none d-lg-block mt-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Fuel Type</th>
                                    <th>Created By</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fuels as $fuel)
                                    <tr>
                                        <td>{{ $fuels->firstItem() + $loop->index }}</td>
                                        <td>{!! $search ? str_ireplace($search, '<mark>' . $search . '</mark>', e($fuel->fuel_type)) : e($fuel->fuel_type) !!} @if ($fuel->created_at->gt(now()->subDay()))
                                                <span class="badge bg-success">New</span>
                                            @endif
                                        </td>
                                        <td>{{ $fuel->creator->name ?? '-' }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-dark" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item text-primary"
                                                            href="{{ route('fuels.edit', $fuel->id) }}">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('fuels.destroy', $fuel->id) }}"
                                                            method="POST" onsubmit="return confirmDelete(event)">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash-alt me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <img src="{{ asset('assets/img/empty-box.png') }}" alt="Empty state"
                                                    style="height: 120px; opacity: 0.7;" class="mb-3">
                                                <h5 class="text-muted">No Fuels Found</h5>
                                                <p class="text-muted mb-3">You haven't added any fuel types yet</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
                    {{ $fuels->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <!-- Mobile Card List -->
            <div class="d-lg-none mt-3">
                @forelse ($fuels as $fuel)
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6 class="fw-bold mb-1">{!! $search ? str_ireplace($search, '<mark>' . $search . '</mark>', e($fuel->fuel_type)) : e($fuel->fuel_type) !!}</h6>
                            <p class="text-muted mb-1">Created by: {{ $fuel->creator->name ?? '-' }}</p>
                            <div class="dropdown float-end">
                                <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item text-primary" href="{{ route('fuels.edit', $fuel->id) }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('fuels.destroy', $fuel->id) }}" method="POST"
                                            onsubmit="return confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash-alt me-1"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <img src="{{ asset('assets/img/empty-box.png') }}" alt="Empty state"
                                    style="height: 100px; opacity: 0.7;" class="mb-3">
                                <h5 class="text-muted">No Fuels Available</h5>
                                <p class="text-muted mb-3">Get started by adding a new fuel type</p>
                            </div>
                        </div>
                    </div>
                @endforelse
                <div class="mt-3">
                    {{ $fuels->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert Confirmation -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if (session('success'))
            showAlert('success', '{{ session('success') }}');
        @endif
        @if (session('error'))
            showAlert('error', '{{ session('error') }}');
        @endif
        @if ($errors->any())
            showAlert('error', '{{ $errors->first() }}');
        @endif

        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            const alertEl = document.createElement('div');

            alertEl.id = alertId;
            alertEl.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
            alertEl.role = 'alert';
            alertEl.style.cssText = `
                position: relative;
                overflow: hidden;
                border: none;
                border-left: 4px solid ${type === 'success' ? '#28a745' : '#dc3545'};
                animation: slideIn 0.3s ease-out forwards;
                margin-bottom: 10px;
            `;

            let icon = type === 'success' ?
                '<i class="fas fa-check-circle me-2"></i>' :
                '<i class="fas fa-exclamation-circle me-2"></i>';

            alertEl.innerHTML = `
                <div class="d-flex align-items-center">
                    <div style="font-size: 1.5rem; color: ${type === 'success' ? '#28a745' : '#dc3545'};">
                        ${icon}
                    </div>
                    <div><strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            alertContainer.appendChild(alertEl);

            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.style.animation = 'fadeOut 0.3s ease-out forwards';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }

        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    popup: 'animated bounceIn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes fadeOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection

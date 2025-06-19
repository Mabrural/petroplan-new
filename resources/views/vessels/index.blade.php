@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Vessel Management</h3>
                    <p class="text-muted mb-0">Manage registered vessels</p>
                </div>
                <div>
                    <a href="{{ route('vessels.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Vessel
                    </a>
                </div>
            </div>
            <!-- Filter and Tools -->
            <form method="GET" action="{{ route('vessels.index') }}" class="row g-2 align-items-center mb-3">
                <div class="col-md-4 col-sm-6">
                    <input type="text" name="search" class="form-control" placeholder="Search vessel..."
                        value="{{ request('search') }}" autofocus>
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
                        <a href="{{ route('vessels.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                @endif
            </form>

            <div class="mb-2 text-muted small">
                Showing {{ $vessels->firstItem() }}–{{ $vessels->lastItem() }} of {{ $totalVessels }} vessels
            </div>

            <!-- Desktop Table -->
            <div class="card d-none d-lg-block mt-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Vessel Name</th>
                                    <th>Image</th>
                                    <th>Created By/At</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vessels as $vessel)
                                    <tr>
                                        <td>{{ $vessels->firstItem() + $loop->index }}</td>
                                        <td>{!! request('search')
                                            ? str_ireplace(request('search'), '<mark>' . request('search') . '</mark>', e($vessel->vessel_name))
                                            : e($vessel->vessel_name) !!}</td>
                                        <td>
                                            @if ($vessel->image)
                                                <img src="{{ url('storage/' . $vessel->image) }}" alt="Vessel Image"
                                                    style="height: 40px;">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $vessel->creator->name ?? '-' }}</div>
                                            <small
                                                class="text-muted">{{ $vessel->created_at ? $vessel->created_at->format('d M Y H:i') : '-' }}</small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-dark" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item text-primary"
                                                            href="{{ route('vessels.edit', $vessel->id) }}">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('vessels.destroy', $vessel->id) }}"
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
                                        <td colspan="5" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <img src="{{ asset('assets/img/empty-box.png') }}" alt="Empty state"
                                                    style="height: 120px; opacity: 0.7;" class="mb-3">
                                                <h5 class="text-muted">No Vessels Found</h5>
                                                <p class="text-muted mb-3">You haven't added any vessels yet</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="p-3">
                    {{ $vessels->links('pagination::bootstrap-5') }}
                </div>
            </div>


            <!-- Mobile Card List -->
            <div class="d-lg-none mt-3">
                @forelse ($vessels as $vessel)
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6 class="fw-bold mb-1">{!! request('search')
                                ? str_ireplace(request('search'), '<mark>' . request('search') . '</mark>', e($vessel->vessel_name))
                                : e($vessel->vessel_name) !!}</h6>
                            <p class="text-muted mb-0 mt-2">Created by: </p>
                            <div class="fw-bold">{{ $vessel->creator->name ?? '-' }}</div>
                                            <small
                                                class="text-muted">{{ $vessel->created_at ? $vessel->created_at->format('d M Y H:i') : '-' }}</small>
                            @if ($vessel->image)
                                <img src="{{ asset('storage/' . $vessel->image) }}" alt="Vessel Image"
                                    style="height: 100px;" class="my-2">
                            @endif
                            <div class="dropdown float-end">
                                <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item text-primary"
                                            href="{{ route('vessels.edit', $vessel->id) }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('vessels.destroy', $vessel->id) }}" method="POST"
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
                                <h5 class="text-muted">No Vessels Available</h5>
                                <p class="text-muted mb-3">Get started by adding a new vessel</p>
                            </div>
                        </div>
                    </div>
                @endforelse
                <div class="mt-3">
                    {{ $vessels->links('pagination::bootstrap-5') }}
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

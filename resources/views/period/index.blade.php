@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <!-- Alert Notification Container -->
            <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Period Management</h3>
                    <p class="text-muted mb-0">Manage active system periods</p>
                </div>
                <div>
                    <a href="{{ route('period-list.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Period
                    </a>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="card d-none d-lg-block mt-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Period Name</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                    <th>Created By/At</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($periodes as $periode)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $periode->name }}</td>
                                        <td>{{ $periode->year }}</td>
                                        <td>
                                            <span class="badge bg-{{ $periode->is_active ? 'success' : 'secondary' }}">
                                                {{ $periode->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $periode->creator->name ?? '-' }}</div>
                                            <small
                                                class="text-muted">{{ $periode->created_at ? $periode->created_at->format('d M Y H:i') : '-' }}</small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-dark" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item text-primary"
                                                            href="{{ route('period-list.edit', $periode->id) }}">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        @if ($periode->is_active)
                                                            <form
                                                                action="{{ route('period-list.deactivate', $periode->id) }}"
                                                                method="POST" class="deactivate-form">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-warning">
                                                                    <i class="fas fa-ban me-1"></i> Deactivate
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form
                                                                action="{{ route('period-list.activate', $periode->id) }}"
                                                                method="POST" class="activate-form">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="fas fa-check-circle me-1"></i> Activate
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </li>

                                                    <li>
                                                        <form action="{{ route('period-list.destroy', $periode->id) }}"
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
                                        <td colspan="6" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <img src="{{ asset('assets/img/empty-box.png') }}" alt="Empty state"
                                                    style="height: 120px; opacity: 0.7;" class="mb-3">
                                                <h5 class="text-muted">No Periods Found</h5>
                                                <p class="text-muted mb-3">You haven't created any periods yet</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Mobile Card List -->
            <div class="d-lg-none mt-3">
                @forelse ($periodes as $periode)
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">{{ $periode->name }} ({{ $periode->year }})</h6>
                            <p class="text-muted mb-0">Created by/at: </p>
                            <div class="fw-bold">{{ $periode->creator->name ?? '-' }}</div>
                            <small
                                class="text-muted">{{ $periode->created_at ? $periode->created_at->format('d M Y H:i') : '-' }}</small><br>
                            <span class="badge bg-{{ $periode->is_active ? 'success' : 'secondary' }}">
                                {{ $periode->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <div class="dropdown float-end">
                                <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item text-primary"
                                            href="{{ route('period-list.edit', $periode->id) }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        @if ($periode->is_active)
                                            <form action="{{ route('period-list.deactivate', $periode->id) }}"
                                                method="POST" class="deactivate-form">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-warning">
                                                    <i class="fas fa-ban me-1"></i> Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('period-list.activate', $periode->id) }}" method="POST"
                                                class="activate-form">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="fas fa-check-circle me-1"></i> Activate
                                                </button>
                                            </form>
                                        @endif
                                    </li>
                                    <li>
                                        <form action="{{ route('period-list.destroy', $periode->id) }}" method="POST"
                                            onsubmit="return confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash me-2"></i> Delete
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
                                <h5 class="text-muted">No Periods Available</h5>
                                <p class="text-muted mb-3">Get started by creating a new period</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Rest of your existing JavaScript code remains the same -->
    <!-- SweetAlert for Confirmation -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Show alerts from session
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
                border-left: 4px solid ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#ffc107'};
                animation: slideIn 0.3s ease-out forwards;
                margin-bottom: 10px;
            `;

            let icon = '';
            if (type === 'success') {
                icon = '<i class="fas fa-check-circle me-2"></i>';
            } else if (type === 'error') {
                icon = '<i class="fas fa-exclamation-circle me-2"></i>';
            } else {
                icon = '<i class="fas fa-info-circle me-2"></i>';
            }

            alertEl.innerHTML = `
                <div class="d-flex align-items-center">
                    <div style="font-size: 1.5rem; color: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#ffc107'};">
                        ${icon}
                    </div>
                    <div>
                        <strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem;"></button>
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
            .alert {
                transition: all 0.3s ease;
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection

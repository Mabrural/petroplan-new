@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <!-- Alert Notification Container -->
            <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">User Management</h3>
                    <p class="text-muted mb-0">Manage all system users</p>
                </div>
                <div>
                    <a href="{{ route('user-management.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add User
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->is_admin ? 'primary' : 'secondary' }}">
                                                {{ $user->is_admin ? 'Administrator' : 'General' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->is_active ? 'success' : 'warning' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item text-primary" href="{{ route('user-management.edit', $user->slug) }}">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        @if ($user->is_admin)
                                                            <form action="{{ route('user-management.revoke-admin', $user->slug) }}" method="POST" class="revoke-form">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-warning">
                                                                    <i class="fas fa-user-slash me-1"></i> Revoke Admin
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('user-management.grant-admin', $user->slug) }}" method="POST" class="grant-form">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="fas fa-user-shield me-1"></i> Grant Admin
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </li>
                                                    <li>
                                                        @if ($user->is_active)
                                                            <form action="{{ route('user-management.deactivate', $user->slug) }}" method="POST" class="deactivate-form">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-warning">
                                                                    <i class="fas fa-user-times me-1"></i> Deactivate
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('user-management.activate', $user->slug) }}" method="POST" class="activate-form">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="fas fa-user-check me-1"></i> Activate
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </li>                                                   
                                                    
                                                    <li>
                                                        <form action="{{ route('user-management.destroy', $user->slug) }}" method="POST" onsubmit="return confirmDelete(event)">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Mobile Card List -->
            <div class="d-lg-none mt-3">
                @foreach ($users as $user)
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex flex-column">
                                        <h6 class="fw-bold mb-1">{{ $user->name }}</h6>
                                        <p class="text-muted small mb-1">{{ $user->email }}</p>
                                        <div class="mt-1">
                                            <span class="badge bg-{{ $user->is_admin ? 'primary' : 'secondary' }}">
                                                {{ $user->is_admin ? 'Admin' : 'General' }}
                                            </span>
                                            <span
                                                class="badge bg-{{ $user->is_active ? 'success' : 'warning' }} align-self-start">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('user-management.edit', $user->slug) }}">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            @if ($user->is_admin)
                                                <form action="{{ route('user-management.revoke-admin', $user->slug) }}" method="POST" class="revoke-form">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-warning">
                                                        <i class="fas fa-user-slash me-1"></i> Revoke Admin
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('user-management.grant-admin', $user->slug) }}" method="POST" class="grant-form">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="fas fa-user-shield me-1"></i> Grant Admin
                                                    </button>
                                                </form>
                                            @endif
                                        </li>
                                        <li>
                                            @if ($user->is_active)
                                                <form action="{{ route('user-management.deactivate', $user->slug) }}" method="POST" class="deactivate-form">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-warning">
                                                        <i class="fas fa-user-times me-1"></i> Deactivate
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('user-management.activate', $user->slug) }}" method="POST" class="activate-form">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="fas fa-user-check me-1"></i> Activate
                                                    </button>
                                                </form>
                                            @endif
                                        </li>  
                                        <li>
                                            <form action="{{ route('user-management.destroy', $user->slug) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirmDelete(event)">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

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

        // Custom alert function
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

            // Add icon based on type
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

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.style.animation = 'fadeOut 0.3s ease-out forwards';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }

        // Custom delete confirmation
        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
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

        // Add CSS animations
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

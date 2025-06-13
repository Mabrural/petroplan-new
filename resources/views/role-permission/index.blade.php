@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>

            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Role & Permission Management</h3>
                    <p class="text-muted mb-0">Manage user roles and permissions</p>
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
                                    <th>User</th>
                                    <th>Assigned Roles & Permissions</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </td>
                                        <td>
                                            @if ($user->rolePermissions->isNotEmpty())
                                                @foreach ($user->rolePermissions as $permission)
                                                    <span
                                                        class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $permission->permission)) }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge bg-secondary">No Roles Assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-dark" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if ($user->rolePermissions->isEmpty())
                                                        <li>
                                                            <a class="dropdown-item text-primary"
                                                                href="{{ url('role-permissions/create/' . $user->slug) }}">
                                                                <i class="fas fa-user-edit me-1"></i> Manage Roles
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <form
                                                                action="{{ route('role-permissions.destroy', $user->slug) }}"
                                                                method="POST" onsubmit="return confirmDelete(event)">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-user-times me-1"></i> Revoke Access
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
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

            <!-- Mobile View -->
            <div class="d-lg-none mt-3">
                @foreach ($users as $user)
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $user->name }}</h6>
                                    <small class="text-muted d-block">{{ $user->email }}</small>
                                    <div class="mt-2">
                                        @if ($user->rolePermissions->isNotEmpty())
                                            @foreach ($user->rolePermissions as $role)
                                                <span
                                                    class="badge bg-info text-dark">{{ ucfirst(str_replace('_', ' ', $role->permission)) }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-secondary">No Roles Assigned</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if ($user->rolePermissions->isEmpty())
                                            <li>
                                                <a class="dropdown-item text-primary"
                                                    href="{{ url('role-permissions/create/' . $user->slug) }}">
                                                    <i class="fas fa-user-edit me-2"></i> Manage Roles
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <form action="{{ route('role-permissions.destroy', $user->slug) }}"
                                                    method="POST" onsubmit="return confirmDelete(event)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-user-times me-2"></i> Revoke
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
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
                title: 'Revoke User Access?',
                text: "This will remove all roles and permissions assigned to this user",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, revoke access',
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

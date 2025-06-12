@extends('layouts.main')

@section('container')
<div class="container">
    <div class="page-inner">
        <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>

        <div class="d-flex justify-content-between align-items-center py-3">
            <div>
                <h3 class="fw-bold mb-0">Assign Roles & Permissions</h3>
                <p class="text-muted mb-0">For: {{ $user->name }}</p>
            </div>
            <div>
                <a href="{{ route('user-management.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Users
                </a>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg me-3">
                            <div class="avatar-title rounded-circle bg-primary-light text-primary">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $user->name }}</h5>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                    </div>
                </div>

                <form action="{{ route('role-permissions.store', $user->slug) }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Role Assignment -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label fw-bold">Select Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" selected disabled>-- Select Role --</option>
                                    <option value="admin_officer">Admin Officer</option>
                                    <option value="operation">Operation</option>
                                </select>
                            </div>
                        </div>

                        <!-- Permissions Section -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Permissions</label>
                                <div class="form-text mb-2">Select permissions for this user</div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="create" id="perm_create">
                                    <label class="form-check-label" for="perm_create">
                                        Create
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="read" id="perm_read" checked>
                                    <label class="form-check-label" for="perm_read">
                                        Read
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="update" id="perm_update">
                                    <label class="form-check-label" for="perm_update">
                                        Update
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="delete" id="perm_delete">
                                    <label class="form-check-label" for="perm_delete">
                                        Delete
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Assignments
                        </button>
                    </div>
                </form>
            </div>
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

    // Role-based permission presets
    document.getElementById('role').addEventListener('change', function() {
        const role = this.value;
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        
        // Reset all checkboxes except 'read' which should always be checked
        checkboxes.forEach(checkbox => {
            if (checkbox.value !== 'read') {
                checkbox.checked = false;
            }
        });

        // Set permissions based on role
        if (role === 'admin_officer') {
            document.getElementById('perm_create').checked = true;
            document.getElementById('perm_read').checked = true;
            document.getElementById('perm_update').checked = true;
            document.getElementById('perm_delete').checked = true;
        } else if (role === 'operation') {
            document.getElementById('perm_read').checked = true;
            document.getElementById('perm_update').checked = true;
        }
    });
</script>
@endsection
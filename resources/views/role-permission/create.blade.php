@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <!-- Alert Container -->
            <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>

            <div class="container mt-4">
                <h3>Assign Role/Permission to {{ $user->name }}</h3>

                <form action="{{ route('role-permissions.store', $user->slug) }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Role Selection -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="permission" class="form-label fw-bold">Select Role</label>
                                <select class="form-select @error('permission') is-invalid @enderror" id="permission"
                                    name="permission" required>
                                    <option value="" selected disabled>-- Select Role --</option>
                                    <option value="admin_officer"
                                        {{ old('permission') == 'admin_officer' ? 'selected' : '' }}>Admin Officer</option>
                                    <option value="operasion" {{ old('permission') == 'operasion' ? 'selected' : '' }}>
                                        Operation</option>
                                </select>
                                @error('permission')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Assign</button>
                    <a href="{{ route('role-permissions.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
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
    </script>
@endsection

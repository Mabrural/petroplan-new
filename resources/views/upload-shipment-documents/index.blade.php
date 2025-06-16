@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Shipment Documents</h3>
                    <p class="text-muted mb-0">Manage uploaded shipment-related documents</p>
                </div>
                <div>
                    <a href="{{ route('upload-shipment-documents.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload me-1"></i> Upload Documents
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
                                    <th>Period</th>
                                    <th>Shipment</th>
                                    <th>Document Type</th>
                                    <th>Attachment</th>
                                    <th>Uploaded By</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($documents as $document)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $document->shipment->period->period_name ?? '-' }}</td>
                                        <td>{{ 'Shipment '.$document->shipment->shipment_number ?? '-' }}</td>
                                        <td>{{ $document->documentType->document_name ?? '-' }}</td>
                                        <td>
                                            <a href="{{ asset('storage/' . $document->attachment) }}" target="_blank" class="text-decoration-underline">View</a>
                                        </td>
                                        <td>{{ $document->creator->name ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('upload-shipment-documents.destroy', $document->id) }}" method="POST" onsubmit="return confirmDelete(event)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <img src="{{ asset('assets/img/empty-box.png') }}" alt="Empty state" style="height: 120px; opacity: 0.7;" class="mb-3">
                                                <h5 class="text-muted">No Documents Found</h5>
                                                <p class="text-muted mb-3">No uploaded documents available yet</p>
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
                @forelse ($documents as $document)
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6 class="fw-bold mb-1">{{ $document->documentType->document_name ?? 'Unknown' }}</h6>
                            <p class="text-muted mb-1">Shipment: {{ $document->shipment->shipment_number ?? '-' }}</p>
                            <p class="text-muted mb-1">Uploaded by: {{ $document->creator->name ?? '-' }}</p>
                            <a href="{{ asset('storage/' . $document->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">View</a>
                            <form action="{{ route('upload-shipment-documents.destroy', $document->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <img src="{{ asset('assets/img/empty-box.png') }}" alt="Empty state" style="height: 100px; opacity: 0.7;" class="mb-3">
                                <h5 class="text-muted">No Documents Available</h5>
                                <p class="text-muted mb-3">Start uploading shipment documents</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- SweetAlert & Alert Script -->
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

            let icon = type === 'success'
                ? '<i class="fas fa-check-circle me-2"></i>'
                : '<i class="fas fa-exclamation-circle me-2"></i>';

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
                customClass: { popup: 'animated bounceIn' }
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

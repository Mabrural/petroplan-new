@extends('layouts.main')

@section('container')
    <div class="container mt-4">
        <div class="page-inner">
            <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>

            <!-- Header -->
            <div class="mb-4 mt-5">
                <h4 class="fw-bold">Upload Documents for <span class="text-primary">Shipment
                        {{ $shipment->shipment_number }}</span></h4>
                <p class="text-muted">Ensure you upload all required documents correctly.</p>
            </div>

            <!-- Shipment Detail Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3 text-primary">Shipment Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong>Termin:</strong> {{ 'Termin ' . $shipment->termin->termin_number ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Shipment:</strong> {{ 'Shipment ' . $shipment->shipment_number ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>SPK:</strong> {{ $shipment->spk->spk_number ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Vessel:</strong> {{ $shipment->vessel->vessel_name ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Fuel:</strong> {{ $shipment->fuel->fuel_type ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Location:</strong> {{ $shipment->location }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Volume:</strong> {{ number_format($shipment->volume, 0, ',', '.') }} Liter
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Status:</strong>
                            <span
                                class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $shipment->status_shipment)) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Upload Cards -->
            <div class="row">
                @foreach ($documentTypes as $docType)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-2">{{ $docType->document_name }}</h6>

                                @php
                                    $uploadedList = \App\Models\UploadShipmentDocument::with('creator')
                                        ->where('shipment_id', $shipment->id)
                                        ->where('document_type_id', $docType->id)
                                        ->where('period_id', session('active_period_id'))
                                        ->orderBy('created_at', 'desc')
                                        ->get();
                                @endphp

                                @if ($uploadedList->isNotEmpty())
                                    <div class="badge bg-success mb-2">
                                        {{ $uploadedList->count() }} file{{ $uploadedList->count() > 1 ? 's' : '' }}
                                        uploaded
                                    </div>

                                    <div class="uploaded-doc-list small mb-3" style="max-height: 150px; overflow-y: auto;">
                                        @foreach ($uploadedList as $doc)
                                            <div class="d-flex flex-column mb-2 p-2 bg-light rounded">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <div class="d-flex align-items-center gap-2" style="width: 70%;">
                                                        <div class="thumbnail-preview cursor-pointer"
                                                            style="width: 50px; height: 50px; overflow: hidden;"
                                                            data-bs-toggle="modal" data-bs-target="#documentModal"
                                                            data-url="{{ asset('storage/' . $doc->attachment) }}"
                                                            data-type="{{ pathinfo($doc->attachment, PATHINFO_EXTENSION) }}"
                                                            data-title="{{ basename($doc->attachment) }}">
                                                            @if (Str::endsWith($doc->attachment, ['.pdf']))
                                                                <div
                                                                    class="bg-danger text-white d-flex align-items-center justify-content-center h-100">
                                                                    <i class="fas fa-file-pdf fa-lg"></i>
                                                                </div>
                                                            @else
                                                                <img src="{{ asset('storage/' . $doc->attachment) }}"
                                                                    class="img-fluid h-100 w-100 object-fit-cover">
                                                            @endif
                                                        </div>
                                                        <span class="text-truncate">
                                                            {{ basename($doc->attachment) }}
                                                        </span>
                                                    </div>
                                                    @if ($shipment->status_shipment != 'filling_completed' && $shipment->status_shipment != 'cancelled')
                                                        <div>
                                                            <form
                                                                action="{{ route('shipments.upload.documents.destroy', [$shipment->id, $doc->id]) }}"
                                                                method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-danger delete-btn">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div
                                                    class="d-flex justify-content-between align-items-center small text-muted">
                                                    <span>
                                                        <i class="fas fa-user me-1"></i>
                                                        {{ $doc->creator->name ?? 'System' }}
                                                    </span>
                                                    <span>
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $doc->created_at->format('d M Y H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <span class="badge bg-warning text-dark">No file uploaded</span>
                                    </div>
                                @endif

                                <form action="{{ route('shipments.upload.documents.store', $shipment->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @if ($shipment->status_shipment != 'filling_completed' && $shipment->status_shipment != 'cancelled')
                                        <input type="hidden" name="document_type_id" value="{{ $docType->id }}">
                                        <p class="text-muted small mb-1">
                                            Allowed file types: <strong>.png, .jpg, .jpeg, .pdf</strong><br>
                                            Max file size: <strong>5MB per file</strong>
                                        </p>


                                        <div class="input-group input-group-sm">
                                            <input type="file" name="attachment[]" multiple class="form-control"
                                                accept=".png, .jpg, .jpeg, .pdf" required>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-upload me-1"></i> Upload
                                            </button>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Back Button -->
            <div class="mt-4">
                <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Shipments
                </a>
            </div>
        </div>
    </div>

    <!-- Document Preview Modal -->
    <div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="documentModalLabel">Document Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="min-height: 70vh;">
                    <div id="documentViewer" class="w-100 h-100 d-flex justify-content-center align-items-center">
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading document...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <a id="downloadBtn" href="#" class="btn btn-primary" download>
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Alert notification system
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
            alertEl.style.cssText =
                `position: relative; overflow: hidden; border: none; border-left: 4px solid ${type === 'success' ? '#28a745' : '#dc3545'}; animation: slideIn 0.3s ease-out forwards; margin-bottom: 10px;`;

            let icon = type === 'success' ?
                '<i class="fas fa-check-circle me-2"></i>' :
                '<i class="fas fa-exclamation-circle me-2"></i>';

            alertEl.innerHTML = `
                <div class="d-flex align-items-center">
                    <div style="font-size: 1.5rem; color: ${type === 'success' ? '#28a745' : '#dc3545'};">${icon}</div>
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

        // Document Modal Handler
        document.addEventListener('DOMContentLoaded', function() {
            const documentModal = new bootstrap.Modal(document.getElementById('documentModal'));
            const documentViewer = document.getElementById('documentViewer');
            const documentModalLabel = document.getElementById('documentModalLabel');
            const downloadBtn = document.getElementById('downloadBtn');

            // Handle thumbnail clicks
            document.querySelectorAll('.thumbnail-preview').forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    const type = this.getAttribute('data-type').toLowerCase();
                    const title = this.getAttribute('data-title');

                    documentModalLabel.textContent = title;
                    downloadBtn.setAttribute('href', url);
                    downloadBtn.setAttribute('download', title);

                    // Show loading state
                    documentViewer.innerHTML = `
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading document...</p>
                        </div>
                    `;

                    // Load content based on type
                    setTimeout(() => {
                        if (type === 'pdf') {
                            documentViewer.innerHTML = `
                                <embed src="${url}" type="application/pdf" 
                                    style="width: 100%; height: 70vh; border: none;" />
                            `;
                        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(type)) {
                            documentViewer.innerHTML = `
                                <img src="${url}" class="img-fluid" style="max-height: 70vh; object-fit: contain;">
                            `;
                        } else {
                            documentViewer.innerHTML = `
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Preview not available for this file type. Please download to view.
                                </div>
                            `;
                        }
                    }, 300);

                    documentModal.show();
                });
            });

            // Delete confirmation
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete Document?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Smooth loading for file uploads
            document.querySelectorAll('form[enctype="multipart/form-data"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <span class="upload-spinner" style="display: inline-block; animation: spin 1s linear infinite;">
                            <i class="fas fa-circle-notch"></i>
                        </span>
                        Uploading...
                    `;

                    // After form submit, reset button after a short delay
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }, 2000);
                });
            });
        });

        // Keyframe animations
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
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .cursor-pointer { cursor: pointer; }
            .object-fit-cover { object-fit: cover; }
        `;
        document.head.appendChild(style);
    </script>
@endsection

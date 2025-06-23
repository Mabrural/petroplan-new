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
                @php
                    // Get all uploaded documents in a single optimized query
                    $uploadedDocuments = \App\Models\UploadShipmentDocument::with(['creator', 'documentType'])
                        ->where('shipment_id', $shipment->id)
                        ->where('period_id', session('active_period_id'))
                        ->get()
                        ->groupBy('document_type_id');
                @endphp

                @foreach ($documentTypes as $docType)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-2">{{ $docType->document_name }}</h6>

                                @if (isset($uploadedDocuments[$docType->id]) && $uploadedDocuments[$docType->id]->isNotEmpty())
                                    @php
                                        $docsForType = $uploadedDocuments[$docType->id]->sortByDesc('created_at');
                                        $totalDocs = $docsForType->count();
                                        $initialDisplay = 3;
                                    @endphp

                                    <div class="badge bg-success mb-2">
                                        {{ $totalDocs }} file{{ $totalDocs > 1 ? 's' : '' }} uploaded
                                    </div>

                                    <!-- Document list with initial display limit -->
                                    <div class="uploaded-doc-list small mb-3" style="max-height: 150px; overflow-y: auto;">
                                        @foreach ($docsForType as $index => $doc)
                                            <div class="d-flex flex-column mb-2 p-2 bg-light rounded @if($index >= $initialDisplay) d-none more-document @endif" 
                                                data-doctype="{{ $docType->id }}">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <div class="d-flex align-items-center gap-2" style="width: 70%;">
                                                        <div class="thumbnail-preview cursor-pointer lazy-load"
                                                            style="width: 50px; height: 50px; overflow: hidden;"
                                                            data-bs-toggle="modal" data-bs-target="#documentModal"
                                                            data-url="{{ url('storage/' . $doc->attachment) }}"
                                                            data-type="{{ pathinfo($doc->attachment, PATHINFO_EXTENSION) }}"
                                                            data-title="{{ basename($doc->attachment) }}"
                                                            data-loaded="false">
                                                            @if (Str::endsWith($doc->attachment, ['.pdf']))
                                                                <div
                                                                    class="bg-danger text-white d-flex align-items-center justify-content-center h-100">
                                                                    <i class="fas fa-file-pdf fa-lg"></i>
                                                                </div>
                                                            @else
                                                                <!-- Placeholder for images -->
                                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center h-100">
                                                                    <i class="fas fa-image"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <span class="text-truncate">
                                                            {{ basename($doc->attachment) }}
                                                        </span>
                                                    </div>
                                                    @if ($shipment->status_shipment != 'filling_completed' && $shipment->status_shipment != 'cancelled' && Auth::user()->id == $doc->created_by)
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
                                        
                                        @if($totalDocs > $initialDisplay)
                                            <div class="text-center mt-2">
                                                <button class="btn btn-sm btn-outline-primary load-more-btn" 
                                                    data-doctype="{{ $docType->id }}"
                                                    data-offset="{{ $initialDisplay }}"
                                                    data-total="{{ $totalDocs }}">
                                                    Load more (+{{ $totalDocs - $initialDisplay }})
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <span class="badge bg-warning text-dark">No file uploaded</span>
                                    </div>
                                @endif

                                <form action="{{ route('shipments.upload.documents.store', $shipment->id) }}"
                                    method="POST" enctype="multipart/form-data" class="upload-form">
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
                                            <button type="submit" class="btn btn-primary upload-btn">
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
            document.addEventListener('click', function(e) {
                if (e.target.closest('.thumbnail-preview')) {
                    const thumbnail = e.target.closest('.thumbnail-preview');
                    const url = thumbnail.getAttribute('data-url');
                    const type = thumbnail.getAttribute('data-type').toLowerCase();
                    const title = thumbnail.getAttribute('data-title');

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
                }
            });

            // Delete confirmation
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn')) {
                    e.preventDefault();
                    const form = e.target.closest('form');

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
                }
            });

            // Lazy load images when they come into view
            const lazyLoadThumbnails = function() {
                const lazyElements = document.querySelectorAll('.lazy-load:not([data-loaded="true"])');
                
                lazyElements.forEach(el => {
                    const rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight + 500 && rect.bottom > -500) {
                        const url = el.getAttribute('data-url');
                        const type = el.getAttribute('data-type').toLowerCase();
                        
                        if (['jpg', 'jpeg', 'png', 'gif'].includes(type)) {
                            const img = document.createElement('img');
                            img.src = url;
                            img.className = 'img-fluid h-100 w-100 object-fit-cover';
                            img.onload = function() {
                                el.innerHTML = '';
                                el.appendChild(img);
                                el.setAttribute('data-loaded', 'true');
                            };
                            img.onerror = function() {
                                el.innerHTML = '<div class="bg-secondary text-white d-flex align-items-center justify-content-center h-100"><i class="fas fa-file"></i></div>';
                                el.setAttribute('data-loaded', 'true');
                            };
                        } else {
                            el.setAttribute('data-loaded', 'true');
                        }
                    }
                });
            };

            // Initial load and setup intersection observer
            lazyLoadThumbnails();
            window.addEventListener('scroll', lazyLoadThumbnails);
            window.addEventListener('resize', lazyLoadThumbnails);

            // Load more documents functionality - FULLY WORKING VERSION
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('load-more-btn')) {
                    const button = e.target;
                    const docTypeId = button.getAttribute('data-doctype');
                    const docListContainer = button.closest('.uploaded-doc-list');
                    
                    // Disable button during loading
                    button.disabled = true;
                    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
                    
                    // Get all hidden documents for this type
                    const hiddenDocs = docListContainer.querySelectorAll('.more-document[data-doctype="' + docTypeId + '"]');
                    
                    // Show all hidden documents
                    hiddenDocs.forEach(doc => {
                        doc.classList.remove('d-none');
                    });
                    
                    // Remove the load more button since we've shown all
                    button.remove();
                }
            });

            // Optimized file upload handling
            document.querySelectorAll('.upload-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('.upload-btn');
                    const originalBtnText = submitBtn.innerHTML;

                    // Validate file size before upload
                    const fileInput = this.querySelector('input[type="file"]');
                    let totalSize = 0;
                    
                    for (let i = 0; i < fileInput.files.length; i++) {
                        totalSize += fileInput.files[i].size;
                        if (fileInput.files[i].size > 5 * 1024 * 1024) { // 5MB
                            showAlert('error', `File "${fileInput.files[i].name}" exceeds 5MB limit`);
                            e.preventDefault();
                            return;
                        }
                    }
                    
                    if (totalSize > 50 * 1024 * 1024) { // 20MB total
                        showAlert('error', 'Total upload size exceeds 50MB limit');
                        e.preventDefault();
                        return;
                    }

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Uploading...
                    `;
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
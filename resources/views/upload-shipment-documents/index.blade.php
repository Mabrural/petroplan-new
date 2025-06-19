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

            <!-- Filter Section -->
            <div class="card mt-3">
                <div class="card-body">
                    <form id="filterForm" method="GET" action="{{ route('upload-shipment-documents.index') }}">
                        <div class="row g-3">
                            <!-- Shipment Filter -->
                            <div class="col-6 col-md-4 col-lg-3">
                                <label for="shipment_id" class="form-label">Shipment</label>
                                <select class="form-select" id="shipment_id" name="shipment_id">
                                    <option value="">All Shipments</option>
                                    @foreach ($shipments as $shipment)
                                        <option value="{{ $shipment->id }}"
                                            {{ request('shipment_id') == $shipment->id ? 'selected' : '' }}>
                                            Shipment {{ $shipment->shipment_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Document Type Filter -->
                            <div class="col-6 col-md-4 col-lg-3">
                                <label for="document_type_id" class="form-label">Document Type</label>
                                <select class="form-select" id="document_type_id" name="document_type_id">
                                    <option value="">All Types</option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ request('document_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->document_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Items Per Page -->
                            <div class="col-6 col-md-4 col-lg-2">
                                <label for="per_page" class="form-label">Show</label>
                                <select class="form-select" id="per_page" name="per_page" onchange="this.form.submit()">
                                    @foreach ([10, 20, 30, 50, 100] as $option)
                                        <option value="{{ $option }}"
                                            {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Reset Button -->
                            <div class="col-6 col-md-4 col-lg-2 d-flex align-items-end">
                                <a href="{{ route('upload-shipment-documents.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-sync-alt me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mb-2 text-muted small">
                Showing {{ $documents->firstItem() }}–{{ $documents->lastItem() }} of {{ $totalDocuments }} documents
            </div>

            <!-- Desktop Table -->
            <div class="card d-none d-lg-block mt-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Shipment</th>
                                    <th>Document Type</th>
                                    <th>Attachment</th>
                                    <th>Uploaded By/At</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($documents as $document)
                                    <tr>
                                        <td>{{ $documents->firstItem() + $loop->index }}</td>
                                        <td>{{ 'Shipment ' . $document->shipment->shipment_number ?? '-' }}</td>
                                        <td>{{ $document->documentType->document_name ?? '-' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary preview-btn"
                                                data-url="{{ asset('storage/' . $document->attachment) }}"
                                                data-type="{{ pathinfo($document->attachment, PATHINFO_EXTENSION) }}">
                                                <i class="fas fa-eye me-1"></i> View
                                            </button>

                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $document->creator->name ?? '-' }}</div>
                                            <small
                                                class="text-muted">{{ $document->created_at ? $document->created_at->format('d M Y H:i') : '-' }}</small>
                                        </td>
                                        <td>
                                            <form action="{{ route('upload-shipment-documents.destroy', $document->id) }}"
                                                method="POST" onsubmit="return confirmDelete(event)">
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
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <img src="{{ asset('assets/img/empty-box.png') }}" alt="Empty state"
                                                    style="height: 120px; opacity: 0.7;" class="mb-3">
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
                <div class="p-3">
                    {{ $documents->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <!-- Mobile Card List -->
            <div class="d-lg-none mt-3">
                @forelse ($documents as $document)
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6 class="fw-bold mb-1">{{ $document->documentType->document_name ?? 'Unknown' }}</h6>
                            <p class="text-muted mb-1">Shipment: {{ $document->shipment->shipment_number ?? '-' }}</p>
                            <p class="text-muted mb-0 mt-1">Uploaded by: </p>
                            <div class="fw-bold">{{ $document->creator->name ?? '-' }}</div>
                            <small
                                class="text-muted">{{ $document->created_at ? $document->created_at->format('d M Y H:i') : '-' }}</small><br>
                            <button type="button" class="btn btn-sm btn-outline-primary preview-btn"
                                data-url="{{ asset('storage/' . $document->attachment) }}"
                                data-type="{{ pathinfo($document->attachment, PATHINFO_EXTENSION) }}">
                                <i class="fas fa-eye me-1"></i> View
                            </button>
                            <form action="{{ route('upload-shipment-documents.destroy', $document->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirmDelete(event)">
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
                                <img src="{{ asset('assets/img/empty-box.png') }}" alt="Empty state"
                                    style="height: 100px; opacity: 0.7;" class="mb-3">
                                <h5 class="text-muted">No Documents Available</h5>
                                <p class="text-muted mb-3">Start uploading shipment documents</p>
                            </div>
                        </div>
                    </div>
                @endforelse
                <div class="p-3">
                    {{ $documents->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Document Preview -->
    <div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-labelledby="documentPreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="documentPreviewModalLabel">Document Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0" style="background-color: #f9f9f9;">
                    <div id="documentPreviewContainer"
                        style="height: 80vh; display: flex; justify-content: center; align-items: center; overflow: hidden;">
                        <!-- Filled dynamically -->
                    </div>
                </div>
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

        // Auto-submit form when filter changes (except per_page which has its own onchange)
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const filterSelects = filterForm.querySelectorAll('select:not(#per_page)');

            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });
        });

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const previewButtons = document.querySelectorAll('.preview-btn');
            const previewModal = new bootstrap.Modal(document.getElementById('documentPreviewModal'));
            const previewContainer = document.getElementById('documentPreviewContainer');

            previewButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const url = button.getAttribute('data-url');
                    const type = button.getAttribute('data-type').toLowerCase();

                    let content = '';

                    if (type === 'pdf') {
                        content =
                            `<embed src="${url}" type="application/pdf" style="width: 100%; height: 100%; object-fit: cover; border: none;" />`;
                    } else {
                        content = `<img src="${url}" alt="Preview Image" class="img-fluid rounded"
                                style="max-height: 100%; max-width: 100%; object-fit: contain;" />`;
                    }

                    previewContainer.innerHTML = content;
                    previewModal.show();
                });
            });
        });
    </script>
@endsection

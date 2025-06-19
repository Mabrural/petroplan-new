@extends('layouts.main')

@section('container')
    <div class="container mt-4">
        <div class="page-inner">
            <!-- Header -->
            <div class="mb-4">
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

            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Document Upload Cards -->
            <div class="row">
                @foreach ($documentTypes as $index => $docType)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <h6 class="fw-bold mb-2">{{ $docType->document_name }}</h6>

                                <div class="mb-2">
                                    @if (isset($uploadedDocuments[$docType->id]))
                                        <span class="badge bg-success mb-1">Uploaded</span><br>
                                        <button type="button" class="btn btn-sm  px-0 text-primary view-doc-btn"
                                            data-url="{{ asset('storage/' . $uploadedDocuments[$docType->id]) }}"
                                            data-name="{{ $docType->document_name }}">
                                            <i class="fas fa-eye me-1"></i> View Document
                                        </button>
                                    @else
                                        <span class="badge bg-warning text-dark">Not Uploaded</span>
                                    @endif

                                </div>

                                <form action="{{ route('shipments.upload.documents.store', $shipment->id) }}"
                                    method="POST" enctype="multipart/form-data" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="document_type_id" value="{{ $docType->id }}">
                                    <div class="input-group">
                                        <input type="file" name="attachment" class="form-control form-control-sm"
                                            required>
                                        <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                                    </div>
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

    <!-- Offcanvas Slider -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="documentSlider" aria-labelledby="documentSliderLabel">
        <div class="offcanvas-header bg-light">
            <h5 class="offcanvas-title" id="documentSliderLabel">Document Viewer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0" id="documentViewerBody" style="height: 100%; overflow: hidden;">
            <iframe id="documentFrame" src="" width="100%" height="100%" frameborder="0"
                style="border: none;"></iframe>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-doc-btn');
            const documentSlider = new bootstrap.Offcanvas(document.getElementById('documentSlider'));
            const documentFrame = document.getElementById('documentFrame');
            const documentLabel = document.getElementById('documentSliderLabel');

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    const name = this.getAttribute('data-name');

                    documentFrame.src = url;
                    documentLabel.innerText = name;
                    documentSlider.show();
                });
            });
        });
    </script>
@endsection

<div class="shipment-details">
    <div class="mb-4">
        <h5 class="fw-bold border-bottom pb-2 mb-3">Basic Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Shipment Number</label>
                <p class="mb-0 fw-bold">Shipment {{ $shipment->shipment_number }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Termin</label>
                <p class="mb-0 fw-bold">Termin {{ $shipment->termin->termin_number ?? '-' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Vessel</label>
                <p class="mb-0 fw-bold">{{ $shipment->vessel->vessel_name ?? '-' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">SPK</label>
                <p class="mb-0 fw-bold">{{ $shipment->spk->spk_number ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h5 class="fw-bold border-bottom pb-2 mb-3">Fuel Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Fuel Type</label>
                <p class="mb-0 fw-bold">{{ $shipment->fuel->fuel_type ?? '-' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Volume</label>
                <p class="mb-0 fw-bold">{{ number_format($shipment->volume, 0, ',', '.') }} Liter</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Location</label>
                <p class="mb-0 fw-bold">{{ $shipment->location }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Completion Date</label>
                <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($shipment->completion_date)->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h5 class="fw-bold border-bottom pb-2 mb-3">Additional Information</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label text-muted small mb-1">P</label>
                <p class="mb-0 fw-bold">{{ $shipment->p ?? '-' }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-muted small mb-1">A</label>
                <p class="mb-0 fw-bold">{{ $shipment->a ?? '-' }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-muted small mb-1">B</label>
                <p class="mb-0 fw-bold">{{ $shipment->b ?? '-' }}</p>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label text-muted small mb-1">LO</label>
                <p class="mb-0 fw-bold">{{ $shipment->lo ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h5 class="fw-bold border-bottom pb-2 mb-3">Status Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Status</label>
                <p class="mb-0">
                    <span
                        class="badge bg-{{ $shipment->status_shipment === 'completed' ? 'success' : ($shipment->status_shipment === 'cancelled' ? 'danger' : 'secondary') }}">
                        {{ ucfirst(str_replace('_', ' ', $shipment->status_shipment)) }}
                    </span>
                </p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Created By</label>
                <p class="mb-0 fw-bold">{{ $shipment->creator->name ?? '-' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Created At</label>
                <p class="mb-0 fw-bold">{{ $shipment->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Updated At</label>
                <p class="mb-0 fw-bold">{{ $shipment->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-primary btn-sm me-2">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this shipment?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash-alt me-1"></i> Delete
            </button>
        </form>
    </div>
</div>

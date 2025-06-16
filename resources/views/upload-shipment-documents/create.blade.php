@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Upload Shipment Document</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="{{ route('dashboard') }}">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('upload-shipment-documents.index') }}">Upload Documents</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Create</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Upload Information</div>
                        </div>
                        <form method="POST" action="{{ route('upload-shipment-documents.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="period_id">Select Period</label>
                                    <select class="form-control @error('period_id') is-invalid @enderror" id="period_id"
                                        name="period_id" required>
                                        <option value="">-- Choose Period --</option>
                                        @foreach ($periodes as $periode)
                                            <option value="{{ $periode->id }}"
                                                {{ old('period_id') == $periode->id ? 'selected' : '' }}>
                                                {{ $periode->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('period_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="shipment_id">Select Shipment</label>
                                    <select class="form-control @error('shipment_id') is-invalid @enderror" id="shipment_id"
                                        name="shipment_id" required>
                                        <option value="">-- Choose Shipment --</option>
                                        {{-- Akan diisi lewat JS --}}
                                    </select>
                                    @error('shipment_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="document_type_id">Document Type</label>
                                    <select name="document_type_id" id="document_type_id"
                                        class="form-control @error('document_type_id') is-invalid @enderror" required>
                                        <option value="">-- Select Document Type --</option>
                                        @foreach ($documentTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->document_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('document_type_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="attachments">Attachment(s)</label>
                                    <!-- Ubah name dari attachment[] menjadi attachments[] -->
                                    <input type="file" name="attachments[]" id="attachments"
                                        class="form-control @error('attachments.*') is-invalid @enderror"
                                        accept=".pdf,.png,.jpg,.jpeg,.heic" multiple required>
                                    <small class="text-muted">Allowed formats: PDF, PNG, JPG, JPEG, HEIC. Max 20MB per
                                        file.</small>
                                    @error('attachments.*')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-action">
                                <button type="submit" class="btn btn-primary">Upload</button>
                                <a href="{{ route('upload-shipment-documents.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const periodSelect = document.getElementById('period_id');
    const shipmentSelect = document.getElementById('shipment_id');

    periodSelect.addEventListener('change', function () {
        const periodId = this.value;
        shipmentSelect.innerHTML = '<option value="">-- Choose Shipment --</option>';

        if (periodId) {
            fetch(`/get-shipments/${periodId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(shipment => {
                        const option = document.createElement('option');
                        option.value = shipment.id;
                        option.textContent = `Shipment ${shipment.shipment_number}`;
                        shipmentSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
    });
});
</script>
@endpush

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
                                    <input type="file" name="attachments[]" id="attachments"
                                        class="form-control @error('attachments.*') is-invalid @enderror"
                                        accept=".pdf,.png,.jpg,.jpeg,.heic" multiple required onchange="previewFiles(this)">
                                    <small class="text-muted">Allowed formats: PDF, PNG, JPG, JPEG, HEIC. Max 20MB per
                                        file.</small>
                                    @error('attachments.*')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror

                                    <!-- Preview Container -->
                                    <div id="filePreviews" class="row mt-3"></div>
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

@push('styles')
    <style>
        .file-preview {
            position: relative;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            background: #f9f9f9;
        }

        .preview-image {
            max-width: 100%;
            height: auto;
            max-height: 150px;
            display: block;
            margin: 0 auto;
        }

        .preview-pdf {
            width: 100%;
            height: 150px;
            background: #f1f1f1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 14px;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            font-size: 12px;
            cursor: pointer;
        }

        .file-info {
            margin-top: 5px;
            font-size: 12px;
            text-align: center;
            word-break: break-all;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodSelect = document.getElementById('period_id');
            const shipmentSelect = document.getElementById('shipment_id');

            periodSelect.addEventListener('change', function() {
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


        function previewFiles(input) {
            const previewContainer = document.getElementById('filePreviews');
            previewContainer.innerHTML = '';

            if (input.files && input.files.length > 0) {
                Array.from(input.files).forEach((file, index) => {
                    const fileType = file.type;
                    const reader = new FileReader();

                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'col-md-3 col-sm-4 col-6 file-preview';
                    previewDiv.id = `preview-${index}`;

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-btn';
                    removeBtn.innerHTML = '×';
                    removeBtn.onclick = () => removePreview(index);

                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'file-info';
                    fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;

                    previewDiv.appendChild(removeBtn);
                    previewDiv.appendChild(fileInfo);

                    if (fileType.startsWith('image/')) {
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.className = 'preview-image';
                            img.src = e.target.result;
                            img.alt = 'Preview';
                            previewDiv.insertBefore(img, fileInfo);
                        }
                        reader.readAsDataURL(file);
                    } else if (fileType === 'application/pdf') {
                        const pdfPreview = document.createElement('div');
                        pdfPreview.className = 'preview-pdf';
                        pdfPreview.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-file-pdf fa-3x mb-2" style="color: #e74c3c;"></i>
                        <p>PDF Preview</p>
                    </div>
                `;
                        previewDiv.insertBefore(pdfPreview, fileInfo);
                    } else {
                        const genericPreview = document.createElement('div');
                        genericPreview.className = 'preview-pdf';
                        genericPreview.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-file fa-3x mb-2"></i>
                        <p>${fileType || 'File'} Preview</p>
                    </div>
                `;
                        previewDiv.insertBefore(genericPreview, fileInfo);
                    }

                    previewContainer.appendChild(previewDiv);
                });
            }
        }

        function removePreview(index) {
            const previewDiv = document.getElementById(`preview-${index}`);
            if (previewDiv) {
                previewDiv.remove();
            }

            // Remove the file from input.files (requires DataTransfer workaround)
            const input = document.getElementById('attachments');
            const dataTransfer = new DataTransfer();
            Array.from(input.files).forEach((file, i) => {
                if (i !== index) {
                    dataTransfer.items.add(file);
                }
            });
            input.files = dataTransfer.files;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
@endpush

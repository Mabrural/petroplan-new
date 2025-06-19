@extends('layouts.main')

@section('container')
<div class="container mt-4">
    <div class="page-inner">
        
        <h4 class="mb-4">Upload Documents for Shipment {{ $shipment->shipment_number }}</h4>
    
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Document Type</th>
                    <th>Status</th>
                    <th>Upload / Replace</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documentTypes as $index => $docType)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $docType->document_name }}</td>
                        <td>
                            @if (isset($uploadedDocuments[$docType->id]))
                                <span class="badge bg-success">Uploaded</span><br>
                                <a href="{{ asset('storage/' . $uploadedDocuments[$docType->id]) }}" target="_blank">View</a>
                            @else
                                <span class="badge bg-warning text-dark">Not Uploaded</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('shipments.upload.documents.store', $shipment->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="document_type_id" value="{{ $docType->id }}">
                                <div class="input-group">
                                    <input type="file" name="attachment" class="form-control form-control-sm" required>
                                    <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        <a href="{{ route('shipments.index') }}" class="btn btn-secondary mt-3">Back to Shipments</a>
    </div>
</div>
@endsection

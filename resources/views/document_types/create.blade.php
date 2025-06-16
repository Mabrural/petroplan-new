@extends('layouts.main')

@section('container')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Create New Document Type</h4>
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
                    <a href="{{ route('document-types.index') }}">Document Types</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Create Document Type</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Document Type Information</div>
                    </div>
                    <form method="POST" action="{{ route('document-types.store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="document_name">Document Name</label>
                                <input type="text" class="form-control @error('document_name') is-invalid @enderror"
                                       id="document_name" name="document_name" value="{{ old('document_name') }}"
                                       placeholder="e.g. Invoice, Delivery Note" required>
                                @error('document_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Create Document Type</button>
                            <a href="{{ route('document-types.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

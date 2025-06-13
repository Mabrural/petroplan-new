@extends('layouts.main')

@section('container')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Vessel</h4>
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
                    <a href="{{ route('vessels.index') }}">Vessel</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit Vessel</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Vessel Information</div>
                    </div>
                    <form method="POST" action="{{ route('vessels.update', $vessel->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">

                            <div class="form-group">
                                <label for="vessel_name">Vessel Name</label>
                                <input type="text" class="form-control @error('vessel_name') is-invalid @enderror"
                                       id="vessel_name" name="vessel_name" value="{{ old('vessel_name', $vessel->vessel_name) }}"
                                       required>
                                @error('vessel_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image">Image (optional)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                @error('image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror

                                <div class="mt-3">
                                    <label class="d-block mb-1">Preview:</label>
                                    <img id="imagePreview"
                                         src="{{ $vessel->image ? asset('storage/' . $vessel->image) : '#' }}"
                                         alt="Image Preview"
                                         style="max-height: 200px; border: 1px solid #ccc; padding: 5px; {{ $vessel->image ? '' : 'display: none;' }}">
                                </div>
                            </div>

                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Update Vessel</button>
                            <a href="{{ route('vessels.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Image Script -->
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection

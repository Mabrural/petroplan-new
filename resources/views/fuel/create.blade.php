@extends('layouts.main')

@section('container')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Create New Fuel Type</h4>
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
                    <a href="{{ route('fuels.index') }}">Fuel</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Create Fuel</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Fuel Information</div>
                    </div>
                    <form method="POST" action="{{ route('fuels.store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="fuel_type">Fuel Type</label>
                                <input type="text" class="form-control @error('fuel_type') is-invalid @enderror"
                                       id="fuel_type" name="fuel_type" value="{{ old('fuel_type') }}"
                                       placeholder="e.g. Pertalite, Solar" required>
                                @error('fuel_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Create Fuel</button>
                            <a href="{{ route('fuels.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

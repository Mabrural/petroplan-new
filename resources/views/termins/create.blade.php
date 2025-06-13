@extends('layouts.main')

@section('container')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Create New Termin</h4>
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
                    <a href="{{ route('termins.index') }}">Termin</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Create Termin</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Termin Information</div>
                    </div>
                    <form method="POST" action="{{ route('termins.store') }}">
                        @csrf
                        <div class="card-body">

                            <div class="form-group">
                                <label for="period_id">Select Period</label>
                                <select class="form-control @error('period_id') is-invalid @enderror"
                                        id="period_id" name="period_id" required>
                                    <option value="">-- Choose Period --</option>
                                    @foreach ($periodes as $periode)
                                        <option value="{{ $periode->id }}" {{ old('period_id') == $periode->id ? 'selected' : '' }}>
                                            {{ $periode->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('period_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="termin_number">Termin Number</label>
                                <input type="number" class="form-control @error('termin_number') is-invalid @enderror"
                                       id="termin_number" name="termin_number" value="{{ old('termin_number') }}"
                                       placeholder="e.g. 1, 2, 3..." required>
                                @error('termin_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Create Termin</button>
                            <a href="{{ route('termins.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

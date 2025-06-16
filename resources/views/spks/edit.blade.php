@extends('layouts.main')

@section('container')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit SPK</h4>
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
                    <a href="{{ route('spks.index') }}">SPK</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit SPK</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit SPK Information</div>
                    </div>
                    <form method="POST" action="{{ route('spks.update', $spk->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                            {{-- Hidden input untuk period_id dari session --}}
                            <input type="hidden" name="period_id" value="{{ $activePeriodId }}">

                            <div class="form-group">
                                <label for="spk_number">SPK Number</label>
                                <input type="text" class="form-control @error('spk_number') is-invalid @enderror"
                                       id="spk_number" name="spk_number" value="{{ old('spk_number', $spk->spk_number) }}"
                                       required>
                                @error('spk_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="spk_date">SPK Date</label>
                                <input type="date" class="form-control @error('spk_date') is-invalid @enderror"
                                       id="spk_date" name="spk_date" value="{{ old('spk_date', $spk->spk_date) }}"
                                       required>
                                @error('spk_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="spk_file">Upload New SPK File (PDF only)</label>
                                @if ($spk->spk_file)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $spk->spk_file) }}" target="_blank" class="btn btn-sm btn-info">
                                            View Current File
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control-file @error('spk_file') is-invalid @enderror"
                                       id="spk_file" name="spk_file" accept="application/pdf">
                                @error('spk_file')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Update SPK</button>
                            <a href="{{ route('spks.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

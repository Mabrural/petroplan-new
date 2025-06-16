@extends('layouts.main')

@section('container')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Shipment</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="{{ route('shipments.index') }}">Shipment</a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="#">Edit Shipment</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Shipment Information</div>
                    </div>
                    <form method="POST" action="{{ route('shipments.update', $shipment->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="card-body row">

                            <div class="form-group col-md-6">
                                <label for="period_id">Select Period</label>
                                <select class="form-control @error('period_id') is-invalid @enderror"
                                        id="period_id" name="period_id" required>
                                    <option value="">-- Choose Period --</option>
                                    @foreach ($periodes as $periode)
                                        <option value="{{ $periode->id }}" {{ $shipment->period_id == $periode->id ? 'selected' : '' }}>
                                            {{ $periode->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('period_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="termin_id">Select Termin</label>
                                <select class="form-control @error('termin_id') is-invalid @enderror"
                                        id="termin_id" name="termin_id" required>
                                    <option value="">-- Choose Termin --</option>
                                    @foreach ($termins as $termin)
                                        @if ($termin->period_id == $shipment->period_id)
                                            <option value="{{ $termin->id }}" {{ $shipment->termin_id == $termin->id ? 'selected' : '' }}>
                                                Termin {{ $termin->termin_number }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('termin_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="shipment_number">Shipment Number</label>
                                <input type="text" class="form-control @error('shipment_number') is-invalid @enderror"
                                       id="shipment_number" name="shipment_number" value="{{ old('shipment_number', $shipment->shipment_number) }}" required>
                                @error('shipment_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="vessel_id">Select Vessel</label>
                                <select class="form-control @error('vessel_id') is-invalid @enderror"
                                        id="vessel_id" name="vessel_id" required>
                                    <option value="">-- Choose Vessel --</option>
                                    @foreach ($vessels as $vessel)
                                        <option value="{{ $vessel->id }}" {{ $shipment->vessel_id == $vessel->id ? 'selected' : '' }}>
                                            {{ $vessel->vessel_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vessel_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="spk_id">Select SPK</label>
                                <select class="form-control @error('spk_id') is-invalid @enderror"
                                        id="spk_id" name="spk_id" required>
                                    <option value="">-- Choose SPK --</option>
                                    @foreach ($spks as $spk)
                                        <option value="{{ $spk->id }}" {{ $shipment->spk_id == $spk->id ? 'selected' : '' }}>
                                            {{ $spk->spk_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('spk_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="location">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                       id="location" name="location" value="{{ old('location', $shipment->location) }}" required>
                                @error('location')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="fuel_id">Select Fuel Type</label>
                                <select class="form-control @error('fuel_id') is-invalid @enderror"
                                        id="fuel_id" name="fuel_id" required>
                                    <option value="">-- Choose Fuel --</option>
                                    @foreach ($fuels as $fuel)
                                        <option value="{{ $fuel->id }}" {{ $shipment->fuel_id == $fuel->id ? 'selected' : '' }}>
                                            {{ $fuel->fuel_type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fuel_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="volume">Volume (Liter)</label>
                                <input type="number" class="form-control @error('volume') is-invalid @enderror"
                                       id="volume" name="volume" value="{{ old('volume', $shipment->volume) }}" required>
                                @error('volume')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="p">P</label>
                                <input type="number" class="form-control @error('p') is-invalid @enderror"
                                       id="p" name="p" value="{{ old('p', $shipment->p) }}">
                                @error('p')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="a">A</label>
                                <input type="number" class="form-control @error('a') is-invalid @enderror"
                                       id="a" name="a" value="{{ old('a', $shipment->a) }}">
                                @error('a')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="b">B</label>
                                <input type="number" class="form-control @error('b') is-invalid @enderror"
                                       id="b" name="b" value="{{ old('b', $shipment->b) }}">
                                @error('b')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="completion_date">Completion Date</label>
                                <input type="date" class="form-control @error('completion_date') is-invalid @enderror"
                                       id="completion_date" name="completion_date" value="{{ old('completion_date', date('Y-m-d', strtotime($shipment->completion_date))) }}" required>
                                @error('completion_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="lo">LO</label>
                                <input type="text" class="form-control @error('lo') is-invalid @enderror"
                                       id="lo" name="lo" value="{{ old('lo', $shipment->lo) }}">
                                @error('lo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="status_shipment">Status Shipment</label>
                                <select class="form-control @error('status_shipment') is-invalid @enderror"
                                        id="status_shipment" name="status_shipment" required>
                                    <option value="in_progress" {{ $shipment->status_shipment == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="cancelled" {{ $shipment->status_shipment == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="completed" {{ $shipment->status_shipment == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="filling_completed" {{ $shipment->status_shipment == 'filling_completed' ? 'selected' : '' }}>Filling Completed</option>
                                </select>
                                @error('status_shipment')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Update Shipment</button>
                            <a href="{{ route('shipments.index') }}" class="btn btn-danger">Cancel</a>
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
    const terminSelect = document.getElementById('termin_id');
    const selectedTerminId = {{ $shipment->termin_id ?? 'null' }};

    periodSelect.addEventListener('change', function () {
        const periodId = this.value;

        terminSelect.innerHTML = '<option value="">-- Choose Termin --</option>';

        if (periodId) {
            fetch(`/get-termins/${periodId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(termin => {
                        const option = document.createElement('option');
                        option.value = termin.id;
                        option.textContent = `Termin ${termin.termin_number}`;
                        if (termin.id == selectedTerminId) {
                            option.selected = true;
                        }
                        terminSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
    });

    // Trigger change on page load to populate termin list
    if (periodSelect.value) {
        periodSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush

@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 350px;"></div>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Shipment Management</h3>
                    <p class="text-muted mb-0">Manage available Shipments</p>
                </div>
                @if (Auth::check() && Auth::user()->rolePermissions->contains('permission', 'admin_officer'))
                    <div>
                        <a href="{{ route('shipments.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add Shipment
                        </a>
                    </div>
                @endif
            </div>

            <!-- Filter Section -->
            <div class="card mt-3">
                <div class="card-body">
                    <form id="filterForm" method="GET" action="{{ route('shipments.index') }}">
                        <div class="row g-3">
                            <!-- Baris Pertama (6 filter) -->
                            <div class="col-6 col-md-4 col-lg-2">
                                <label for="termin_id" class="form-label">Termin</label>
                                <select class="form-select" id="termin_id" name="termin_id">
                                    <option value="">All Termin</option>
                                    @foreach ($termins as $termin)
                                        <option value="{{ $termin->id }}"
                                            {{ request('termin_id') == $termin->id ? 'selected' : '' }}>
                                            Termin {{ $termin->termin_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-4 col-lg-2">
                                <label for="spk_id" class="form-label">SPK</label>
                                <select class="form-select" id="spk_id" name="spk_id">
                                    <option value="">All SPK</option>
                                    @foreach ($spks as $spk)
                                        <option value="{{ $spk->id }}"
                                            {{ request('spk_id') == $spk->id ? 'selected' : '' }}>
                                            {{ $spk->spk_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-4 col-lg-2">
                                <label for="vessel_id" class="form-label">Vessel</label>
                                <select class="form-select" id="vessel_id" name="vessel_id">
                                    <option value="">All Vessel</option>
                                    @foreach ($vessels as $vessel)
                                        <option value="{{ $vessel->id }}"
                                            {{ request('vessel_id') == $vessel->id ? 'selected' : '' }}>
                                            {{ $vessel->vessel_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-4 col-lg-2">
                                <label for="fuel_id" class="form-label">Fuel</label>
                                <select class="form-select" id="fuel_id" name="fuel_id">
                                    <option value="">All Fuel</option>
                                    @foreach ($fuels as $fuel)
                                        <option value="{{ $fuel->id }}"
                                            {{ request('fuel_id') == $fuel->id ? 'selected' : '' }}>
                                            {{ $fuel->fuel_type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-4 col-lg-2">
                                <label for="status_shipment" class="form-label">Status</label>
                                <select class="form-select" id="status_shipment" name="status_shipment">
                                    <option value="">All Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ request('status_shipment') == $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-4 col-lg-2">
                                <label for="per_page" class="form-label">Show</label>
                                <select class="form-select" id="per_page" name="per_page" onchange="this.form.submit()">
                                    @foreach ([10, 20, 30, 100, 1000] as $option)
                                        <option value="{{ $option }}"
                                            {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Baris Kedua (Reset Button) -->
                            <div class="col-12 col-lg-2 d-flex align-items-end">
                                <a href="{{ route('shipments.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-sync-alt me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mb-2 text-muted small">
                Showing {{ $shipments->firstItem() }}–{{ $shipments->lastItem() }} of {{ $totalShipments }}
                shipments
            </div>

            <!-- Desktop Table -->
            <div class="card d-none d-lg-block mt-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Termin Number</th>
                                    <th>Shipment Number</th>
                                    <th>Vessel</th>
                                    <th>SPK</th>
                                    <th>Location</th>
                                    <th>Fuel</th>
                                    <th>Volume (L)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shipments as $shipment)
                                    <tr>
                                        <td>{{ $shipments->firstItem() + $loop->index }}</td>
                                        <td>{{ 'Termin ' . $shipment->termin->termin_number ?? '-' }}</td>
                                        <td>
                                            <button class="dropdown-item text-info view-details"
                                                data-id="{{ $shipment->id }}">
                                                {{ 'Shipment ' . $shipment->shipment_number ?? '-' }}
                                            </button>
                                        </td>
                                        <td>{{ $shipment->vessel->vessel_name ?? '-' }}</td>
                                        <td><a href="{{ asset('storage/' . $shipment->spk->spk_file) }}"
                                                target="_blank">{{ $shipment->spk->spk_number ?? '-' }}</a></td>
                                        <td>{{ $shipment->location }}</td>
                                        <td>{{ $shipment->fuel->fuel_type ?? '-' }}</td>
                                        <td>{{ number_format($shipment->volume, 0, ',', '.') }}</td>
                                        <td><span
                                                class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $shipment->status_shipment)) }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-dark" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button class="dropdown-item text-info view-details"
                                                            data-id="{{ $shipment->id }}">
                                                            <i class="fas fa-eye me-1"></i> View Details
                                                        </button>
                                                    </li>
                                                    @if (Auth::check() && Auth::user()->rolePermissions->contains('permission', 'admin_officer'))
                                                        <li>
                                                            <a class="dropdown-item text-primary"
                                                                href="{{ route('shipments.edit', $shipment->id) }}">
                                                                <i class="fas fa-edit me-1"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('shipments.destroy', $shipment->id) }}"
                                                                method="POST" onsubmit="return confirmDelete(event)">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item text-warning"
                                                            href="{{ route('shipments.upload.documents', $shipment->id) }}">
                                                            <i class="fas fa-upload me-1"></i> Upload Document
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <img src="{{ asset('assets/img/empty-box.png') }}" alt="Empty state"
                                                    style="height: 120px; opacity: 0.7;" class="mb-3">
                                                <h5 class="text-muted">No Shipments Found</h5>
                                                <p class="text-muted mb-3">You haven't added any Shipments yet</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="p-3">
                    {{ $shipments->links('pagination::bootstrap-5') }}
                </div>

            </div>

            <!-- Mobile Card List -->
            <div class="d-lg-none mt-3">
                @forelse ($shipments as $shipment)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="dropdown-item text-primary view-details" data-id="{{ $shipment->id }}">
                                        Shipment {{ $shipment->shipment_number }}</h6>
                                    <span class="badge bg-secondary small">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status_shipment)) }}
                                    </span>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item text-info view-details"
                                                data-id="{{ $shipment->id }}">
                                                <i class="fas fa-eye me-1"></i> View Details
                                            </button>
                                        </li>
                                        @if (Auth::check() && Auth::user()->rolePermissions->contains('permission', 'admin_officer'))
                                            <li>
                                                <a class="dropdown-item text-primary"
                                                    href="{{ route('shipments.edit', $shipment->id) }}">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('shipments.destroy', $shipment->id) }}"
                                                    method="POST" onsubmit="return confirmDelete(event)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        <li>
                                            <a class="dropdown-item text-warning"
                                                href="{{ route('shipments.upload.documents', $shipment->id) }}">
                                                <i class="fas fa-upload me-1"></i> Upload Document
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <ul class="list-unstyled small mb-0">
                                <li class="mb-1"><strong>Termin:</strong>
                                    {{ 'Termin ' . $shipment->termin->termin_number ?? '-' }}</li>
                                <li class="mb-1"><strong>Vessel:</strong> {{ $shipment->vessel->vessel_name ?? '-' }}
                                </li>
                                <li class="mb-1"><strong>SPK:</strong> <a
                                        href="{{ asset('storage/' . $shipment->spk->spk_file) }}"
                                        target="_blank">{{ $shipment->spk->spk_number ?? '-' }}</a></li>
                                <li class="mb-1"><strong>Location:</strong> {{ $shipment->location }}</li>
                                <li class="mb-1"><strong>Fuel:</strong> {{ $shipment->fuel->fuel_type ?? '-' }}</li>
                                <li><strong>Volume:</strong> {{ number_format($shipment->volume, 0, ',', '.') }} Liter</li>
                            </ul>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <img src="{{ asset('assets/img/empty-box.png') }}" alt="Empty state"
                                    style="height: 100px; opacity: 0.7;" class="mb-3">
                                <h5 class="text-muted">No Shipments Available</h5>
                                <p class="text-muted mb-3">Get started by adding a new Shipment</p>
                            </div>
                        </div>
                    </div>
                @endforelse
                <div class="p-3">
                    {{ $shipments->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>

    <!-- Detail Slider -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="detailSlider" aria-labelledby="detailSliderLabel">
        <div class="offcanvas-header bg-light">
            <h5 class="offcanvas-title" id="detailSliderLabel">Shipment Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" id="detailContent">
            <!-- Content will be loaded here via AJAX -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert Confirmation -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            showAlert('success', '{{ session('success') }}');
        @endif
        @if (session('error'))
            showAlert('error', '{{ session('error') }}');
        @endif
        @if ($errors->any())
            showAlert('error', '{{ $errors->first() }}');
        @endif

        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            const alertEl = document.createElement('div');

            alertEl.id = alertId;
            alertEl.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
            alertEl.role = 'alert';
            alertEl.style.cssText =
                `position: relative; overflow: hidden; border: none; border-left: 4px solid ${type === 'success' ? '#28a745' : '#dc3545'}; animation: slideIn 0.3s ease-out forwards; margin-bottom: 10px;`;

            let icon = type === 'success' ?
                '<i class="fas fa-check-circle me-2"></i>' :
                '<i class="fas fa-exclamation-circle me-2"></i>';

            alertEl.innerHTML = `
                <div class="d-flex align-items-center">
                    <div style="font-size: 1.5rem; color: ${type === 'success' ? '#28a745' : '#dc3545'};">${icon}</div>
                    <div><strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            alertContainer.appendChild(alertEl);

            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.style.animation = 'fadeOut 0.3s ease-out forwards';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }

        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    popup: 'animated bounceIn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // View Details functionality
        document.addEventListener('DOMContentLoaded', function() {
            const viewDetailButtons = document.querySelectorAll('.view-details');

            viewDetailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const shipmentId = this.getAttribute('data-id');
                    const offcanvas = new bootstrap.Offcanvas(document.getElementById(
                        'detailSlider'));

                    // Show loading state
                    document.getElementById('detailContent').innerHTML = `
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `;

                    // Fetch data via AJAX
                    fetch(`/shipments/${shipmentId}/details`)
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('detailContent').innerHTML = html;
                        })
                        .catch(error => {
                            document.getElementById('detailContent').innerHTML = `
                                <div class="alert alert-danger">Failed to load details. Please try again.</div>
                            `;
                        });

                    offcanvas.show();
                });
            });
        });

        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes fadeOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        // Auto-submit form when any filter changes (optional)
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const filterSelects = filterForm.querySelectorAll('select');

            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });
        });

        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            window.location.href = url.toString();
        }
    </script>
@endsection

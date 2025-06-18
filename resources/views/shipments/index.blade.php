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
                <div>
                    <a href="{{ route('shipments.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Shipment
                    </a>
                </div>
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
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ 'Termin ' . $shipment->termin->termin_number ?? '-' }}</td>
                                        <td>{{ 'Shipment ' . $shipment->shipment_number ?? '-' }}</td>
                                        <td>{{ $shipment->vessel->vessel_name ?? '-' }}</td>
                                        <td>{{ $shipment->spk->spk_number ?? '-' }}</td>
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
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
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
            </div>

            <!-- Mobile Card List -->
            <div class="d-lg-none mt-3">
                @forelse ($shipments as $shipment)
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6 class="fw-bold mb-1">{{ 'Shipment ' . $shipment->shipment_number }}</h6>
                            <p class="text-muted mb-1">Vessel: {{ $shipment->vessel->vessel_name ?? '-' }}</p>
                            <p class="text-muted mb-1">Location: {{ $shipment->location }}</p>
                            <p class="text-muted mb-1">Fuel: {{ $shipment->fuel->fuel_type ?? '-' }}</p>
                            <p class="text-muted mb-1">Volume: {{ $shipment->volume }} Liter</p>
                            <p class="text-muted mb-2">
                                Status: <span
                                    class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $shipment->status_shipment)) }}</span>
                            </p>
                            <div class="dropdown float-end">
                                <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item text-info view-details" data-id="{{ $shipment->id }}">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </button>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-primary"
                                            href="{{ route('shipments.edit', $shipment->id) }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST"
                                            onsubmit="return confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash-alt me-1"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
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
    </script>
@endsection

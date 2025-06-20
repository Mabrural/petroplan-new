<!DOCTYPE html>
<html lang="en">

@include('layouts.head')

<body>
    <div class="wrapper">
        @include('layouts.sidebar')

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand"
                                class="navbar-brand" height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                @include('layouts.navbar')

            </div>

            @yield('container')

            @include('layouts.footer')
        </div>

    </div>
    @include('layouts.script')
    @stack('scripts')

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (!session('active_period_id') && isset($allPeriods))
        <form id="setPeriodForm" action="{{ route('set.period') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="period_id" id="selectedPeriodId">
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const periods = @json($allPeriods);

                let htmlContent = '<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem;">';

                periods.forEach(p => {
                    htmlContent += `
                    <div class="period-card" data-id="${p.id}"
                        style="
                            border: 1px solid #ddd;
                            border-radius: 10px;
                            padding: 1rem;
                            min-width: 150px;
                            text-align: center;
                            cursor: pointer;
                            background-color: #f8f9fa;
                            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                            transition: all 0.3s;
                        "
                        onmouseover="this.style.backgroundColor='#e2e6ea'"
                        onmouseout="this.style.backgroundColor='#f8f9fa'"
                    >
                        <strong>${p.name}</strong>
                    </div>`;
                });

                htmlContent += '</div>';

                Swal.fire({
                    title: 'Select Active Period',
                    html: htmlContent,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        document.querySelectorAll('.period-card').forEach(card => {
                            card.addEventListener('click', function() {
                                const selectedId = this.getAttribute('data-id');
                                document.getElementById('selectedPeriodId').value =
                                    selectedId;
                                document.getElementById('setPeriodForm').submit();
                            });
                        });
                    }
                });
            });
        </script>
    @endif




</body>

</html>

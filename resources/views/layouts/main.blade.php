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

    @if (!session('active_period_id') && isset($allPeriods))

        <!-- Active Period Selection Modal -->
        <div class="modal fade" id="periodModal" tabindex="-1" aria-labelledby="periodModalLabel" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered"> {{-- Center the modal vertically --}}
                <form action="{{ route('set.period') }}" method="POST" class="modal-content shadow">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="periodModalLabel">Select Active Period</h5>
                    </div>
                    <div class="modal-body">
                        <select name="period_id" class="form-select" required>
                            <option value="">-- Choose Period --</option>
                            @foreach ($allPeriods as $periode)
                                <option value="{{ $periode->id }}">{{ $periode->name }} ({{ $periode->year }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Set Period</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (!session('active_period_id') && isset($allPeriods))
                var periodModal = new bootstrap.Modal(document.getElementById('periodModal'));
                periodModal.show();
            @endif
        });
    </script>



</body>

</html>

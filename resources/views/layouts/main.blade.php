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
                            <img
                                src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}"
                                alt="navbar brand"
                                class="navbar-brand"
                                height="20" />
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

</body>

</html>
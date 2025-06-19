<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo text-white text-decoration-none fw-bold d-flex align-items-center"
                style="font-size: 20px;">
                <i class="fas fa-gas-pump me-2"></i> PetroPlan
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if (Auth::check() && Auth::user()->rolePermissions->contains('permission', 'admin_officer'))
                    <li class="nav-item {{ request()->routeIs('period-list.*') ? 'active' : '' }}">
                        <a href="{{ route('period-list.index') }}">
                            <i class="fas fa-calendar-check"></i>
                            <p>Period Management</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('termins.*') ? 'active' : '' }}">
                        <a href="{{ route('termins.index') }}">
                            <i class="fas fa-calendar-alt"></i>
                            <p>Termin Management</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('spks.*') ? 'active' : '' }}">
                        <a href="{{ route('spks.index') }}">
                            <i class="fas fa-file-contract"></i>
                            <p>SPK Management</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->is_admin == true)
                    <li class="nav-item {{ request()->routeIs('vessels.*') ? 'active' : '' }}">
                        <a href="{{ route('vessels.index') }}">
                            <i class="fas fa-ship"></i>
                            <p>Vessel Registry</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item {{ request()->routeIs('shipments.*') ? 'active' : '' }}">
                    <a href="{{ route('shipments.index') }}">
                        <i class="fas fa-boxes"></i>
                        <p>Shipment Management</p>
                    </a>
                </li>

                @if (Auth::user()->is_admin == true)
                    <li class="nav-item {{ request()->routeIs('document-types.*') ? 'active' : '' }}">
                        <a href="{{ route('document-types.index') }}">
                            <i class="fas fa-file-alt"></i>
                            <p>Document Types</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item {{ request()->routeIs('upload-shipment-documents.*') ? 'active' : '' }}">
                    <a href="{{ route('upload-shipment-documents.index') }}">
                        <i class="fas fa-upload"></i>
                        <p>Document Uploads</p>
                    </a>
                </li>

                @if (Auth::user()->is_admin == true)
                    <li class="nav-item {{ request()->routeIs('fuels.*') ? 'active' : '' }}">
                        <a href="{{ route('fuels.index') }}">
                            <i class="fas fa-gas-pump"></i>
                            <p>Fuel Types</p>
                        </a>
                    </li>
                @endif

                <li
                    class="nav-item 
                        {{ request()->is('shipment-summary-report') ||
                        request()->is('vessel-activity-chart') ||
                        request()->is('fuel-usage-analysis')
                            ? 'active'
                            : '' }}">
                    <a data-bs-toggle="collapse" href="#reportAnalytics"
                        class="{{ request()->is('shipment-summary-report') ||
                        request()->is('vessel-activity-chart') ||
                        request()->is('fuel-usage-analysis')
                            ? ''
                            : 'collapsed' }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Report & Analytics</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse 
                        {{ request()->is('shipment-summary-report') ||
                        request()->is('vessel-activity-chart') ||
                        request()->is('fuel-usage-analysis')
                            ? 'show'
                            : '' }}"
                        id="reportAnalytics">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('shipment-summary-report') ? 'active' : '' }}">
                                <a href="{{ url('/shipment-summary-report') }}">
                                    <span class="sub-item">Shipment Summary Report</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('vessel-activity-chart') ? 'active' : '' }}">
                                <a href="{{ url('/vessel-activity-chart') }}">
                                    <span class="sub-item">Vessel Activity Chart</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('fuel-usage-analysis') ? 'active' : '' }}">
                                <a href="{{ url('/fuel-usage-analysis') }}">
                                    <span class="sub-item">Fuel Usage Analysis</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


                @if (Auth::user()->is_admin == true)
                    <li
                        class="nav-item {{ request()->routeIs('user-management.*') || request()->routeIs('role-permissions.*') ? 'active' : '' }}">
                        <a data-bs-toggle="collapse" href="#userAccess"
                            class="{{ request()->routeIs('user-management.*') || request()->routeIs('role-permissions.*') ? '' : 'collapsed' }}">
                            <i class="fas fa-users-cog"></i>
                            <p>User Access</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ request()->routeIs('user-management.*') || request()->routeIs('role-permissions.*') ? 'show' : '' }}"
                            id="userAccess">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('user-management.*') ? 'active' : '' }}"><a
                                        href="{{ route('user-management.index') }}"><span class="sub-item">User
                                            Management</span></a></li>
                                <li class="{{ request()->routeIs('role-permissions.*') ? 'active' : '' }}"><a
                                        href="{{ route('role-permissions.index') }}"><span class="sub-item">Role &
                                            Permissions</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ url('/settings') }}">
                        <i class="fas fa-cogs"></i>
                        <p>Settings</p>
                        {{-- <span class="badge badge-secondary">1</span> --}}
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->

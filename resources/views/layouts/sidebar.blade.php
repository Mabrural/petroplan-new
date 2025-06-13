<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo text-white text-decoration-none fw-bold d-flex align-items-center" style="font-size: 20px;">
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

                <li class="nav-item {{ request()->routeIs('vessels.*') ? 'active' : '' }}">
                    <a href="{{ route('vessels.index') }}">
                        <i class="fas fa-ship"></i>
                        <p>Vessel Registry</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#maps">
                        <i class="fas fa-boxes"></i>
                        <p>Shipment Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="maps">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ url('/shipment-list') }}"><span class="sub-item">Shipment List</span></a></li>
                            <li><a href="{{ url('/shipment-list/create') }}"><span class="sub-item">Create Shipment</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts">
                        <i class="fas fa-upload"></i>
                        <p>Document Uploads</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="sidebarLayouts">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ url('/upload-shipment-document') }}"><span class="sub-item">Upload Shipment Documents</span></a></li>
                            <li><a href="{{ url('/document-type') }}"><span class="sub-item">Document Types</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->routeIs('fuels.*') ? 'active' : '' }}">
                    <a href="{{ route('fuels.index') }}">
                        <i class="fas fa-gas-pump"></i>
                        <p>Fuel Types</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#reportAnalitics">
                        <i class="fas fa-chart-bar"></i>
                        <p>Report & Analytics</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="reportAnalitics">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ url('/shipment-summary-report') }}"><span class="sub-item">Shipment Summary Report</span></a></li>
                            <li><a href="{{ url('/vessel-activity-chart') }}"><span class="sub-item">Vessel Activity Chart</span></a></li>
                            <li><a href="{{ url('/bbm-usage-analysis') }}"><span class="sub-item">BBM Usage Analysis</span></a></li>
                            <li><a href="{{ url('/export-data') }}"><span class="sub-item">Export Data (Excel/CSV)</span></a></li>
                            <li><a href="{{ url('/print-report') }}"><span class="sub-item">Print Reports (PDF)</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->routeIs('user-management.*') || request()->routeIs('role-permissions.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#userAccess" class="{{ request()->routeIs('user-management.*') || request()->routeIs('role-permissions.*') ? '' : 'collapsed' }}">
                        <i class="fas fa-users-cog"></i>
                        <p>User Access</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('user-management.*') || request()->routeIs('role-permissions.*') ? 'show' : '' }}" id="userAccess">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('user-management.*') ? 'active' : '' }}"><a href="{{ route('user-management.index') }}"><span class="sub-item">User Management</span></a></li>
                            <li class="{{ request()->routeIs('role-permissions.*') ? 'active' : '' }}"><a href="{{ route('role-permissions.index') }}"><span class="sub-item">Role & Permissions</span></a></li>
                        </ul>
                    </div>
                </li>

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
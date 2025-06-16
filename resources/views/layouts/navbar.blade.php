<!-- Navbar Header -->
<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">
        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <!-- Period Dropdown -->
            <li class="nav-item dropdown me-3">
                <a class="nav-link dropdown-toggle text-dark" href="#" id="dropdownPeriod" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $activePeriod?->name ?? 'Pilih Periode' }}
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownPeriod">
                    @foreach ($allPeriods as $periode)
                        <li>
                            <form action="{{ route('set.period') }}" method="POST">
                                @csrf
                                <input type="hidden" name="period_id" value="{{ $periode->id }}">
                                <button class="dropdown-item {{ $periode->id == $activePeriodId ? 'active' : '' }}"
                                    type="submit">
                                    {{ $periode->name }}
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </li>

            <!-- User Profile -->
            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="{{ asset('assets/img/user-286.png') }}" alt="..."
                            class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                        <span class="op-7">Hi,</span>
                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <img src="{{ asset('assets/img/user-286.png') }}" alt="image profile"
                                        class="avatar-img rounded" />
                                </div>
                                <div class="u-text">
                                    <h4>{{ Auth::user()->name }}</h4>
                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profile</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<!-- End Navbar -->
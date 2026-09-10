<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bad Loan Recovery Management') - Rastriya Banijya Bank</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Brand UI Design System CSS -->
    <link href="{{ asset('css/brand-ui.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>

    <!-- Sidebar Navigation -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <div class="bank-logo-panel mb-2">
                <img src="{{ asset('assets/logo.png') }}" alt="Rastriya Banijya Bank Logo">
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <span class="eyebrow" style="color: #cbd7f6; font-size: 9.5px;">PLRD RECOVERY PORTAL</span>
            </div>
        </div>

        <div class="sidebar-user-block">
            <div class="text-white-50" style="font-size: 11px;">Signed in as:</div>
            <strong class="text-white d-block text-truncate"
                style="font-size: 13px;">{{ Auth::user()->name ?? 'Guest' }}</strong>
            <div class="mt-1">
                @if (Auth::check() && Auth::user()->isCentral())
                    <span class="badge badge-brand-accent">
                        <i class="bi bi-shield-lock-fill me-1"></i>Central Recovery Staff
                    </span>
                @elseif(Auth::check() && Auth::user()->isBranch())
                    <span class="badge badge-brand">
                        <i class="bi bi-geo-alt-fill me-1"></i>Branch: {{ Auth::user()->branch->code ?? 'N/A' }}
                    </span>
                @endif
            </div>
        </div>

        <ul class="nav flex-column mt-2">
            <li class="section-divider">Loan Records</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bad-loans.index') ? 'active' : '' }}"
                    href="{{ route('bad-loans.index') }}">
                    <i class="bi bi-table"></i>
                    <span>Bad Loans Directory</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bad-loans.create') ? 'active' : '' }}"
                    href="{{ route('bad-loans.create') }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>New Bad Loan Entry</span>
                </a>
            </li>

            @if (Auth::check() && Auth::user()->isCentral())
                <li class="section-divider">Central Recovery Modules</li>
                <li class="nav-item">
                    <span class="nav-link text-white-50" title="Select a loan from list to manage recovery stages">
                        <i class="bi bi-arrow-repeat text-warning"></i>
                        <span>Recovery Pipeline (6)</span>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                        href="{{ route('users.index') }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Users & Roles</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}"
                        href="{{ route('branches.index') }}">
                        <i class="bi bi-buildings-fill"></i>
                        <span>Branch Directory</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <!-- Main Content Area -->
    <div id="main-content">
        <!-- Top Header -->
        <header class="top-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0 fw-bold text-dark">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                @auth
                    <span class="text-muted small">
                        <i class="bi bi-person-circle me-1 text-primary"></i>{{ Auth::user()->email }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Sign Out
                        </button>
                    </form>
                @endauth
            </div>
        </header>

        <!-- Flash Notifications -->
        <div class="container-fluid px-4 pt-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert"
                    style="background-color: var(--status-success-bg); border-color: var(--status-success-border); color: var(--status-success-text); border-radius: 8px;">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert"
                    style="background-color: var(--status-error-bg); border-color: var(--status-error-border); color: var(--status-error-text); border-radius: 8px;">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert"
                    style="background-color: var(--status-error-bg); border-color: var(--status-error-border); color: var(--status-error-text); border-radius: 8px;">
                    <strong class="d-flex align-items-center mb-1">
                        <i class="bi bi-shield-x me-2"></i>Please resolve the following errors:
                    </strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Page Dynamic Body -->
        <main class="container-fluid px-4 py-3 flex-grow-1">
            @yield('content')
        </main>

        <footer
            class="bg-white py-3 px-4 border-top text-center text-muted small d-flex justify-content-between align-items-center">
            <span>Rastriya Banijya Bank Limited &bull; Problem Loan Recovery Department (PLRD)</span>
            <span>Version 2.0 &bull; Secure Banking Portal</span>
        </footer>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>

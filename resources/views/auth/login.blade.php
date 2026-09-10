<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Bad Loan Recovery Portal | Rastriya Banijya Bank</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Brand UI Design System CSS -->
    <link href="{{ asset('css/brand-ui.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: var(--bg-canvas);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1060px;
            margin: 0 auto;
        }

        /* Two-Column Split Layout */
        .login-layout {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 48px;
            align-items: center;
        }

        /* Column 1: Branding & Context */
        .login-branding-col {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 16px 20px 16px 0;
        }

        .login-branding-col .login-bank-logo {
            display: block;
            max-width: 290px;
            width: 100%;
            margin-bottom: 26px;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            border: 1px solid var(--border-card);
            box-shadow: 0 4px 12px rgba(20, 44, 70, 0.04);
        }

        .login-branding-col .login-bank-logo img {
            display: block;
            max-width: 100%;
            height: auto;
        }

        .login-brand-text .eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.6px;
            color: var(--brand-primary);
            text-transform: uppercase;
            display: block;
            margin-bottom: 8px;
        }

        .login-brand-text h1 {
            font-size: 30px;
            font-weight: 700;
            color: var(--brand-primary);
            line-height: 1.25;
            margin: 0 0 14px 0;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 14px;
            line-height: 1.6;
            color: #556579;
            margin: 0 0 22px 0;
        }

        .login-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .login-badges .badge-pill {
            display: inline-block;
            background: var(--brand-tint);
            color: var(--brand-primary);
            padding: 5px 12px;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid #d4def7;
        }

        /* Column 2: Authentication Card */
        .login-form-col {
            width: 100%;
        }

        .card.login {
            background: #ffffff;
            border: 1px solid var(--border-card);
            border-top: 3px solid var(--brand-accent);
            border-radius: 12px;
            padding: 34px 30px;
            box-shadow: 0 6px 20px rgba(33, 58, 143, 0.08);
        }

        .card.login h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 4px;
        }

        .auth-tabs {
            display: flex;
            border-bottom: 2px solid #e1e7ef;
            margin: 18px 0 22px 0;
            gap: 4px;
        }

        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 10px 14px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.15s ease;
            border-radius: 6px 6px 0 0;
            cursor: pointer;
        }

        .auth-tab:hover {
            color: var(--brand-primary);
            background: var(--brand-tint);
        }

        .auth-tab.active {
            color: var(--brand-primary);
            border-bottom-color: var(--brand-primary);
            background: transparent;
        }

        /* Responsive Breakpoints */
        @media (max-width: 800px) {
            .login-layout {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .login-branding-col {
                align-items: center;
                text-align: center;
                padding-right: 0;
            }

            .login-badges {
                justify-content: center;
            }

            .card.login {
                max-width: 480px;
                margin: 0 auto;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="login-layout">
            <!-- Column 1: Official Brand Identity & Context -->
            <div class="login-branding-col">
                <div class="login-bank-logo">
                    <img src="{{ asset('assets/logo.png') }}" alt="Rastriya Banijya Bank Logo">
                </div>
                <div class="login-brand-text">
                    <span class="eyebrow">PROBLEM LOAN RECOVERY DEPARTMENT</span>
                    <h1>Bad Loan Status Management Portal</h1>
                    <p class="login-subtitle">
                        Centralized platform for branch officers and central loan recovery teams to monitor overdue
                        loans, manage legal recovery pipelines, and enforce resolution stages in compliance with central
                        regulatory guidelines.
                    </p>
                    <div class="login-badges">
                        <span class="badge-pill"><i class="bi bi-shield-check me-1"></i> Rastriya Banijya Bank</span>
                        <span class="badge-pill"><i class="bi bi-diagram-3-fill me-1"></i> PLRD Recovery Portal</span>
                        <span class="badge-pill"><i class="bi bi-lock-fill me-1"></i> Secure Banking Access</span>
                    </div>
                </div>
            </div>

            <!-- Column 2: Authentication Card & Form -->
            <div class="login-form-col">
                <section class="card login">
                    <h2>Sign in</h2>
                    <p class="text-muted small mb-0">Enter your official banking credentials to continue.</p>

                    <!-- Quick Role Selector Tabs -->
                    <div class="auth-tabs" role="tablist">
                        <button type="button" class="auth-tab active" id="tabCentral" onclick="selectRole('central')">
                            <i class="bi bi-shield-lock-fill me-1"></i> Central Staff
                        </button>
                        <button type="button" class="auth-tab" id="tabBranch" onclick="selectRole('branch')">
                            <i class="bi bi-building me-1"></i> Branch Officer
                        </button>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success py-2 px-3 small rounded mb-3"
                            style="background-color: var(--status-success-bg); border-color: var(--status-success-border); color: var(--status-success-text);">
                            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 px-3 small rounded mb-3"
                            style="background-color: var(--status-error-bg); border-color: var(--status-error-border); color: var(--status-error-text);">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Official Email <span class="required">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', 'central@bank.com') }}" required autofocus
                                    placeholder="name@bank.com">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password <span class="required">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" value="password"
                                    required placeholder="Enter password">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember" checked>
                                <label class="form-check-label text-muted small" for="remember">Remember this
                                    device</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand-primary w-100 py-2 fs-6">
                            Sign in to Portal &rarr;
                        </button>
                    </form>

                    <div class="mt-4 pt-3 border-top text-center">
                        <span class="text-muted small" style="font-size: 11.5px;">
                            <i class="bi bi-info-circle me-1 text-primary"></i>
                            Need account access or branch re-assignment? Contact the IT System Administrator.
                        </span>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        function selectRole(role) {
            const tabCentral = document.getElementById('tabCentral');
            const tabBranch = document.getElementById('tabBranch');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            if (role === 'central') {
                tabCentral.classList.add('active');
                tabBranch.classList.remove('active');
                emailInput.value = 'central@bank.com';
                passwordInput.value = 'password';
            } else {
                tabBranch.classList.add('active');
                tabCentral.classList.remove('active');
                emailInput.value = 'branch@bank.com';
                passwordInput.value = 'password';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

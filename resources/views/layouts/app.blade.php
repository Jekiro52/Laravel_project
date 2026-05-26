<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student, Degree, and Teacher Management - @yield('title', 'Dashboard')</title>
    <meta name="color-scheme" content="dark">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        :root {
            --bg-base: #080e1a;
            --bg-surface: #121c30;
            --bg-soft: #172642;
            --line: #2a3b5f;
            --text: #e7eefb;
            --muted: #94a9cc;
            --accent: #4f8cff;
            --accent-soft: #63cbff;
            --danger: #ff6b8a;
            --success: #3fd39c;
        }

        body.app-shell {
            min-height: 100vh;
            background:
                radial-gradient(circle at 15% -20%, rgba(79, 140, 255, 0.32), transparent 38%),
                radial-gradient(circle at 95% 0%, rgba(99, 203, 255, 0.2), transparent 30%),
                linear-gradient(180deg, #070d18 0%, #0d1629 60%, #0b1220 100%);
            color: var(--text);
            font-family: 'Manrope', sans-serif;
        }

        .top-nav {
            background: rgba(7, 14, 26, 0.84);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.45rem 0;
        }

        .top-nav .container {
            min-height: 4.8rem;
        }

        .navbar-brand {
            color: #f1f6ff !important;
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: 0.02em;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            background: linear-gradient(120deg, rgba(79, 140, 255, 0.34), rgba(99, 203, 255, 0.3));
            border: 1px solid rgba(99, 203, 255, 0.35);
            transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .navbar-brand:hover,
        .navbar-brand:focus {
            background: linear-gradient(120deg, rgba(79, 140, 255, 0.5), rgba(99, 203, 255, 0.42));
            border-color: rgba(99, 203, 255, 0.6);
            box-shadow: 0 0 0 0.18rem rgba(79, 140, 255, 0.22);
        }

        .brand-dot {
            width: 0.65rem;
            height: 0.65rem;
            border-radius: 999px;
            background: linear-gradient(130deg, var(--accent) 20%, var(--accent-soft) 100%);
            box-shadow: 0 0 12px rgba(99, 203, 255, 0.7);
        }

        .navbar .nav-link {
            color: var(--muted);
            border-radius: 999px;
            font-size: 1.02rem;
            padding: 0.62rem 1.15rem;
            transition: all 0.2s ease;
        }

        .navbar .nav-link:hover {
            color: #e8f2ff;
            background-color: rgba(79, 140, 255, 0.15);
        }

        .navbar .nav-link.active {
            color: #ffffff;
            background: linear-gradient(100deg, rgba(79, 140, 255, 0.3), rgba(99, 203, 255, 0.28));
        }

        .navbar-toggler {
            border-color: rgba(148, 169, 204, 0.45);
            padding: 0.48rem 0.7rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.18rem rgba(79, 140, 255, 0.32);
        }

        .page-wrap {
            padding-top: 2.15rem;
            padding-bottom: 2.75rem;
        }

        .page-header {
            margin-bottom: 1.4rem;
        }

        .page-title {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            letter-spacing: 0.01em;
            color: #f2f7ff;
        }

        .page-subtitle {
            color: var(--muted);
        }

        .card {
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.015));
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.34);
            overflow: hidden;
        }

        .card-header {
            border-bottom: 1px solid var(--line);
            background: rgba(9, 17, 30, 0.7);
            color: #f0f6ff;
            font-weight: 600;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text);
            --bs-table-border-color: var(--line);
            margin-bottom: 0;
        }

        .table > :not(caption) > * > * {
            color: #eaf2ff;
        }

        .table thead th {
            color: #c0d0ea;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-weight: 600;
            border-bottom: 1px solid var(--line);
            background-color: rgba(10, 18, 31, 0.72);
        }

        .table-striped > tbody > tr:nth-of-type(odd) > * {
            --bs-table-accent-bg: rgba(255, 255, 255, 0.03);
        }

        .table-hover > tbody > tr:hover > * {
            --bs-table-accent-bg: rgba(79, 140, 255, 0.12);
            color: #ffffff;
        }

        .form-label {
            color: #cad7ef;
            font-weight: 500;
        }

        .form-control,
        .form-select {
            background: rgba(9, 17, 30, 0.76);
            border: 1px solid var(--line);
            color: var(--text);
        }

        .form-control::placeholder {
            color: #7f91b2;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(9, 17, 30, 0.92);
            color: var(--text);
            border-color: rgba(99, 203, 255, 0.72);
            box-shadow: 0 0 0 0.2rem rgba(99, 203, 255, 0.16);
        }

        .form-select option {
            color: #0e1525;
        }

        .list-group-item {
            color: #f2f7ff;
            border-color: var(--line);
            background: transparent;
        }

        .list-group-item strong {
            color: #cae0ff;
        }

        .list-group-item small,
        .list-group-item span,
        .list-group-item a {
            color: #eaf2ff;
        }

        .btn {
            font-weight: 600;
            border-radius: 0.68rem;
            border-width: 1px;
        }

        .btn-primary {
            border: none;
            color: #ffffff;
            background-image: linear-gradient(125deg, #508aff 0%, #3f76ea 58%, #57b4ff 100%);
            box-shadow: 0 10px 22px rgba(63, 118, 234, 0.38);
        }

        .btn-primary:hover {
            color: #ffffff;
            filter: brightness(1.04);
            transform: translateY(-1px);
        }

        .btn-secondary {
            color: #d8e8ff;
            background: rgba(148, 169, 204, 0.17);
            border-color: rgba(148, 169, 204, 0.34);
        }

        .btn-secondary:hover {
            color: #f2f7ff;
            background: rgba(148, 169, 204, 0.28);
            border-color: rgba(148, 169, 204, 0.45);
        }

        .btn-outline-primary {
            color: #98c4ff;
            border-color: rgba(79, 140, 255, 0.55);
        }

        .btn-outline-primary:hover {
            color: #ffffff;
            border-color: transparent;
            background: rgba(79, 140, 255, 0.36);
        }

        .btn-outline-secondary {
            color: #b8c6df;
            border-color: rgba(160, 177, 207, 0.45);
        }

        .btn-outline-secondary:hover {
            color: #f1f6ff;
            border-color: transparent;
            background: rgba(160, 177, 207, 0.24);
        }

        .btn-outline-danger {
            color: #ff9cb0;
            border-color: rgba(255, 107, 138, 0.55);
        }

        .btn-outline-danger:hover {
            color: #ffffff;
            border-color: transparent;
            background: rgba(255, 107, 138, 0.34);
        }

        .alert {
            border-radius: 0.85rem;
            border-width: 1px;
        }

        .alert-success {
            color: #b9f0d8;
            background-color: rgba(63, 211, 156, 0.16);
            border-color: rgba(63, 211, 156, 0.35);
        }

        .alert-danger {
            color: #ffd0da;
            background-color: rgba(255, 107, 138, 0.14);
            border-color: rgba(255, 107, 138, 0.32);
        }

        .alert-info {
            color: #c7e6ff;
            background-color: rgba(99, 203, 255, 0.16);
            border-color: rgba(99, 203, 255, 0.34);
        }

        .table-label {
            color: #f4f8ff;
            font-weight: 700;
        }

        .table-chip,
        .metric-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.72rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: rgba(79, 140, 255, 0.12);
            border: 1px solid rgba(99, 203, 255, 0.28);
            color: #bde0ff;
        }

        .table-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.45rem;
        }

        .table-action {
            width: 2.35rem;
            height: 2.35rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 0.7rem;
            border: 1px solid rgba(160, 177, 207, 0.4);
            background: rgba(9, 17, 30, 0.58);
            color: #c8d7ef;
            transition: all 0.2s ease;
        }

        .table-action:hover {
            color: #ffffff;
            transform: translateY(-1px);
        }

        .table-action svg {
            width: 1rem;
            height: 1rem;
            stroke: currentColor;
        }

        .table-action-view {
            border-color: rgba(79, 140, 255, 0.5);
            color: #98c4ff;
        }

        .table-action-view:hover {
            background: rgba(79, 140, 255, 0.28);
        }

        .table-action-edit:hover {
            background: rgba(160, 177, 207, 0.24);
        }

        .table-action-delete {
            border-color: rgba(255, 107, 138, 0.45);
            color: #ff9cb0;
        }

        .table-action-delete:hover {
            background: rgba(255, 107, 138, 0.22);
        }

        .empty-card {
            padding: 1.4rem 1.5rem;
            border: 1px dashed rgba(99, 203, 255, 0.3);
            border-radius: 1rem;
            background: rgba(18, 28, 48, 0.72);
            color: var(--muted);
        }

        .text-muted {
            color: var(--muted) !important;
        }

        @media (max-width: 767.98px) {
            .top-nav {
                padding: 0.32rem 0;
            }

            .top-nav .container {
                min-height: 4.2rem;
            }

            .navbar-brand {
                font-size: 1.08rem;
            }

            .navbar .nav-link {
                font-size: 0.98rem;
                padding: 0.55rem 0.95rem;
            }

            .page-header {
                margin-bottom: 1.2rem;
            }

            .page-actions {
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="app-shell">
    <nav class="navbar navbar-expand-lg navbar-dark top-nav">
        <div class="container">
            <a class="navbar-brand me-auto" href="{{ session('auth_user_role') === 'admin' ? route('useraccounts.index') : (session()->has('auth_user_id') ? route('welcome') : route('login')) }}">
                <span class="brand-dot"></span>
                Student, Degree, and Teacher Management
            </a>

            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto">
                    @if (session()->has('auth_user_id'))
                        @if (session('auth_user_role') === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('useraccounts.*') ? 'active' : '' }}" href="{{ route('useraccounts.index') }}">User Accounts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">Students</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('degrees.*') ? 'active' : '' }}" href="{{ route('degrees.index') }}">Degrees</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">Teachers</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <span class="nav-link active">{{ \Illuminate\Support\Str::headline(session('auth_user_role', 'user')) }}</span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main class="container page-wrap">
        <div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
            <div>
                <h1 class="page-title h3 mb-1">@yield('title', 'Dashboard')</h1>
                @hasSection('subtitle')
                    <p class="page-subtitle mb-0">@yield('subtitle')</p>
                @endif
            </div>
            @hasSection('page_actions')
                <div class="page-actions d-flex gap-2">
                    @yield('page_actions')
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info" role="alert">
                {{ session('info') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Please check the highlighted fields.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

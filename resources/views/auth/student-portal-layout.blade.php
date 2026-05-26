<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student, Degree, and Teacher Management - @yield('title', 'Portal')</title>
    <meta name="color-scheme" content="dark">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #080e1a;
            --bg-surface: rgba(15, 27, 49, 0.94);
            --bg-soft: rgba(79, 140, 255, 0.14);
            --line: rgba(148, 169, 204, 0.24);
            --line-strong: rgba(99, 203, 255, 0.32);
            --text: #ebf2ff;
            --muted: #97acce;
            --accent: #59a5ff;
            --accent-strong: #2f72ff;
            --accent-soft: #63cbff;
            --danger: #ff8da7;
            --success: #72f0bf;
            --shadow: rgba(0, 0, 0, 0.34);
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--text);
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at 15% -20%, rgba(79, 140, 255, 0.32), transparent 38%),
                radial-gradient(circle at 95% 0%, rgba(99, 203, 255, 0.2), transparent 30%),
                linear-gradient(180deg, #070d18 0%, #0d1629 60%, #0b1220 100%);
        }

        .portal-header {
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 0.85rem 1.25rem;
            background: rgba(7, 14, 26, 0.84);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.22);
        }

        .portal-header-inner {
            width: min(100%, 102rem);
            min-height: 4.8rem;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .portal-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.95rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            color: #f1f6ff;
            text-decoration: none;
            background: linear-gradient(120deg, rgba(79, 140, 255, 0.34), rgba(99, 203, 255, 0.3));
            border: 1px solid rgba(99, 203, 255, 0.35);
            transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .portal-brand:hover,
        .portal-brand:focus {
            background: linear-gradient(120deg, rgba(79, 140, 255, 0.5), rgba(99, 203, 255, 0.42));
            border-color: rgba(99, 203, 255, 0.6);
            box-shadow: 0 0 0 0.18rem rgba(79, 140, 255, 0.22);
        }

        .portal-brand-title {
            margin: 0;
            font-family: 'Sora', sans-serif;
            font-size: 1.22rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .portal-nav {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .portal-link,
        .portal-user,
        .portal-logout {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2.85rem;
            padding: 0 1.2rem;
            border-radius: 999px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        .portal-link {
            color: var(--muted);
        }

        .portal-link:hover {
            color: #e8f2ff;
            background-color: rgba(79, 140, 255, 0.15);
            transform: translateY(-1px);
        }

        .portal-link.active {
            color: #ffffff;
            background: linear-gradient(100deg, rgba(79, 140, 255, 0.3), rgba(99, 203, 255, 0.28));
        }

        .portal-user {
            color: #dbeaff;
            background: rgba(148, 169, 204, 0.17);
            border: 1px solid rgba(148, 169, 204, 0.34);
        }

        .portal-logout {
            color: #dbeaff;
            border: 1px solid rgba(148, 169, 204, 0.34);
            background: rgba(148, 169, 204, 0.14);
            cursor: pointer;
            font: inherit;
        }

        .portal-logout:hover {
            color: #ffffff;
            background: rgba(148, 169, 204, 0.26);
            transform: translateY(-1px);
        }

        .portal-page {
            width: min(100%, 102rem);
            margin: 0 auto;
            padding: 2.6rem 1.25rem 4rem;
        }

        .flash-stack {
            max-width: 100rem;
            margin: 0 auto 1.9rem;
        }

        .flash {
            padding: 1.25rem 1.35rem;
            border-radius: 0.95rem;
            border: 1px solid transparent;
            font-size: 1rem;
            font-weight: 600;
        }

        .flash + .flash {
            margin-top: 1rem;
        }

        .flash-success {
            color: #c8fae2;
            background: rgba(114, 240, 191, 0.14);
            border-color: rgba(114, 240, 191, 0.35);
        }

        .flash-info {
            color: #d5e8ff;
            background: rgba(89, 165, 255, 0.14);
            border-color: rgba(89, 165, 255, 0.35);
        }

        .flash-danger {
            color: #ffd6df;
            background: rgba(255, 141, 167, 0.14);
            border-color: rgba(255, 141, 167, 0.35);
        }

        .portal-content {
            display: flex;
            justify-content: center;
        }

        .portal-panel {
            width: min(100%, 50rem);
            padding: 3.3rem 3.5rem;
            border-radius: 1.6rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(155deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.015)),
                var(--bg-surface);
            box-shadow: 0 32px 70px var(--shadow);
        }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 1.6rem;
            padding: 0.8rem 1.2rem;
            border-radius: 999px;
            border: 1px solid var(--line-strong);
            background: rgba(79, 140, 255, 0.12);
            color: #d5e8ff;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .portal-title {
            margin: 0 0 1rem;
            font-family: 'Sora', sans-serif;
            font-size: clamp(2.1rem, 4vw, 3.3rem);
            line-height: 1.08;
            color: #f2f7ff;
        }

        .portal-copy {
            margin: 0 0 2rem;
            color: var(--muted);
            font-size: 1.06rem;
            line-height: 1.65;
        }

        .portal-field {
            margin-bottom: 1.35rem;
        }

        .portal-field label {
            display: block;
            margin-bottom: 0.65rem;
            font-size: 0.96rem;
            font-weight: 800;
            color: #d7e4fb;
        }

        .portal-field input {
            width: 100%;
            min-height: 3.9rem;
            padding: 0 1.1rem;
            border-radius: 1rem;
            border: 1px solid var(--line);
            background: rgba(7, 14, 26, 0.75);
            color: var(--text);
            font: inherit;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .portal-field input:focus {
            outline: none;
            background: rgba(7, 14, 26, 0.92);
            border-color: rgba(99, 203, 255, 0.72);
            box-shadow: 0 0 0 0.28rem rgba(99, 203, 255, 0.16);
        }

        .portal-field input.is-invalid {
            border-color: rgba(255, 141, 167, 0.5);
            box-shadow: 0 0 0 0.28rem rgba(255, 141, 167, 0.11);
        }

        .portal-error {
            display: block;
            margin-top: 0.55rem;
            color: #ffd6df;
            font-size: 0.94rem;
            font-weight: 600;
        }

        .portal-submit {
            min-height: 3.55rem;
            padding: 0 1.6rem;
            border: 0;
            border-radius: 0.9rem;
            color: #ffffff;
            font: inherit;
            font-size: 1rem;
            font-weight: 800;
            background: linear-gradient(125deg, var(--accent) 0%, var(--accent-strong) 100%);
            box-shadow: 0 18px 28px rgba(47, 114, 255, 0.24);
            cursor: pointer;
        }

        .portal-submit:hover {
            filter: brightness(1.03);
        }

        @media (max-width: 860px) {
            .portal-header-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .portal-nav {
                width: 100%;
                justify-content: flex-start;
            }

            .portal-panel {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    @php
        $isTeacher = $user->role === 'teacher';
        $portalTitle = 'Student, Degree, and Teacher Management';
        $portalHomeRoute = $isTeacher ? route('teacher.dashboard') : route('student.welcome');
        $passwordRoute = $isTeacher ? route('password.change.form') : route('student.password.form');
        $passwordRouteActive = $isTeacher
            ? request()->routeIs('password.change.*')
            : request()->routeIs('student.password.*');
        $displayName = $user->students?->full_name ?? $user->display_name;
    @endphp

    <header class="portal-header">
        <div class="portal-header-inner">
            <a href="{{ $portalHomeRoute }}" class="portal-brand">
                <h1 class="portal-brand-title">{{ $portalTitle }}</h1>
            </a>

            <nav class="portal-nav" aria-label="{{ $portalTitle }}">
                <a href="{{ $passwordRoute }}" class="portal-link {{ $passwordRouteActive ? 'active' : '' }}">
                    Change Password
                </a>
                <span class="portal-user">{{ $displayName }}</span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="portal-logout">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="portal-page">
        <div class="flash-stack">
            @if (session('success'))
                <div class="flash flash-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="flash flash-info" role="alert">
                    {{ session('info') }}
                </div>
            @endif

            @if (session('error'))
                <div class="flash flash-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="flash flash-danger" role="alert">
                    <strong>Please review the form and try again.</strong>
                </div>
            @endif
        </div>

        <section class="portal-content">
            @yield('content')
        </section>
    </main>
</body>
</html>

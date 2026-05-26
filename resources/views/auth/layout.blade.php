<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student, Degree, and Teacher Management - @yield('title', 'Account')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        :root {
            --bg-base: #09111f;
            --bg-surface: rgba(15, 27, 49, 0.92);
            --bg-soft: rgba(79, 140, 255, 0.16);
            --line: rgba(148, 169, 204, 0.24);
            --text: #ebf2ff;
            --muted: #97acce;
            --accent: #59a5ff;
            --accent-strong: #2f72ff;
            --danger: #ff8da7;
            --success: #72f0bf;
        }

        body.auth-shell {
            min-height: 100vh;
            margin: 0;
            color: var(--text);
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(89, 165, 255, 0.35), transparent 28%),
                radial-gradient(circle at right, rgba(114, 240, 191, 0.16), transparent 22%),
                linear-gradient(180deg, #07101c 0%, #0a1426 55%, #09111f 100%);
        }

        .auth-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            width: min(100%, 32rem);
            border: 1px solid var(--line);
            border-radius: 1.4rem;
            background:
                linear-gradient(150deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.02)),
                var(--bg-surface);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
            overflow: hidden;
        }

        .auth-card-body {
            padding: 2rem;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.84rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #cfe1ff;
            margin-bottom: 1rem;
        }

        .eyebrow-dot {
            width: 0.7rem;
            height: 0.7rem;
            border-radius: 999px;
            background: linear-gradient(130deg, var(--accent) 20%, var(--success) 100%);
            box-shadow: 0 0 14px rgba(89, 165, 255, 0.65);
        }

        .auth-title {
            font-family: 'Sora', sans-serif;
            font-size: clamp(1.8rem, 4vw, 2.35rem);
            margin-bottom: 0.6rem;
        }

        .auth-subtitle {
            color: var(--muted);
            margin-bottom: 1.6rem;
        }

        .form-label {
            color: #d7e4fb;
            font-weight: 500;
        }

        .form-control {
            min-height: 3rem;
            background: rgba(7, 14, 26, 0.75);
            color: var(--text);
            border: 1px solid var(--line);
        }

        .form-control::placeholder {
            color: #7e93b6;
        }

        .form-control:focus {
            background: rgba(7, 14, 26, 0.92);
            color: var(--text);
            border-color: rgba(89, 165, 255, 0.66);
            box-shadow: 0 0 0 0.22rem rgba(89, 165, 255, 0.16);
        }

        .btn-primary {
            border: none;
            min-height: 3rem;
            font-weight: 700;
            background-image: linear-gradient(125deg, var(--accent) 0%, var(--accent-strong) 100%);
            box-shadow: 0 12px 24px rgba(47, 114, 255, 0.35);
        }

        .btn-outline-light {
            color: #e7eefb;
            border-color: rgba(231, 238, 251, 0.2);
        }

        .btn-outline-light:hover {
            color: #09111f;
            background: #e7eefb;
            border-color: #e7eefb;
        }

        .alert {
            border-radius: 0.9rem;
            border-width: 1px;
        }

        .alert-success {
            color: #c8fae2;
            background: rgba(114, 240, 191, 0.14);
            border-color: rgba(114, 240, 191, 0.35);
        }

        .alert-danger {
            color: #ffd6df;
            background: rgba(255, 141, 167, 0.14);
            border-color: rgba(255, 141, 167, 0.35);
        }

        .alert-info {
            color: #d5e8ff;
            background: rgba(89, 165, 255, 0.14);
            border-color: rgba(89, 165, 255, 0.35);
        }
    </style>
</head>
<body class="auth-shell">
    <main class="auth-wrap">
        <section class="auth-card">
            <div class="auth-card-body">
                <div class="eyebrow">
                    <span class="eyebrow-dot"></span>
                    Student, Degree, and Teacher Management
                </div>

                <h1 class="auth-title">@yield('heading', 'Welcome')</h1>
                <p class="auth-subtitle">@yield('subheading')</p>

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
                        <strong>Please review the form and try again.</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </main>
</body>
</html>

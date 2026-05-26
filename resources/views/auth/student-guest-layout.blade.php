<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Student Portal')</title>
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
            --text: #ebf2ff;
            --muted: #97acce;
            --accent: #59a5ff;
            --accent-strong: #2f72ff;
            --accent-soft: #63cbff;
            --danger: #ff8da7;
            --success: #72f0bf;
            --shadow: rgba(0, 0, 0, 0.38);
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
                radial-gradient(circle at top left, rgba(89, 165, 255, 0.34), transparent 28%),
                radial-gradient(circle at right, rgba(99, 203, 255, 0.18), transparent 24%),
                linear-gradient(180deg, #07101c 0%, #0a1426 55%, #09111f 100%);
        }

        .guest-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .guest-card {
            width: min(100%, 32rem);
            padding: 2rem;
            border-radius: 1.4rem;
            border: 1px solid var(--line);
            background:
                linear-gradient(150deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.02)),
                var(--bg-surface);
            box-shadow: 0 28px 64px var(--shadow);
        }

        .portal-head {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .portal-kicker {
            margin: 0 0 0.15rem;
            font-size: 0.95rem;
            font-weight: 800;
            color: #f4f8ff;
        }

        .portal-subkicker {
            margin: 0;
            font-size: 0.9rem;
            color: var(--muted);
        }

        .guest-title {
            margin: 0 0 0.85rem;
            font-family: 'Sora', sans-serif;
            font-size: clamp(1.8rem, 4vw, 2.35rem);
            line-height: 1.08;
            color: #eef5ff;
        }

        .guest-copy {
            margin: 0 0 1.6rem;
            font-size: 1rem;
            line-height: 1.55;
            color: var(--muted);
        }

        .alert-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.05rem;
            margin-bottom: 1.4rem;
            border-radius: 1rem;
            border: 1px solid transparent;
            font-weight: 600;
        }

        .alert-danger {
            color: #ffd6df;
            border-color: rgba(255, 141, 167, 0.3);
            background: rgba(255, 141, 167, 0.14);
        }

        .alert-success {
            color: #c8fae2;
            border-color: rgba(114, 240, 191, 0.3);
            background: rgba(114, 240, 191, 0.14);
        }

        .field {
            margin-bottom: 1.45rem;
        }

        .field label {
            display: block;
            margin-bottom: 0.65rem;
            font-size: 0.95rem;
            font-weight: 800;
            color: #d7e4fb;
        }

        .field input {
            width: 100%;
            min-height: 3rem;
            padding: 0 1.15rem;
            border-radius: 1.15rem;
            border: 1px solid var(--line);
            background: rgba(7, 14, 26, 0.75);
            color: var(--text);
            font: inherit;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .field input:focus {
            outline: none;
            background: rgba(7, 14, 26, 0.92);
            border-color: rgba(89, 165, 255, 0.66);
            box-shadow: 0 0 0 0.3rem rgba(89, 165, 255, 0.14);
        }

        .field input.is-invalid {
            border-color: rgba(255, 141, 167, 0.52);
            box-shadow: 0 0 0 0.3rem rgba(255, 141, 167, 0.1);
        }

        .field-error {
            display: block;
            margin-top: 0.55rem;
            color: #ffd6df;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .submit-btn {
            width: 100%;
            min-height: 3rem;
            border: 0;
            border-radius: 999px;
            color: #ffffff;
            font: inherit;
            font-size: 1rem;
            font-weight: 800;
            background: linear-gradient(125deg, var(--accent) 0%, var(--accent-strong) 100%);
            box-shadow: 0 20px 30px rgba(47, 114, 255, 0.28);
            cursor: pointer;
        }

        .submit-btn:hover {
            filter: brightness(1.03);
        }

        .helper-copy {
            margin: 1.45rem 0 0;
            color: var(--muted);
            font-size: 0.96rem;
        }

        .helper-copy a {
            color: #d9e9ff;
            font-weight: 800;
            text-decoration: none;
        }

        .helper-copy a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        @media (max-width: 640px) {
            .guest-card {
                padding: 1.7rem;
                border-radius: 1.2rem;
            }

            .portal-head {
                margin-bottom: 1.1rem;
            }

            .guest-title {
                font-size: 2rem;
            }

            .field input,
            .submit-btn {
                min-height: 3rem;
            }
        }
    </style>
</head>
<body>
    <main class="guest-shell">
        <section class="guest-card">
            @yield('content')
        </section>
    </main>
</body>
</html>

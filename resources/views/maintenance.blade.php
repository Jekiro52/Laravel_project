<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Under Maintenance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-1: #06101f;
            --bg-2: #0a1830;
            --panel: rgba(11, 24, 45, 0.78);
            --panel-line: rgba(137, 180, 255, 0.2);
            --text: #edf4ff;
            --muted: #9fb5d8;
            --accent: #67b8ff;
            --accent-2: #4d7dff;
            --accent-3: #8bf3d2;
            --shadow: 0 30px 80px rgba(0, 0, 0, 0.42);
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            color: var(--text);
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at 12% 18%, rgba(103, 184, 255, 0.26), transparent 24%),
                radial-gradient(circle at 84% 20%, rgba(139, 243, 210, 0.12), transparent 20%),
                radial-gradient(circle at 50% 120%, rgba(77, 125, 255, 0.18), transparent 35%),
                linear-gradient(160deg, var(--bg-1) 0%, var(--bg-2) 55%, #050c18 100%);
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            inset: auto;
            border-radius: 999px;
            filter: blur(10px);
            opacity: 0.65;
            pointer-events: none;
        }

        body::before {
            top: 9%;
            left: -6rem;
            width: 18rem;
            height: 18rem;
            background: radial-gradient(circle, rgba(103, 184, 255, 0.34), transparent 68%);
            animation: floatOne 10s ease-in-out infinite;
        }

        body::after {
            right: -4rem;
            bottom: 8%;
            width: 16rem;
            height: 16rem;
            background: radial-gradient(circle, rgba(77, 125, 255, 0.28), transparent 70%);
            animation: floatTwo 12s ease-in-out infinite;
        }

        .grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 3rem 3rem;
            mask-image: radial-gradient(circle at center, black 30%, transparent 82%);
            pointer-events: none;
        }

        .wrap {
            position: relative;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.5rem;
        }

        .card {
            position: relative;
            width: min(100%, 52rem);
            padding: clamp(1.8rem, 4vw, 3.2rem);
            border: 1px solid var(--panel-line);
            border-radius: 2rem;
            background:
                linear-gradient(145deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.02)),
                var(--panel);
            box-shadow: var(--shadow);
            overflow: hidden;
            isolation: isolate;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(130deg, rgba(103, 184, 255, 0.16), transparent 22%),
                radial-gradient(circle at top right, rgba(139, 243, 210, 0.12), transparent 26%);
            z-index: -1;
        }

        .topline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.6rem;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.78rem 1.08rem;
            border: 1px solid rgba(103, 184, 255, 0.28);
            border-radius: 999px;
            background: rgba(103, 184, 255, 0.12);
            color: #dff1ff;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .pill::before {
            content: '';
            width: 0.72rem;
            height: 0.72rem;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), var(--accent-3));
            box-shadow: 0 0 16px rgba(103, 184, 255, 0.75);
        }

        .badge {
            font-size: 0.86rem;
            color: var(--muted);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .title {
            margin: 0;
            font-family: 'Sora', sans-serif;
            font-size: clamp(3rem, 8vw, 6rem);
            line-height: 0.95;
            letter-spacing: -0.055em;
            text-wrap: balance;
            max-width: 8ch;
        }

        .title span {
            display: block;
            background: linear-gradient(135deg, #ffffff 0%, #d8e8ff 45%, #97d3ff 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .copy {
            margin: 1.4rem 0 0;
            max-width: 33rem;
            font-size: 1.08rem;
            line-height: 1.8;
            color: var(--muted);
        }

        .signal {
            margin-top: 2rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .signal-card {
            padding: 1rem 1rem 1.1rem;
            border-radius: 1.15rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(7, 16, 31, 0.5);
        }

        .signal-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #89a6d2;
            margin-bottom: 0.45rem;
        }

        .signal-value {
            font-size: 1.04rem;
            font-weight: 700;
            color: #f4f8ff;
        }

        .line {
            position: absolute;
            right: 1.35rem;
            top: 1.35rem;
            width: 9rem;
            height: 9rem;
            border-top: 1px solid rgba(103, 184, 255, 0.25);
            border-right: 1px solid rgba(103, 184, 255, 0.25);
            border-radius: 0 1.5rem 0 0;
            opacity: 0.7;
        }

        @keyframes floatOne {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(18px); }
        }

        @keyframes floatTwo {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-16px); }
        }

        @media (max-width: 767.98px) {
            body {
                overflow: auto;
            }

            .topline {
                flex-direction: column;
                align-items: flex-start;
            }

            .title {
                max-width: none;
            }

            .signal {
                grid-template-columns: 1fr;
            }

            .line {
                width: 6rem;
                height: 6rem;
            }
        }
    </style>
</head>
<body>
    <div class="grid"></div>

    <main class="wrap">
        <section class="card">
            <div class="line"></div>

            <div class="topline">
                <div class="pill">Under maintenance</div>
                <div class="badge">System Notice</div>
            </div>

            <h1 class="title">
                <span>Under</span>
                <span>maintenance</span>
            </h1>

            <p class="copy">
                The page is temporarily unavailable while updates are being applied. Please check back again in a little while.
            </p>

            <div class="signal">
                <div class="signal-card">
                    <div class="signal-label">Status</div>
                    <div class="signal-value">Temporary Pause</div>
                </div>
                <div class="signal-card">
                    <div class="signal-label">Access</div>
                    <div class="signal-value">Unavailable</div>
                </div>
                <div class="signal-card">
                    <div class="signal-label">Progress</div>
                    <div class="signal-value">Updating</div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>

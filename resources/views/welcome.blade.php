<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PayPoint | Smart Payments</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            background-image: radial-gradient(circle at 20% 150%, #2B64E3 0%, #0f172a 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            overflow: hidden;
        }
        .container { text-align: center; width: 90%; max-width: 400px; padding: 20px; }

        /* Modern "P" Logo implementation */
        .logo-box {
            width: 64px; height: 64px;
            background: white;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .logo-text { font-size: 32px; font-weight: 900; color: #2B64E3; }

        .brand-name { font-size: 32px; font-weight: 900; letter-spacing: -1px; margin-bottom: 8px; }
        .tagline { font-size: 16px; opacity: 0.8; margin-bottom: 40px; font-weight: 500; }

        .btn { display: block; padding: 18px; border-radius: 16px; text-decoration: none; font-weight: 800; font-size: 15px; margin-bottom: 12px; transition: 0.2s; }
        .btn-white { background: white; color: #2B64E3; }
        .btn-outline { border: 1px solid rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px); }

        .btn:active { transform: scale(0.96); }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-box">
            <span class="logo-text">P</span>
        </div>

        <div class="brand-name">PayPoint</div>
        <p class="tagline">The smarter way to pay and get paid.</p>

        @if (Route::has('login'))
            <div style="margin-top: 20px;">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-white">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-white">Login to Account</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline">Create New Account</a>
                    @endif
                @endauth
            </div>
        @endif

        <p style="font-size: 11px; opacity: 0.4; margin-top: 60px; font-weight: 600; letter-spacing: 1px;">
            &copy; 2026 PAYPOINT TECHNOLOGY
        </p>
    </div>
</body>
</html>

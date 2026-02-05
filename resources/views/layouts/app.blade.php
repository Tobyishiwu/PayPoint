<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PayPoint') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css/inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @php
        $manifestPath = public_path('build/manifest.json');
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
            $jsFile  = $manifest['resources/js/app.js']['file'] ?? null;
        }
    @endphp

    @if(isset($cssFile, $jsFile))
        <link rel="stylesheet" href="{{ url('build/' . $cssFile) }}">
        <script type="module" src="{{ url('build/' . $jsFile) }}"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --pp-blue: #2B64E3;
            --pp-inactive: #94A3B8;
            --pp-success: #16A34A;
            --pp-error: #DC2626;
        }

        body, html {
            margin: 0;
            padding: 0;
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        .app-container {
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .app-shell {
            width: 100%;
            max-width: 430px;
            background: #fff;
            min-height: 100vh;
            padding-bottom: 90px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* ===== GLOBAL FLASH ALERTS ===== */
        .flash {
            position: fixed;
            top: 18px;
            left: 50%;
            transform: translateX(-50%);
            padding: 14px 22px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 800;
            color: #fff;
            z-index: 99999;
            box-shadow: 0 12px 30px rgba(0,0,0,.18);
            animation: slideDown .35s ease;
        }

        .flash-success { background: var(--pp-success); }
        .flash-error { background: var(--pp-error); }

        @keyframes slideDown {
            from { opacity: 0; transform: translate(-50%, -10px); }
            to   { opacity: 1; transform: translate(-50%, 0); }
        }

        /* ===== BOTTOM NAV ===== */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 430px;
            background: #fff;
            display: flex;
            justify-content: space-around;
            padding: 12px 0 30px;
            border-top: 1px solid #f1f5f9;
            z-index: 1000;
        }

        .nav-item {
            color: var(--pp-inactive);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .nav-item.active { color: var(--pp-blue); }

        .nav-icon-main {
            background: var(--pp-blue);
            color: #fff;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 18px;
        }

        .nav-icon { font-size: 22px; }

        .nav-item span {
            font-size: 10px;
            font-weight: 700;
        }
    </style>
</head>

<body>

{{-- ================= PAYPOINT GLOBAL ALERT ENGINE ================= --}}
@if (
    session('success') ||
    session('airtime_success') ||
    session('data_success') ||
    session('electricity_success')
)

@php
    $successMessage =
        session('success')
        ?? (session('airtime_success') ? 'Airtime purchase successful'
        : (session('data_success') ? 'Data purchase successful'
        : 'Payment successful'));
@endphp

<div class="flash flash-success" id="flashMsg">
    {{ $successMessage }}
</div>
@endif

@if ($errors->any())
<div class="flash flash-error" id="flashMsg">
    {{ $errors->first() }}
</div>
@endif
{{-- =============================================================== --}}

<div class="app-container">
    <div class="app-shell">
        <main>
            {{ $slot }}
        </main>

        <nav class="bottom-nav">
            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="nav-icon-main">🏠</div>
                <span>Home</span>
            </a>

            <a href="{{ route('history') }}"
               class="nav-item {{ request()->routeIs('history') ? 'active' : '' }}">
                <div class="nav-icon">📊</div>
                <span>Activity</span>
            </a>

            <a href="{{ route('support') }}"
               class="nav-item {{ request()->routeIs('support') ? 'active' : '' }}">
                <div class="nav-icon">💬</div>
                <span>Support</span>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="nav-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
                <div class="nav-icon">👤</div>
                <span>Profile</span>
            </a>
        </nav>
    </div>
</div>

<script>
    setTimeout(() => {
        const flash = document.getElementById('flashMsg');
        if (flash) flash.remove();
    }, 3500);
</script>

</body>
</html>

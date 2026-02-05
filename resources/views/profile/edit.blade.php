<x-app-layout>
    <style>
        :root {
            --pp-accent: #2B64E3;
            --pp-dark: #0F172A;
            --pp-bg: #FFFFFF;
            --pp-surface: #F8FAFC;
        }

        .pp-profile-shell { width: 100%; min-height: 100vh; background: var(--pp-bg); padding-bottom: 120px; font-family: 'Inter', sans-serif; }

        /* Minimalist Floating Header */
        .pp-header-nav {
            padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; background: rgba(255,255,255,0.8); backdrop-filter: blur(12px); z-index: 100;
        }
        .pp-back-btn {
            width: 44px; height: 44px; border-radius: 14px; background: var(--pp-surface);
            border: none; display: grid; place-items: center; cursor: pointer; color: var(--pp-dark); font-size: 20px;
        }

        /* Identity Card */
        .pp-identity-section { text-align: center; padding: 20px 24px 40px; }
        .pp-avatar-wrapper {
            position: relative; width: 100px; height: 100px; margin: 0 auto 20px;
        }
        .pp-avatar-img {
            width: 100%; height: 100%; border-radius: 35px; object-fit: cover;
            border: 3px solid #FFF; box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .pp-status-dot {
            position: absolute; bottom: -5px; right: -5px; width: 28px; height: 28px;
            background: #22C55E; border: 4px solid #FFF; border-radius: 50%;
        }

        .pp-user-name { font-size: 24px; font-weight: 900; color: var(--pp-dark); margin: 0; letter-spacing: -1px; }
        .pp-user-meta { font-size: 12px; color: #94A3B8; font-weight: 700; margin-top: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .pp-id-code { color: var(--pp-accent); font-family: 'JetBrains Mono', monospace; margin-left: 5px; }

        /* Security Pills */
        .pp-security-status { display: inline-flex; margin-top: 15px; }
        .pp-badge {
            padding: 6px 14px; border-radius: 100px; font-size: 10px; font-weight: 800;
            text-transform: uppercase; display: flex; align-items: center; gap: 6px;
        }
        .pp-badge.secure { background: #DCFCE7; color: #16A34A; }
        .pp-badge.warning { background: #FFF7ED; color: #EA580C; }

        /* Bento-Style Menu List */
        .pp-menu-container { padding: 0 24px; }
        .pp-menu-label { font-size: 11px; font-weight: 800; color: #CBD5E1; text-transform: uppercase; letter-spacing: 1.5px; margin: 30px 0 15px 5px; }

        .pp-menu-item {
            background: var(--pp-surface); padding: 20px; border-radius: 24px;
            display: flex; align-items: center; margin-bottom: 12px;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer;
            border: 1px solid transparent;
        }
        .pp-menu-item:active { transform: scale(0.97); background: #F1F5F9; border-color: #E2E8F0; }

        .pp-icon-box {
            width: 48px; height: 48px; border-radius: 16px; background: #FFF;
            display: grid; place-items: center; font-size: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
        .pp-item-content { flex: 1; margin-left: 18px; }
        .pp-item-content strong { display: block; font-size: 15px; color: var(--pp-dark); font-weight: 800; }
        .pp-item-content span { font-size: 11px; color: #94A3B8; font-weight: 600; }

        /* Modern Logout */
        .pp-logout-wrap { padding: 40px 24px; }
        .pp-logout-btn {
            width: 100%; padding: 20px; border-radius: 24px; border: none;
            background: #FFF1F2; color: #E11D48; font-weight: 800; font-size: 14px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            cursor: pointer; transition: 0.3s;
        }
        .pp-logout-btn:active { background: #FFE4E6; transform: translateY(2px); }
    </style>

    <div class="pp-profile-shell">
        <nav class="pp-header-nav">
            <button onclick="window.location.href='{{ route('dashboard') }}'" class="pp-back-btn">←</button>
            <span style="font-weight: 800; font-size: 16px;">Account</span>
            <div style="width:44px;"></div>
        </nav>

        <section class="pp-identity-section">
            <div class="pp-avatar-wrapper">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2B64E3&color=FFFFFF&bold=true" class="pp-avatar-img">
                <div class="pp-status-dot"></div>
            </div>
            <h2 class="pp-user-name">{{ Auth::user()->name }}</h2>
            <p class="pp-user-meta">
                {{ Auth::user()->pay_tag }} <span class="pp-id-code">| {{ Auth::user()->account->account_number ?? 'SB-00000002' }}</span>
            </p>
<div class="pp-security-status">
    @if(Auth::user()->transaction_pin)
        <div class="pp-badge secure"><span>🛡️</span> Verified</div>
    @else
        <a href="{{ route('settings.security') }}" class="pp-badge warning pulse" style="text-decoration: none;">
            <span>⚠️</span> Set Transaction PIN
        </a>
    @endif
</div>
        </section>

        <main class="pp-menu-container">
            <p class="pp-menu-label">Preferences</p>

            <div class="pp-menu-item" onclick="window.location.href='{{ route('profile.edit') }}'">
                <div class="pp-icon-box">👤</div>
                <div class="pp-item-content">
                    <strong>Personal Info</strong>
                    <span>Manage name and contact email</span>
                </div>
                <div style="color: #CBD5E1;">→</div>
            </div>

            <div class="pp-menu-item" onclick="window.location.href='{{ route('settings.security') }}'">
                <div class="pp-icon-box" style="color: #F59E0B;">🔐</div>
                <div class="pp-item-content">
                    <strong>Security Engine</strong>
                    <span>Transaction PIN & Passwords</span>
                </div>
                <div style="color: #CBD5E1;">→</div>
            </div>

            <div class="pp-menu-item">
                <div class="pp-icon-box" style="color: #2B64E3;">💳</div>
                <div class="pp-item-content">
                    <strong>Payment Methods</strong>
                    <span>Linked cards and bank details</span>
                </div>
                <div style="color: #CBD5E1;">→</div>
            </div>

            <p class="pp-menu-label">Support</p>

            <div class="pp-menu-item" onclick="window.location.href='{{ route('support') }}'">
                <div class="pp-icon-box">🎧</div>
                <div class="pp-item-content">
                    <strong>PayPoint Concierge</strong>
                    <span>24/7 priority chat support</span>
                </div>
                <div style="color: #CBD5E1;">→</div>
            </div>

            <div class="pp-logout-wrap">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="pp-logout-btn">
                        Log out of PayPoint
                    </button>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>

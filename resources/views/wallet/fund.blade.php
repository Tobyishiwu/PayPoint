<x-app-layout>
    <style>
        .f-fund-surface { background: #ffffff; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 24px; color: #1e293b; }
        .nav-header { display: flex; align-items: center; margin-bottom: 30px; }
        .back-link { font-size: 24px; color: #0f172a; text-decoration: none; margin-right: 20px; transition: 0.2s; }
        .back-link:active { opacity: 0.5; }
        .f-title { font-size: 22px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; }

        .f-section-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 16px; display: block; }

        /* Modernized Manual Transfer Card */
        .bank-card {
            background: #F8FAFC; border-radius: 32px; padding: 32px; border: 1.5px solid #f1f5f9;
            margin-bottom: 35px; position: relative; overflow: hidden; transition: 0.3s; cursor: pointer;
        }
        .bank-card:active { transform: scale(0.97); background: #f1f5f9; }
        .bank-card::before { content: 'PP'; position: absolute; right: -5px; bottom: -15px; font-size: 90px; font-weight: 900; color: #2B64E3; opacity: 0.05; transform: rotate(-10deg); }

        .b-name { color: #2B64E3; font-weight: 800; font-size: 12px; margin-bottom: 10px; display: block; letter-spacing: 0.5px; }
        .b-acc { font-size: 28px; font-weight: 900; color: #0f172a; font-family: 'JetBrains Mono', monospace; letter-spacing: -1px; margin-bottom: 8px; }
        .b-user { font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px; }

        /* Payment Methods Grid */
        .method-item {
            display: flex; align-items: center; padding: 22px; background: #fff;
            border: 1.5px solid #f8fafc; border-radius: 24px; margin-bottom: 14px;
            cursor: pointer; transition: 0.2s; text-decoration: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        .method-item:active { transform: scale(0.98); background: #f8fafc; border-color: #e2e8f0; }
        .method-icon { width: 52px; height: 52px; background: #F0F7FF; border-radius: 16px; display: grid; place-items: center; font-size: 22px; margin-right: 18px; }
        .method-info { flex: 1; }
        .method-title { font-size: 15px; font-weight: 800; color: #0f172a; display: block; margin-bottom: 2px; }
        .method-desc { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Toast Notification */
        .copy-toast {
            position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%);
            background: #0f172a; color: #fff; padding: 14px 28px; border-radius: 20px;
            font-size: 12px; font-weight: 800; display: none; z-index: 1000;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: popUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes popUp { from { bottom: 0; opacity: 0; } to { bottom: 40px; opacity: 1; } }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>

    <div class="f-fund-surface">
        <header class="nav-header">
            <a href="{{ route('dashboard') }}" class="back-link">←</a>
            <h1 class="f-title">Fund Wallet</h1>
        </header>

        <span class="f-section-label">Manual Bank Transfer</span>
        <div class="bank-card" onclick="copyAccount()">
            <span class="b-name">PAYPOINT MICROFINANCE BANK</span>
            <div class="b-acc" id="account-number">{{ Auth::user()->account->account_number ?? 'PP-00000000' }}</div>
            <div class="b-user">{{ strtoupper(auth()->user()->name) }}</div>

            <div style="margin-top: 25px; display: flex; align-items: center; gap: 8px;">
                <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; animation: pulse 2s infinite;"></div>
                <span style="font-size: 11px; font-weight: 800; color: #2B64E3; text-transform: uppercase;">Tap to copy account number</span>
            </div>
        </div>

        <span class="f-section-label">Instant Top-up</span>

        <a href="#" class="method-item">
            <div class="method-icon">💳</div>
            <div class="method-info">
                <span class="method-title">Debit Card</span>
                <span class="method-desc">Fund via Flutterwave / Paystack</span>
            </div>
            <span style="color: #cbd5e1; font-weight: 900;">→</span>
        </a>

        <a href="#" class="method-item">
            <div class="method-icon">🏦</div>
            <div class="method-info">
                <span class="method-title">Direct Bank Link</span>
                <span class="method-desc">Authorized Bank Transfer</span>
            </div>
            <span style="color: #cbd5e1; font-weight: 900;">→</span>
        </a>

        <div style="margin-top: 45px; text-align: center; padding: 0 30px;">
            <p style="font-size: 12px; color: #94a3b8; line-height: 1.8; font-weight: 500;">
                Funds transferred to your <strong style="color: #64748b;">{{ Auth::user()->account->account_number ?? 'PP-00000000' }}</strong> account will be credited to your PayPoint wallet automatically.
            </p>
        </div>
    </div>

    <div id="toast" class="copy-toast">✅ Account Number Copied!</div>

    <script>
        function copyAccount() {
            const accNum = document.getElementById('account-number').innerText;
            navigator.clipboard.writeText(accNum).then(() => {
                const toast = document.getElementById('toast');
                toast.style.display = 'block';

                if (window.navigator.vibrate) window.navigator.vibrate(50);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        toast.style.display = 'none';
                        toast.style.opacity = '1';
                    }, 300);
                }, 2000);
            });
        }
    </script>
</x-app-layout>

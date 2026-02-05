<x-app-layout>
    <style>
        :root {
            --pp-primary: #2B64E3;
            --pp-dark: #0F172A;
            --pp-bg: #FFFFFF;
            --pp-soft: #F8FAFC;
            --pp-border: #F1F5F9;
        }

        body {
            background: var(--pp-bg);
        }

        .pp-shell {
            min-height: 100vh;
            padding: 24px;
            font-family: 'Inter', sans-serif;
            background: var(--pp-bg);
        }

        /* ================= HEADER ================= */
        .pp-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .pp-back {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: var(--pp-soft);
            display: grid;
            place-items: center;
            font-size: 20px;
            text-decoration: none;
            color: var(--pp-dark);
        }

        /* ================= NETWORK GRID ================= */
        .net-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 30px;
        }

        .net-item {
            min-height: 86px; /* CRITICAL FIX */
            border-radius: 20px;
            border: 2.5px solid var(--pp-border);
            background: var(--pp-soft);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.25s ease;
        }

        .net-item img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            display: block;        /* CRITICAL FIX */
            flex-shrink: 0;        /* CRITICAL FIX */
        }

        .net-item span {
            margin-top: 8px;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #94A3B8;
        }

        .net-item.active {
            border-color: var(--pp-primary);
            background: #F0F4FF;
            transform: scale(1.05);
        }

        .net-item.active span {
            color: var(--pp-primary);
        }

        /* ================= INPUTS ================= */
        .pp-label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: #94A3B8;
            margin-bottom: 10px;
        }

        .pp-input {
            width: 100%;
            padding: 18px;
            border-radius: 20px;
            border: 2.5px solid var(--pp-border);
            background: var(--pp-soft);
            font-size: 17px;
            font-weight: 700;
            color: var(--pp-dark);
            margin-bottom: 22px;
            transition: 0.25s;
        }

        .pp-input:focus {
            outline: none;
            border-color: var(--pp-primary);
            background: #FFF;
        }

        .pin-input {
            text-align: center;
            letter-spacing: 12px;
            font-size: 22px;
        }

        /* ================= BUTTON ================= */
        .pp-btn {
            width: 100%;
            padding: 22px;
            border-radius: 20px;
            background: var(--pp-primary);
            color: #FFF;
            border: none;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(43, 100, 227, 0.25);
        }

        .pp-btn:active {
            transform: scale(0.98);
        }

        /* ================= LOADING OVERLAY ================= */
        #loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            flex-direction: column;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 4px solid var(--pp-border);
            border-top-color: var(--pp-primary);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    {{-- ================= LOADING OVERLAY ================= --}}
    <div id="loading-overlay">
        <div class="spinner"></div>
        <p style="margin-top:18px;font-weight:900;font-size:18px;color:#0F172A;">
            Processing…
        </p>
        <p style="font-size:13px;color:#94A3B8;font-weight:600;">
            PayPoint is securing your airtime
        </p>
    </div>

    <div class="pp-shell">
        {{-- HEADER --}}
        <nav class="pp-nav">
            <a href="{{ route('dashboard') }}" class="pp-back">←</a>
            <h1 style="font-size:18px;font-weight:900;margin:0;color:#0F172A;">
                Buy Airtime
            </h1>
            <div style="width:44px"></div>
        </nav>

        {{-- ERRORS --}}
        @if($errors->any())
            <div style="background:#FFF1F2;border-left:4px solid #E11D48;padding:16px;border-radius:12px;margin-bottom:24px;">
                <p style="color:#9F1239;font-size:13px;font-weight:700;margin:0;">
                    {{ $errors->first() }}
                </p>
            </div>
        @endif

        {{-- FORM --}}
        <form id="airtimeForm" method="POST" action="{{ route('services.airtime.process') }}">
            @csrf

            <label class="pp-label">Select Network</label>
            <div class="net-grid">
                <div class="net-item" onclick="setNet(this,'mtn')">
                    <img src="{{ asset('images/providers/mtn.png') }}" alt="MTN">
                    <span>MTN</span>
                </div>
                <div class="net-item" onclick="setNet(this,'airtel')">
                    <img src="{{ asset('images/providers/airtel.png') }}" alt="Airtel">
                    <span>Airtel</span>
                </div>
                <div class="net-item" onclick="setNet(this,'glo')">
                    <img src="{{ asset('images/providers/glo.png') }}" alt="Glo">
                    <span>Glo</span>
                </div>
                <div class="net-item" onclick="setNet(this,'9mobile')">
                    <img src="{{ asset('images/providers/9mobile.png') }}" alt="9mobile">
                    <span>9mobile</span>
                </div>
            </div>

            <input type="hidden" name="provider" id="provider-input" required>

            <label class="pp-label">Recipient Number</label>
            <input type="tel" name="phone" maxlength="11" class="pp-input"
                   placeholder="08000000000" required>

            <label class="pp-label">Amount (₦)</label>
            <input type="number" name="amount" min="100"
                   class="pp-input" placeholder="0.00"
                   style="font-size:24px;" required>

            <label class="pp-label">Transaction PIN</label>
            <input type="password" name="pin" maxlength="4" inputmode="numeric"
                   class="pp-input pin-input" placeholder="••••" required>

            <button type="submit" class="pp-btn">
                Purchase Airtime
            </button>
        </form>

        <p style="text-align:center;color:#CBD5E1;font-size:11px;font-weight:800;margin-top:30px;letter-spacing:2px;">
            PayPoint Secure
        </p>
    </div>

    <script>
        function setNet(el, val) {
            document.querySelectorAll('.net-item').forEach(i => i.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('provider-input').value = val;
        }

        document.getElementById('airtimeForm').onsubmit = function () {
            if (!document.getElementById('provider-input').value) {
                alert('Please select a network');
                return false;
            }
            document.getElementById('loading-overlay').style.display = 'flex';
        };
    </script>
</x-app-layout>

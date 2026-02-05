<x-app-layout>
    @php
        $account = $account ?? Auth::user()->account;
    @endphp

    <style>
        .f-dashboard { background: #ffffff; min-height: 100vh; font-family: 'Inter', sans-serif; padding-bottom: 40px; }

        /* Header */
        .f-header { padding: 24px; display: flex; justify-content: space-between; align-items: center; background: #fff; }
        .u-avatar { width: 44px; height: 44px; border-radius: 14px; background: #f1f5f9; border: 1.5px solid #e2e8f0; }

        /* 2026 Blue Gradient Balance Card */
        .b-section { padding: 0 24px; margin-bottom: 30px; }
        .b-card {
            background: #0f172a; border-radius: 32px; padding: 32px; color: white;
            background-image: radial-gradient(circle at 20% 150%, #2B64E3 0%, #0f172a 100%);
            box-shadow: 0 20px 40px rgba(43, 100, 227, 0.15);
            position: relative;
        }
        .b-label { font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1.2px; }
        .b-amount { font-size: 36px; font-weight: 800; margin: 10px 0; letter-spacing: -1px; display: flex; align-items: center; gap: 12px; }

        .b-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 25px; }

        /* The Updated Add Money Button */
        .btn-add {
            background: #fff; color: #2B64E3; border: none; padding: 14px 28px;
            border-radius: 16px; font-weight: 800; font-size: 14px; cursor: pointer;
            transition: 0.2s; text-decoration: none; display: inline-block;
        }
        .btn-add:active { transform: scale(0.95); opacity: 0.9; }

        .acc-code { color: rgba(255,255,255,0.8); font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 600; cursor: pointer; }

        /* Grid */
        .s-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 0 24px; }
        .s-card {
            background: #F8FAFC; border-radius: 24px; padding: 20px 10px; display: flex;
            flex-direction: column; align-items: center; border: 1px solid transparent;
            transition: 0.2s; cursor: pointer; text-decoration: none;
        }
        .s-card:active { background: #f1f5f9; transform: scale(0.96); }
        .s-icon { width: 50px; height: 50px; background: #fff; border-radius: 16px; display: grid; place-items: center; font-size: 22px; margin-bottom: 10px; }
        .s-label { font-size: 11px; font-weight: 800; color: #475569; }

        /* Quick Transfer Modal Styles */
        #bankModal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.9); z-index: 9999; align-items: center; justify-content: center; padding: 24px; backdrop-filter: blur(10px); }
        .m-content { background: #fff; padding: 40px 30px; border-radius: 40px; width: 100%; max-width: 400px; text-align: center; animation: slideUp 0.3s ease-out; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>

    <div class="f-dashboard">
        <header class="f-header">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2B64E3&color=fff&bold=true" class="u-avatar">
                <div>
                    <p style="margin: 0; font-size: 17px; font-weight: 800; color: #0f172a;">{{ explode(' ', Auth::user()->name)[0] }}</p>
                    <small style="color: #94A3B8; font-size: 11px; font-weight: 700; text-transform: uppercase;">SB-00000002</small>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                <button onclick="toggleModal('bankModal')" style="border:none; background:#f1f5f9; width:44px; height:44px; border-radius:14px; cursor:pointer;">🏛️</button>
                <button onclick="location.href='{{ route('profile.edit') }}'" style="border:none; background:#f1f5f9; width:44px; height:44px; border-radius:14px; cursor:pointer;">⚙️</button>
            </div>
        </header>

        {{-- Balance Card --}}
        <section class="b-section">
            <div class="b-card">
                <span class="b-label">Account Balance</span>
                <div class="b-amount">
                    <span id="bal-text">₦{{ number_format($account->balance ?? 0, 2) }}</span>
                    <span onclick="toggleVisibility()" id="eye-icon" style="cursor: pointer; font-size: 20px; opacity: 0.6;">👁️</span>
                </div>

                <div class="b-actions">
                    <a href="{{ route('wallet.fund') }}" class="btn-add">Add Money</a>

                    <div style="text-align: right;">
                        <span class="acc-code" onclick="copyAcc()" id="acc-num">{{ $account->account_number ?? 'SB-00000002' }}</span>
                        <div style="font-size: 9px; font-weight: 800; color: rgba(255,255,255,0.4); margin-top: 4px;">TAP TO COPY</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Quick Actions --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 28px 15px;">
            <h3 style="font-size: 13px; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px;">Services</h3>
            <button onclick="location.href='{{ route('history') }}'" style="background:transparent; border:none; color:#2B64E3; font-weight:800; font-size:12px; cursor:pointer;">History</button>
        </div>

        <div class="s-grid">
            @foreach([
                ['Airtime', '📱', 'airtime'], ['Data', '📊', 'data'], ['Electricity', '⚡', 'electricity'],
                ['Internet', '📡', 'wifi'], ['E-Sim', '📟', 'esim'], ['More', '⁞⁞', 'more']
            ] as [$name, $icon, $id])
            <a href="/services/{{$id}}" class="s-card">
                <div class="s-icon">{{$icon}}</div>
                <span class="s-label">{{$name}}</span>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Quick Info Modal (For Bank Details) --}}
    <div id="bankModal">
        <div class="m-content">
            <div style="width: 60px; height: 60px; background: #F0F7FF; color: #2B64E3; border-radius: 20px; display: grid; place-items: center; font-size: 24px; margin: 0 auto 20px;">🏛️</div>
            <h3 style="font-weight: 900; color: #0f172a; margin-bottom: 8px;">Direct Deposit</h3>
            <p style="font-size: 14px; color: #64748B; margin-bottom: 30px;">Transfer to your unique account number to fund your **SB-00000002** wallet.</p>

            <div style="background: #F8FAFC; padding: 25px; border-radius: 24px; border: 2px dashed #e2e8f0; margin-bottom: 30px;">
                <small style="color: #2B64E3; font-weight: 800; font-size: 11px;">SIMPLE BANK PLC</small>
                <div style="font-size: 28px; font-weight: 900; color: #0f172a; margin: 8px 0; font-family: 'JetBrains Mono';">{{ $account->account_number ?? 'SB-00000002' }}</div>
                <small style="color: #94A3B8; font-weight: 700;">{{ strtoupper(Auth::user()->name) }}</small>
            </div>

            <button onclick="toggleModal('bankModal')" style="width:100%; padding:20px; border:none; background: #2B64E3; color:#fff; border-radius:20px; font-weight:800; cursor:pointer;">Got it</button>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const el = document.getElementById(id);
            el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'flex' : 'none';
        }

        let isVisible = true;
        function toggleVisibility() {
            const text = document.getElementById('bal-text');
            const eye = document.getElementById('eye-icon');
            isVisible = !isVisible;
            text.innerText = isVisible ? "₦{{ number_format($account->balance ?? 0, 2) }}" : "₦ ••••••";
            eye.innerText = isVisible ? "👁️" : "🙈";
        }

        function copyAcc() {
            const num = document.getElementById('acc-num').innerText;
            navigator.clipboard.writeText(num).then(() => {
                alert("Account Number Copied!");
            });
        }
    </script>
</x-app-layout>

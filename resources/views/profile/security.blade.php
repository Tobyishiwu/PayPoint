<x-app-layout>
    <style>
        .pp-vault {
            background: #FFF; min-height: 100vh; padding: 24px;
            font-family: 'Inter', sans-serif; display: flex; flex-direction: column;
        }

        /* Minimal Header */
        .pp-v-nav { display: flex; align-items: center; margin-bottom: 20px; }
        .pp-v-back { border: none; background: #F8FAFC; width: 42px; height: 42px; border-radius: 14px; cursor: pointer; font-size: 18px; }

        /* Compact Stage */
        .pp-v-stage { text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; max-width: 320px; margin: 0 auto; width: 100%; }
        .pp-v-icon { font-size: 36px; margin-bottom: 8px; }
        .pp-v-stage h2 { font-size: 22px; font-weight: 900; margin: 0; color: #0F172A; letter-spacing: -0.5px; }
        .pp-v-stage p { font-size: 14px; color: #94A3B8; margin: 6px 0 32px; font-weight: 600; }

        /* Small PIN Dots */
        .pin-dots { display: flex; justify-content: center; gap: 16px; margin-bottom: 40px; }
        .dot { width: 14px; height: 14px; border-radius: 50%; background: #F1F5F9; border: 2px solid #E2E8F0; transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .dot.active { background: #2B64E3; border-color: #2B64E3; transform: scale(1.2); }

        /* Tight Keypad */
        .keypad { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; width: 100%; }
        .key {
            background: #F8FAFC; border: none; padding: 22px; border-radius: 20px;
            font-size: 24px; font-weight: 800; color: #0F172A; transition: 0.15s;
        }
        .key:active { background: #E2E8F0; transform: scale(0.92); }
        .key.del { color: #94A3B8; font-size: 18px; }

        /* Success Layer */
        #success-layer {
            position: fixed; inset: 0; background: #FFF; z-index: 100;
            display: none; flex-direction: column; align-items: center; justify-content: center;
        }

        /* PayPoint Blue Action Button */
        .submit-btn {
            width: 100%; margin-top: 32px; padding: 20px;
            background: #2B64E3; color: #FFF; border: none; border-radius: 20px;
            font-weight: 800; font-size: 16px; display: none; cursor: pointer;
            box-shadow: 0 10px 20px rgba(43, 100, 227, 0.2);
        }
        .submit-btn.show { display: block; animation: slideUp 0.3s ease-out; }

        @keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="pp-vault">
        <div id="success-layer">
            <div style="font-size: 60px; margin-bottom: 15px;">✅</div>
            <h2 style="font-weight: 900; letter-spacing: -1px;">PayPoint Secured</h2>
        </div>

        <nav class="pp-v-nav">
            <button onclick="window.location.href='{{ route('profile.edit') }}'" class="pp-v-back">←</button>
        </nav>

        <main class="pp-v-stage">
            <div class="pp-v-icon" id="v-icon">🔒</div>
            <h2 id="v-title">Set PIN</h2>
            <p id="v-desc">Secure your PayPoint account</p>

            <div class="pin-dots">
                <div class="dot" id="d0"></div>
                <div class="dot" id="d1"></div>
                <div class="dot" id="d2"></div>
                <div class="dot" id="d3"></div>
            </div>

            <div class="keypad">
                <button class="key" onclick="tap(1)">1</button>
                <button class="key" onclick="tap(2)">2</button>
                <button class="key" onclick="tap(3)">3</button>
                <button class="key" onclick="tap(4)">4</button>
                <button class="key" onclick="tap(5)">5</button>
                <button class="key" onclick="tap(6)">6</button>
                <button class="key" onclick="tap(7)">7</button>
                <button class="key" onclick="tap(8)">8</button>
                <button class="key" onclick="tap(9)">9</button>
                <div class="key" style="background:transparent;"></div>
                <button class="key" onclick="tap(0)">0</button>
                <button class="key del" onclick="tap('del')">⌫</button>
            </div>

            <form id="v-form" method="POST" action="{{ route('settings.update-pin') }}">
                @csrf
                <input type="hidden" name="pin" id="p-main">
                <input type="hidden" name="pin_confirmation" id="p-conf">
                <button type="button" id="v-btn" onclick="next()" class="submit-btn">Verify PIN</button>
            </form>
        </main>
    </div>

    <script>
        let code = ""; let first = ""; let step = 1;

        function tap(k) {
            if (k === 'del') code = code.slice(0, -1);
            else if (code.length < 4) code += k;

            for (let i = 0; i < 4; i++) {
                document.getElementById(`d${i}`).classList.toggle('active', i < code.length);
            }
            document.getElementById('v-btn').classList.toggle('show', code.length === 4);
        }

        function next() {
            if (step === 1) {
                first = code; code = ""; step = 2;
                document.getElementById('v-title').innerText = "Verify PIN";
                document.getElementById('v-desc').innerText = "Repeat your new PIN";
                document.getElementById('v-icon').innerText = "🛡️";
                tap('');
            } else {
                if (code === first) {
                    document.getElementById('success-layer').style.display = 'flex';
                    document.getElementById('p-main').value = first;
                    document.getElementById('p-conf').value = code;
                    setTimeout(() => document.getElementById('v-form').submit(), 1200);
                } else {
                    alert("PINs do not match. Let's try again.");
                    location.reload();
                }
            }
        }
    </script>
</x-app-layout>

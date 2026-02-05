<x-app-layout>
    <style>
        :root {
            --pp-blue: #2B64E3;      /* PayPoint Blue */
            --pp-dark: #0F172A;
            --pp-muted: #64748B;
            --pp-soft: #F8FAFC;
            --pp-border: #E5E7EB;
        }

        .pp-container {
            max-width: 440px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Inter', sans-serif;
            background: #FFF;
            min-height: 100vh;
        }

        /* Header */
        .pp-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .pp-back {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: var(--pp-soft);
            display: grid;
            place-items: center;
            font-size: 18px;
            text-decoration: none;
            color: var(--pp-dark);
        }

        .pp-title {
            font-size: 20px;
            font-weight: 900;
            color: var(--pp-dark);
            margin: 0;
        }

        .pp-subtitle {
            font-size: 13px;
            color: var(--pp-muted);
            margin-bottom: 26px;
        }

        /* Network Selector */
        .net-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 26px;
        }

        .net-card {
            background: #FFF;
            border: 2px solid var(--pp-border);
            border-radius: 18px;
            padding: 16px 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .25s ease;
            min-height: 96px;
            user-select: none;
        }

        .net-card:hover {
            border-color: var(--pp-blue);
        }

        .net-card.active {
            border-color: var(--pp-blue);
            background: #F3F7FF;
            box-shadow: 0 8px 22px rgba(43,100,227,.18);
            transform: scale(1.04);
        }

        .net-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
            display: block;
            margin-bottom: 8px;
        }

        .net-name {
            font-size: 10px;
            font-weight: 800;
            color: var(--pp-muted);
            letter-spacing: -.3px;
        }

        .net-card.active .net-name {
            color: var(--pp-blue);
        }

        /* Inputs */
        .pp-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--pp-muted);
            margin-bottom: 8px;
            display: block;
        }

        .pp-input {
            width: 100%;
            padding: 15px 16px;
            border-radius: 14px;
            border: 2px solid var(--pp-border);
            background: var(--pp-soft);
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 22px;
            transition: .2s;
        }

        .pp-input:focus {
            outline: none;
            border-color: var(--pp-blue);
            background: #FFF;
            box-shadow: 0 0 0 3px rgba(43,100,227,.15);
        }

        /* Error */
        .pp-error {
            background: #FFF1F2;
            color: #BE123C;
            padding: 12px 14px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 22px;
            border-left: 4px solid #E11D48;
        }

        /* Button */
        .pp-btn {
            width: 100%;
            padding: 16px;
            border-radius: 18px;
            border: none;
            background: var(--pp-blue);
            color: #FFF;
            font-size: 15px;
            font-weight: 900;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: .25s;
        }

        .pp-btn:hover {
            box-shadow: 0 12px 26px rgba(43,100,227,.35);
        }

        .pp-btn:disabled {
            opacity: .85;
            cursor: not-allowed;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2.5px solid rgba(255,255,255,.4);
            border-top-color: #FFF;
            border-radius: 50%;
            animation: spin .8s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>

    <div class="pp-container">
        <div class="pp-header">
            <a href="{{ route('dashboard') }}" class="pp-back">←</a>
            <h1 class="pp-title">Buy Data</h1>
            <div style="width:42px"></div>
        </div>

        <p class="pp-subtitle">Select a network and enter the recipient number.</p>

        @if($errors->any())
            <div class="pp-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('services.data.select') }}" method="POST" id="dataForm">
            @csrf

            <label class="pp-label">Network Provider</label>

            <div class="net-grid">
                <div class="net-card" data-provider="mtn">
                    <img src="{{ asset('images/providers/mtn.png') }}" class="net-logo" alt="MTN">
                    <span class="net-name">MTN</span>
                </div>

                <div class="net-card" data-provider="airtel">
                    <img src="{{ asset('images/providers/airtel.png') }}" class="net-logo" alt="Airtel">
                    <span class="net-name">AIRTEL</span>
                </div>

                <div class="net-card" data-provider="glo">
                    <img src="{{ asset('images/providers/glo.png') }}" class="net-logo" alt="Glo">
                    <span class="net-name">GLO</span>
                </div>

                <div class="net-card" data-provider="9mobile">
                    <img src="{{ asset('images/providers/9mobile.png') }}" class="net-logo" alt="9mobile">
                    <span class="net-name">9MOBILE</span>
                </div>
            </div>

            <input type="hidden" name="provider" id="providerInput" value="{{ old('provider') }}">

            <label class="pp-label">Phone Number</label>
            <input type="tel"
                   name="phone"
                   class="pp-input"
                   placeholder="08012345678"
                   maxlength="11"
                   inputmode="numeric"
                   pattern="[0-9]{11}"
                   value="{{ old('phone') }}"
                   required>

            <button type="submit" class="pp-btn" id="submitBtn">
                <span class="spinner" id="spinner"></span>
                <span id="btnText">Continue</span>
            </button>
        </form>
    </div>

    <script>
        const cards = document.querySelectorAll('.net-card');
        const providerInput = document.getElementById('providerInput');

        cards.forEach(card => {
            card.addEventListener('click', () => {
                cards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                providerInput.value = card.dataset.provider;
            });
        });

        document.getElementById('dataForm').addEventListener('submit', function (e) {
            if (!providerInput.value) {
                e.preventDefault();
                alert('Please select a network provider.');
                return;
            }

            document.getElementById('submitBtn').disabled = true;
            document.getElementById('spinner').style.display = 'inline-block';
            document.getElementById('btnText').innerText = 'Connecting…';
        });

        @if(old('provider'))
            const prev = "{{ old('provider') }}";
            cards.forEach(card => {
                if (card.dataset.provider === prev) card.click();
            });
        @endif
    </script>
</x-app-layout>

<x-app-layout>
<style>
    :root {
        --pp-blue: #2B64E3;
        --pp-dark: #0F172A;
        --pp-muted: #64748B;
        --pp-soft: #F8FAFC;
        --pp-border: #E5E7EB;
        --pp-success: #16A34A;
    }

    .pp-shell {
        max-width: 440px;
        margin: 0 auto;
        padding: 22px;
        font-family: 'Inter', sans-serif;
        background: #FFF;
        min-height: 100vh;
    }

    /* =====================
       Recipient Card
    ====================== */
    .pp-recipient {
        background: linear-gradient(135deg, #F8FAFF, #FFFFFF);
        border: 1px solid var(--pp-border);
        padding: 18px;
        border-radius: 18px;
        margin-bottom: 26px;
    }

    .pp-recipient small {
        font-size: 11px;
        font-weight: 800;
        color: var(--pp-muted);
        text-transform: uppercase;
        letter-spacing: .6px;
    }

    .pp-recipient strong {
        display: block;
        margin-top: 6px;
        font-size: 15px;
        font-weight: 900;
        color: var(--pp-dark);
    }

    /* =====================
       Inputs
    ====================== */
    .pp-label {
        font-size: 12px;
        font-weight: 800;
        color: var(--pp-muted);
        margin-bottom: 8px;
        display: block;
    }

    .pp-input {
        width: 100%;
        padding: 16px;
        border-radius: 16px;
        border: 2px solid var(--pp-border);
        background: var(--pp-soft);
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 18px;
        transition: .25s;
    }

    .pp-input:focus {
        outline: none;
        border-color: var(--pp-blue);
        background: #FFF;
        box-shadow: 0 0 0 4px rgba(43,100,227,.12);
    }

    /* =====================
       Price Preview
    ====================== */
    .pp-price {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #F0F4FF;
        border: 1px dashed rgba(43,100,227,.4);
        padding: 14px 16px;
        border-radius: 14px;
        margin: 4px 0 22px;
        font-size: 14px;
        font-weight: 900;
        color: var(--pp-blue);
    }

    /* =====================
       Errors
    ====================== */
    .pp-error {
        background: #FEF2F2;
        border: 1px solid #FEE2E2;
        padding: 14px;
        border-radius: 14px;
        margin-bottom: 22px;
        animation: shake .35s ease;
    }

    .pp-error p {
        color: #991B1B;
        font-size: 13px;
        font-weight: 800;
        margin: 0;
    }

    @keyframes shake {
        25% { transform: translateX(-4px); }
        50% { transform: translateX(4px); }
        75% { transform: translateX(-2px); }
    }

    /* =====================
       Action Button
    ====================== */
    .pp-btn {
        width: 100%;
        padding: 18px;
        border-radius: 20px;
        border: none;
        background: linear-gradient(135deg, var(--pp-blue), #1E40AF);
        color: #FFF;
        font-size: 15px;
        font-weight: 900;
        cursor: pointer;
        transition: .3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        box-shadow: 0 12px 30px rgba(43,100,227,.35);
    }

    .pp-btn.processing {
        pointer-events: none;
        opacity: .95;
    }

    /* =====================
       Spinner (2026 style)
    ====================== */
    .spinner {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,.35);
        border-top-color: #FFF;
        animation: spin .8s linear infinite;
        display: none;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* =====================
       Footer Trust
    ====================== */
    .pp-trust {
        text-align: center;
        margin-top: 26px;
        font-size: 11px;
        font-weight: 800;
        color: #CBD5E1;
        letter-spacing: 2px;
    }
</style>

<div class="pp-shell">

    {{-- Error --}}
    @if($errors->any())
        <div class="pp-error">
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    {{-- Recipient --}}
    <div class="pp-recipient">
        <small>Recipient</small>
        <strong>{{ $phone }} • {{ strtoupper($provider) }}</strong>
    </div>

    <form action="{{ route('services.data.process') }}" method="POST" id="dataForm">
        @csrf

        <input type="hidden" name="provider" value="{{ old('provider', $provider) }}">
        <input type="hidden" name="phone" value="{{ old('phone', $phone) }}">
        <input type="hidden" name="amount" id="amt" value="{{ old('amount') }}">

        {{-- Plan --}}
        <label class="pp-label">Choose Data Bundle</label>
        <select name="plan" id="planSelect" class="pp-input" required>
            <option value="">Select a plan</option>
            @foreach($plans as $p)
                <option
                    value="{{ $p['variation_code'] }}"
                    data-price="{{ $p['variation_amount'] }}"
                    {{ old('plan') == $p['variation_code'] ? 'selected' : '' }}
                >
                    {{ $p['name'] }} — ₦{{ number_format($p['variation_amount']) }}
                </option>
            @endforeach
        </select>

        <div id="pricePreview"></div>

        {{-- PIN --}}
        <label class="pp-label">Transaction PIN</label>
        <input
            type="password"
            name="pin"
            maxlength="4"
            inputmode="numeric"
            class="pp-input"
            placeholder="••••"
            required
        >

        <button type="submit" class="pp-btn" id="payBtn">
            <span id="btnText">Confirm & Pay</span>
            <span class="spinner" id="btnSpinner"></span>
        </button>
    </form>

    <div class="pp-trust">PAYPOINT • SECURED TRANSACTION</div>
</div>

<script>
    const select = document.getElementById('planSelect');
    const priceEl = document.getElementById('pricePreview');
    const amtInput = document.getElementById('amt');

    function updatePrice() {
        const opt = select.options[select.selectedIndex];
        const price = opt?.dataset.price;

        if (price) {
            amtInput.value = price;
            priceEl.innerHTML = `
                <div class="pp-price">
                    <span>Total</span>
                    <span>₦${Number(price).toLocaleString()}</span>
                </div>
            `;
        } else {
            priceEl.innerHTML = '';
            amtInput.value = '';
        }
    }

    select.addEventListener('change', updatePrice);
    window.addEventListener('DOMContentLoaded', updatePrice);

    // Processing state (same UX as Airtime)
    document.getElementById('dataForm').addEventListener('submit', () => {
        document.getElementById('btnSpinner').style.display = 'inline-block';
        document.getElementById('btnText').innerText = 'Processing…';
        document.getElementById('payBtn').classList.add('processing');
    });
</script>
</x-app-layout>

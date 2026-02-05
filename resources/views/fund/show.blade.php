<x-app-layout>
    <style>
        .fund-surface { background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 20px; }
        .back-btn { font-size: 24px; color: #1e293b; text-decoration: none; margin-bottom: 20px; display: block; }

        /* Premium Card Design */
        .fund-card { background: #fff; border-radius: 30px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; text-align: center; }

        .bank-badge { display: inline-block; padding: 6px 12px; background: #e0e7ff; color: #4338ca; border-radius: 10px; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 15px; }

        .acc-display { background: #f8fafc; border: 2px dashed #e2e8f0; padding: 25px; border-radius: 24px; margin: 20px 0; position: relative; }
        .acc-number { font-size: 32px; font-weight: 900; color: #0f172a; letter-spacing: 1px; display: block; margin-bottom: 5px; }
        .acc-name { font-size: 14px; color: #64748b; font-weight: 600; text-transform: uppercase; }

        .copy-btn { background: #2B64E3; color: #fff; border: none; padding: 12px 24px; border-radius: 14px; font-weight: 800; font-size: 13px; cursor: pointer; transition: 0.2s; margin-top: 15px; }
        .copy-btn:active { transform: scale(0.95); }

        .instruction-box { text-align: left; margin-top: 30px; }
        .step { display: flex; gap: 15px; margin-bottom: 20px; }
        .step-num { width: 28px; height: 28px; background: #0f172a; color: #fff; border-radius: 50%; display: grid; place-items: center; font-size: 12px; font-weight: 800; flex-shrink: 0; }
        .step-text { font-size: 13px; color: #475569; line-height: 1.5; font-weight: 500; }
        .step-text strong { color: #1e293b; }

        .btn-confirm { width: 100%; margin-top: 20px; padding: 18px; background: #f1f5f9; color: #1e293b; border: none; border-radius: 20px; font-weight: 800; font-size: 15px; cursor: pointer; }
    </style>

    <div class="fund-surface">
        <a href="{{ route('dashboard') }}" class="back-btn">←</a>

        <h1 style="font-size: 24px; font-weight: 900; color: #1e293b; margin-bottom: 8px;">Add Money</h1>
        <p style="color: #64748b; font-size: 14px; margin-bottom: 30px;">Fund your <strong>SB-00000002</strong> account via bank transfer.</p>

        <div class="fund-card">
            <span class="bank-badge">{{ $bankDetails['bank_name'] }}</span>

            <div class="acc-display">
                <span class="acc-number" id="target-acc">{{ $bankDetails['account_number'] }}</span>
                <span class="acc-name">{{ $bankDetails['account_name'] }}</span>
                <br>
                <button class="copy-btn" onclick="copyAccount()">Copy Number</button>
            </div>

            <div class="instruction-box">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-text">Open your other bank app and select <strong>Transfer</strong>.</div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-text">Input the account number above and select <strong>{{ $bankDetails['bank_name'] }}</strong>.</div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-text">Use reference <strong>{{ $bankDetails['pay_reference'] }}</strong> for faster confirmation.</div>
                </div>
            </div>

            <button onclick="window.location.href='/dashboard'" class="btn-confirm">I've Sent the Money</button>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <p style="font-size: 12px; color: #94a3b8; font-weight: 600;">
                Need help? Contact <a href="mailto:{{ $bankDetails['support_email'] }}" style="color: #2B64E3; text-decoration: none;">Support</a>
            </p>
        </div>
    </div>
<script>
    function copyAccount() {
        const accNum = document.getElementById('target-acc').innerText;
        const btn = document.querySelector('.copy-btn');
        const originalText = btn.innerText;

        // 1. Try the modern Clipboard API
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(accNum).then(() => {
                applySuccessStyle(btn, originalText);
            }).catch(() => {
                fallbackCopy(accNum, btn, originalText);
            });
        } else {
            // 2. Fallback for insecure contexts (HTTP/IP addresses)
            fallbackCopy(accNum, btn, originalText);
        }
    }

    function fallbackCopy(text, btn, originalText) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        // Ensure the textarea is not visible but part of the DOM
        textArea.style.position = "fixed";
        textArea.style.left = "-9999px";
        textArea.style.top = "0";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            applySuccessStyle(btn, originalText);
        } catch (err) {
            console.error('Fallback copy failed', err);
        }
        document.body.removeChild(textArea);
    }

    function applySuccessStyle(btn, originalText) {
        btn.innerText = "✓ Copied!";
        btn.style.background = "#16A34A";

        setTimeout(() => {
            btn.innerText = originalText;
            btn.style.background = "#2B64E3";
        }, 2000);
    }
</script>
</x-app-layout>

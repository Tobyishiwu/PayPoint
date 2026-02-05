{{-- 1. SEND MONEY (TRANSFER) --}}
<div id="modal-transfer" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:20000; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; width:100%; max-width:400px; border-radius:24px; padding:24px; position:relative;">
        <button onclick="closeM('modal-transfer')" style="position:absolute; right:20px; top:20px; border:none; background:none; font-size:20px; cursor:pointer;">✕</button>
        <h3 style="font-weight:900; margin-bottom:20px; color:#101828;">Send Money</h3>
        <form action="{{ route('account.transfer.execute') }}" method="POST" class="secure-form">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:11px; font-weight:800; color:#667085; margin-bottom:6px;">RECIPIENT ACCOUNT NUMBER</label>
                <input type="text" name="recipient_account" placeholder="SB-00000000" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; font-weight:700;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:11px; font-weight:800; color:#667085; margin-bottom:6px;">AMOUNT (₦)</label>
                <input type="number" name="amount" min="100" placeholder="0.00" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; font-weight:700;">
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:11px; font-weight:800; color:#667085; margin-bottom:6px;">TRANSACTION PIN</label>
                <input type="password" name="pin" maxlength="4" minlength="4" placeholder="****" inputmode="numeric" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; font-weight:700;">
            </div>
            <button type="submit" style="width:100%; background:#00338D; color:#fff; padding:16px; border-radius:12px; border:none; font-weight:800; cursor:pointer;">Send Now</button>
        </form>
    </div>
</div>

{{-- 2. AIRTIME --}}
<div id="modal-airtime" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:20000; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; width:100%; max-width:400px; border-radius:24px; padding:24px; position:relative;">
        <button onclick="closeM('modal-airtime')" style="position:absolute; right:20px; top:20px; border:none; background:none; font-size:20px; cursor:pointer;">✕</button>
        <h3 style="font-weight:900; margin-bottom:20px; color:#101828;">Buy Airtime</h3>
        <form action="{{ route('airtime.store') }}" method="POST" class="secure-form">
            @csrf
            <select name="service_id" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; font-weight:700; margin-bottom:15px;">
                <option value="mtn">MTN Airtime</option>
                <option value="airtel">Airtel Airtime</option>
                <option value="glo">Glo Airtime</option>
                <option value="9mobile">9mobile Airtime</option>
            </select>
            <input type="number" name="phone" placeholder="Phone Number" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; margin-bottom:15px; font-weight:700;">
            <input type="number" name="amount" placeholder="Amount" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; margin-bottom:15px; font-weight:700;">
            <input type="password" name="pin" maxlength="4" placeholder="Transaction PIN" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; margin-bottom:20px; font-weight:700;">
            <button type="submit" style="width:100%; background:#00338D; color:#fff; padding:16px; border-radius:12px; border:none; font-weight:800; cursor:pointer;">Purchase Now</button>
        </form>
    </div>
</div>

{{-- 3. BILLS (ENUGU/EEDC READY) --}}
<div id="modal-utilities" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:20000; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; width:100%; max-width:400px; border-radius:24px; padding:24px; position:relative;">
        <button onclick="closeM('modal-utilities')" style="position:absolute; right:20px; top:20px; border:none; background:none; font-size:20px; cursor:pointer;">✕</button>
        <h3 style="font-weight:900; margin-bottom:20px; color:#101828;">Pay Bills</h3>
        <form id="utility-form" action="{{ route('utility.pay') }}" method="POST" class="secure-form">
            @csrf
            <select id="util-service-id" name="service_id" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; font-weight:700; margin-bottom:15px;">
                <option value="eedc">EEDC (Enugu Electric)</option>
                <option value="phed">PHED (Port Harcourt)</option>
                <option value="ikedc">IKEDC (Ikeja Electric)</option>
                <option value="dstv">DSTV Subscription</option>
                <option value="gotv">GOTV Payment</option>
            </select>

            <select name="variation_code" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; font-weight:700; margin-bottom:15px;">
                <option value="prepaid">Prepaid Meter</option>
                <option value="postpaid">Postpaid Meter</option>
            </select>

            <div style="position:relative; margin-bottom:15px;">
                <input type="text" id="util-bill-number" name="bill_number" placeholder="Meter or SmartCard Number" required style="width:100%; padding:14px; padding-right:80px; border-radius:12px; border:1px solid #D0D5DD; font-weight:700;">
                <button type="button" onclick="verifyUtility()" style="position:absolute; right:10px; top:10px; background:#E0F2FE; color:#0369A1; border:none; padding:6px 12px; border-radius:8px; font-size:11px; font-weight:800; cursor:pointer;">VERIFY</button>
            </div>

            <div id="verify-display" style="display:none; background:#F0FDF4; border:1px solid #BBF7D0; padding:12px; border-radius:12px; margin-bottom:15px;">
                <p id="verify-name" style="color:#166534; font-size:12px; font-weight:800; margin:0;"></p>
                <p id="verify-address" style="color:#166534; font-size:10px; margin:0; opacity:0.8;"></p>
            </div>

            <input type="number" name="amount" placeholder="Amount" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; margin-bottom:15px; font-weight:700;">
            <input type="password" name="pin" maxlength="4" placeholder="Transaction PIN" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; margin-bottom:20px; font-weight:700;">
            <button type="submit" style="width:100%; background:#00338D; color:#fff; padding:16px; border-radius:12px; border:none; font-weight:800; cursor:pointer;">Pay Bill Now</button>
        </form>
    </div>
</div>

{{-- 4. FUND ACCOUNT --}}
<div id="modal-deposit" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:20000; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; width:100%; max-width:400px; border-radius:24px; padding:24px; position:relative;">
        <button onclick="closeM('modal-deposit')" style="position:absolute; right:20px; top:20px; border:none; background:none; font-size:20px; cursor:pointer;">✕</button>
        <h3 style="font-weight:900; margin-bottom:20px; color:#101828;">Fund Account</h3>
        <form action="{{ route('account.deposit') }}" method="POST" class="secure-form">
            @csrf
            <input type="number" name="amount" placeholder="Amount to Fund (₦)" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #D0D5DD; margin-bottom:20px; font-weight:700;">
            <button type="submit" style="width:100%; background:#00338D; color:#fff; padding:16px; border-radius:12px; border:none; font-weight:800; cursor:pointer;">Deposit Funds</button>
        </form>
    </div>
</div>

{{-- 5. TRANSACTION RECEIPT --}}
<div id="modal-receipt" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:30000; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; width:100%; max-width:380px; border-radius:32px; padding:30px; text-align:center; position:relative;">
        <button onclick="closeM('modal-receipt')" style="position:absolute; right:24px; top:24px; border:none; background:#F2F4F7; width:32px; height:32px; border-radius:50%; cursor:pointer;">✕</button>
        <div style="width:70px; height:70px; border-radius:50%; background:#16A34A; display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
            <span style="font-size:30px; color:white;">✓</span>
        </div>
        <h2 id="receipt-amount" style="font-size:32px; font-weight:900; margin-bottom:4px;"></h2>
        <p id="receipt-date" style="font-size:14px; color:#667085; margin-bottom:24px;"></p>
        <div style="background:#F8FAFC; border-radius:20px; padding:20px; text-align:left;">
            <div id="receipt-token-section" style="display:none; background:#FFFBEB; border:1px dashed #F59E0B; padding:15px; border-radius:12px; margin-bottom:15px; text-align:center;">
                <span style="color:#B45309; font-size:10px; font-weight:800; display:block; margin-bottom:5px;">TOKEN / PIN</span>
                <strong id="receipt-token" style="font-size:18px; color:#1E293B; letter-spacing:2px; display:block; margin-bottom:10px;"></strong>
                <button type="button" onclick="copyToken()" style="background:#FEF3C7; color:#92400E; border:1px solid #F59E0B; padding:6px 15px; border-radius:8px; font-size:10px; cursor:pointer; font-weight:800;">COPY TOKEN</button>
            </div>
            <p style="font-size:12px; color:#64748B; margin-bottom:4px;">REF: <span id="receipt-ref" style="font-weight:800; color:#1E293B;"></span></p>
            <p id="receipt-desc" style="font-weight:700; color:#1E293B; font-size:14px;"></p>
        </div>
        <button onclick="closeM('modal-receipt')" style="width:100%; margin-top:20px; background:#00338D; color:white; padding:16px; border-radius:12px; border:none; font-weight:800; cursor:pointer;">Done</button>
    </div>
</div>

<script>
/**
 * ROBUST COPY TOKEN LOGIC (HTTPS & HTTP FALLBACK)
 */
function copyToken() {
    const token = document.getElementById('receipt-token').innerText;

    // Attempt modern Clipboard API
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(token).then(() => {
            alert('Token Copied!');
        }).catch(() => fallbackCopy(token));
    } else {
        fallbackCopy(token);
    }
}

function fallbackCopy(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-9999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        alert('Token Copied!');
    } catch (err) {
        alert('Copy failed. Please copy manually: ' + text);
    }
    document.body.removeChild(textArea);
}

// --- VERIFY UTILITY ---
async function verifyUtility() {
    const service_id = document.getElementById('util-service-id').value;
    const bill_number = document.getElementById('util-bill-number').value;
    const display = document.getElementById('verify-display');
    const nameEl = document.getElementById('verify-name');

    if(!bill_number) return alert('Enter a meter number first');

    nameEl.innerText = 'Verifying...';
    display.style.display = 'block';

    try {
        const response = await fetch("{{ route('utility.verify') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ service_id, bill_number })
        });
        const res = await response.json();
        if(res.status === 'success') {
            nameEl.innerText = res.customer_name;
            document.getElementById('verify-address').innerText = res.address;
        } else {
            nameEl.innerText = 'Verify failed: ' + res.message;
        }
    } catch (e) {
        nameEl.innerText = 'Connection error during verification';
    }
}

function showReceipt(data) {
    document.getElementById('receipt-amount').innerText = '₦' + data.amount;
    document.getElementById('receipt-date').innerText = data.date;
    document.getElementById('receipt-ref').innerText = data.reference;
    document.getElementById('receipt-desc').innerText = data.description;

    const tokenSec = document.getElementById('receipt-token-section');
    const tokenEl = document.getElementById('receipt-token');

    if (data.token) {
        tokenSec.style.display = 'block';
        tokenEl.innerText = data.token.replace('Token : ', '');
    } else {
        tokenSec.style.display = 'none';
    }

    openM('modal-receipt');
}

// SECURE FORM HANDLER
document.querySelectorAll('.secure-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const loader = document.getElementById('loading-guard');
        if(loader) loader.style.display = 'flex';

        try {
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const res = await response.json();
            if(loader) loader.style.display = 'none';

            if (res.status === 'success') {
                const balanceEl = document.getElementById('user-balance');
                if(balanceEl) balanceEl.innerText = '₦' + res.new_balance;

                this.closest('[id^="modal-"]').style.display = 'none';
                document.body.style.overflow = 'auto';

                showReceipt(res.data);
                this.reset();
                document.getElementById('verify-display').style.display = 'none';
            } else {
                alert(res.message || 'Transaction Failed');
            }
        } catch (error) {
            if(loader) loader.style.display = 'none';
            alert('Connection error. Please try again.');
        }
    });
});
</script>

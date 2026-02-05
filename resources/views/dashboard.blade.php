<x-app-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

@php
    $account = $account ?? Auth::user()->account;
@endphp

<style>
:root {
    --pp-blue:#2B64E3;
    --pp-blue-dark:#1E40AF;
    --pp-dark:#0F172A;
    --pp-muted:#64748B;
    --pp-soft:#F8FAFC;
    --pp-border:#E5E7EB;
    --pp-success:#16A34A;
}

/* ========== BASE ========== */
body {
    background:#F8FAFC;
    font-family:'Inter',sans-serif;
    color:var(--pp-dark);
}

/* ========== HEADER ========== */
.pp-header {
    padding:20px 22px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    position:sticky;
    top:0;
    background:rgba(255,255,255,.85);
    backdrop-filter:blur(12px);
    z-index:100;
}

.pp-user {
    display:flex;
    gap:12px;
    align-items:center;
}

.pp-avatar {
    width:44px;
    height:44px;
    border-radius:16px;
    border:1.5px solid var(--pp-border);
}

.pp-app {
    font-size:16px;
    font-weight:900;
    margin:0;
}

.pp-tag {
    font-size:11px;
    font-weight:800;
    color:var(--pp-blue);
}

/* ========== BALANCE CARD ========== */
.balance-wrap {
    padding:0 20px;
    margin-bottom:28px;
}

.balance-card {
    border-radius:36px;
    padding:32px 26px;
    color:#fff;
    background:
        radial-gradient(circle at top right, var(--pp-blue) 0%, var(--pp-dark) 65%);
    box-shadow:0 30px 60px -20px rgba(43,100,227,.45);
}

.balance-label {
    font-size:11px;
    font-weight:800;
    opacity:.65;
    letter-spacing:1.6px;
    text-transform:uppercase;
}

.balance-amount {
    font-size:38px;
    font-weight:900;
    margin:14px 0;
    display:flex;
    align-items:center;
    gap:12px;
}

.balance-actions {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:26px;
}

.btn-fund {
    background:#fff;
    color:var(--pp-dark);
    padding:12px 24px;
    border-radius:16px;
    font-weight:900;
    font-size:13px;
    text-decoration:none;
}

.pp-id {
    background:rgba(255,255,255,.06);
    border:1px solid rgba(255,255,255,.15);
    border-radius:14px;
    padding:8px 12px;
    cursor:pointer;
    text-align:right;
}

/* ========== SERVICES ========== */
.services-title {
    padding:0 22px 14px;
    font-size:13px;
    font-weight:900;
    color:var(--pp-muted);
    text-transform:uppercase;
}

.services-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:12px;
    padding:0 20px;
}

.service-card {
    background:var(--pp-soft);
    border-radius:26px;
    padding:22px 10px;
    text-align:center;
    text-decoration:none;
    border:1px solid transparent;
    transition:.25s;
}

.service-card:active {
    transform:scale(.96);
    background:#EEF2FF;
    border-color:rgba(43,100,227,.2);
}

.service-icon {
    font-size:26px;
    margin-bottom:10px;
}

.service-name {
    font-size:11px;
    font-weight:800;
    color:#475569;
}

/* ========== MODALS ========== */
.modal {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.6);
    backdrop-filter:blur(10px);
    z-index:9999;
    align-items:flex-end;
}

.modal-content {
    background:#fff;
    width:100%;
    padding:38px 24px;
    border-radius:36px 36px 0 0;
    text-align:center;
}

.success-icon {
    width:72px;
    height:72px;
    border-radius:50%;
    display:grid;
    place-items:center;
    font-size:32px;
    margin:0 auto 18px;
    background:#DCFCE7;
    color:var(--pp-success);
}

.btn-primary {
    width:100%;
    padding:18px;
    border-radius:20px;
    border:none;
    background:linear-gradient(135deg,var(--pp-blue),var(--pp-blue-dark));
    color:#fff;
    font-weight:900;
    cursor:pointer;
}
</style>

{{-- HEADER --}}
<header class="pp-header">
    <div class="pp-user">
        <img class="pp-avatar"
             src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2B64E3&color=fff&bold=true">
        <div>
            <p class="pp-app">PayPoint</p>
            <p class="pp-tag">{{ Auth::user()->pay_tag ?? '@member' }}</p>
        </div>
    </div>

    <div style="display:flex;gap:10px;">
        <button onclick="toggleModal('fundModal')" style="border:none;background:var(--pp-soft);width:42px;height:42px;border-radius:14px;">🏛️</button>
        <button onclick="location.href='{{ route('history') }}'" style="border:none;background:var(--pp-soft);width:42px;height:42px;border-radius:14px;">🕒</button>
    </div>
</header>

{{-- BALANCE --}}
<section class="balance-wrap">
    <div class="balance-card">
        <span class="balance-label">Total Balance</span>
        <div class="balance-amount">
            <span id="balText">₦{{ number_format($account->balance ?? 0,2) }}</span>
            <span onclick="toggleBal()" style="cursor:pointer;opacity:.5;">👁️</span>
        </div>

        <div class="balance-actions">
            <a href="{{ route('wallet.fund') }}" class="btn-fund">Add Money</a>
            <div class="pp-id" onclick="copyID()">
                <small style="font-size:8px;opacity:.5;">PAYPOINT ID</small><br>
                <strong id="ppID">{{ $account->account_number ?? 'SB-00000002' }}</strong>
            </div>
        </div>
    </div>
</section>

{{-- SERVICES --}}
<h3 class="services-title">Services</h3>
<div class="services-grid">
@php
$services = [
    ['Airtime','📱','services.airtime'],
    ['Data','📶','services.data.index'],
    ['Electricity','⚡','services.electricity.index'],
    ['Cable TV','📺','services.tv.index'],
    ['Internet','🌐','wifi'],
    ['E-Sim','📟','esim'],
];
@endphp

@foreach($services as [$name,$icon,$route])
<a class="service-card" href="{{ Route::has($route) ? route($route) : '#' }}">
    <div class="service-icon">{{ $icon }}</div>
    <div class="service-name">{{ $name }}</div>
</a>
@endforeach
</div>

{{-- FUNDING MODAL --}}
<div id="fundModal" class="modal" onclick="if(event.target===this)toggleModal('fundModal')">
    <div class="modal-content">
        <h3 style="font-weight:900;">Wallet Funding</h3>
        <p style="color:var(--pp-muted);font-size:14px;">Transfer to your PayPoint ID</p>

        <div style="margin:26px 0;padding:22px;border-radius:22px;border:1.5px dashed var(--pp-border);background:var(--pp-soft);">
            <div style="font-size:26px;font-weight:900;font-family:monospace;">{{ $account->account_number ?? 'SB-00000002' }}</div>
            <small style="color:var(--pp-muted);font-weight:700;">{{ strtoupper(Auth::user()->name) }}</small>
        </div>

        <button class="btn-primary" onclick="toggleModal('fundModal')">I’ve made the transfer</button>
    </div>
</div>

{{-- RECEIPT --}}
@if(session('receipt'))
@php $r=session('receipt'); @endphp
<div class="modal" style="display:flex;">
    <div class="modal-content">
        <div id="receiptArea">
            <div class="success-icon">✓</div>
            <h2 style="font-weight:900;">Payment Successful</h2>

            <div style="background:var(--pp-soft);padding:20px;border-radius:20px;text-align:left;margin:20px 0;">
                <p><strong>Service:</strong> {{ $r['service'] }}</p>
                <p><strong>Amount:</strong> ₦{{ number_format($r['amount'],2) }}</p>
                @foreach($r['meta'] ?? [] as $k=>$v)
                    <p><strong>{{ ucfirst($k) }}:</strong> {{ $v }}</p>
                @endforeach
                <small><strong>Ref:</strong> {{ $r['reference'] }}</small>
            </div>
        </div>

        <button class="btn-primary" onclick="downloadReceipt()">Download Receipt</button>
    </div>
</div>
@endif

<script>
let visible=true;
function toggleBal(){
    visible=!visible;
    document.getElementById('balText').innerText =
        visible ? "₦{{ number_format($account->balance ?? 0,2) }}" : "₦ ••••••";
}
function copyID(){
    navigator.clipboard.writeText(document.getElementById('ppID').innerText);
    alert('PayPoint ID Copied');
}
function toggleModal(id){
    const el=document.getElementById(id);
    el.style.display=el.style.display==='flex'?'none':'flex';
}
function downloadReceipt(){
    html2canvas(document.getElementById('receiptArea')).then(c=>{
        const a=document.createElement('a');
        a.href=c.toDataURL();
        a.download='PayPoint-Receipt.png';
        a.click();
    });
}
</script>
</x-app-layout>

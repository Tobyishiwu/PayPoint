<x-app-layout>
<style>
:root {
    --pp-blue:#2B64E3;
    --pp-dark:#0F172A;
    --pp-muted:#64748B;
    --pp-soft:#F8FAFC;
    --pp-border:#E5E7EB;
    --pp-success:#16A34A;
    --pp-failed:#EF4444;
    --pp-pending:#F59E0B;
}

/* Page */
.history-wrap {
    min-height:100vh;
    padding:20px;
    background:var(--pp-soft);
    font-family:'Inter',sans-serif;
}

/* Header */
.history-header {
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:24px;
}

.history-header h1 {
    font-size:18px;
    font-weight:900;
    margin:0;
}

/* Transaction Row */
.txn {
    background:#fff;
    border-radius:18px;
    padding:16px;
    border:1px solid var(--pp-border);
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
    cursor:pointer;
    transition:.15s;
}

.txn:hover {
    border-color:rgba(43,100,227,.25);
}

.txn-left {
    display:flex;
    gap:12px;
    align-items:center;
}

.txn-icon {
    width:42px;
    height:42px;
    border-radius:14px;
    background:var(--pp-soft);
    display:grid;
    place-items:center;
    font-size:18px;
}

.txn-title {
    font-size:13px;
    font-weight:800;
}

.txn-date {
    font-size:11px;
    color:var(--pp-muted);
    font-weight:600;
}

.txn-right {
    text-align:right;
}

.txn-amount {
    font-size:14px;
    font-weight:900;
}

.txn-status {
    font-size:9px;
    font-weight:900;
    text-transform:uppercase;
    margin-top:4px;
}

/* Receipt Modal */
#receiptModal {
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.65);
    backdrop-filter:blur(10px);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

.receipt {
    background:#fff;
    width:100%;
    max-width:360px;
    border-radius:28px;
    padding:28px;
    animation:slideUp .25s ease;
}

@keyframes slideUp {
    from { transform:translateY(20px); opacity:0; }
    to { transform:translateY(0); opacity:1; }
}

.receipt-head {
    text-align:center;
    margin-bottom:22px;
}

.receipt-check {
    width:56px;
    height:56px;
    border-radius:50%;
    display:grid;
    place-items:center;
    margin:0 auto 12px;
    background:#E0EDFF;
    color:var(--pp-blue);
    font-size:24px;
}

.receipt h3 {
    margin:0;
    font-size:17px;
    font-weight:900;
}

.receipt-row {
    display:flex;
    justify-content:space-between;
    padding:12px 0;
    border-bottom:1px solid var(--pp-soft);
}

.receipt-row:last-child {
    border:none;
}

.receipt-label {
    font-size:11px;
    font-weight:800;
    color:var(--pp-muted);
    text-transform:uppercase;
}

.receipt-val {
    font-size:13px;
    font-weight:900;
    text-align:right;
}

.btn-close {
    margin-top:22px;
    width:100%;
    padding:16px;
    border:none;
    border-radius:18px;
    background:var(--pp-dark);
    color:#fff;
    font-weight:900;
    cursor:pointer;
}
</style>

<div class="history-wrap">

    <div class="history-header">
        <a href="{{ route('dashboard') }}" style="font-size:22px;text-decoration:none;color:var(--pp-dark);">←</a>
        <h1>Activity</h1>
    </div>

    @forelse($transactions as $trx)
        @php
            $title = strtoupper($trx->title ?: ($trx->category === 'data' ? 'DATA BUNDLE' : 'AIRTIME'));
            $statusColor = match($trx->status) {
                'failed' => 'var(--pp-failed)',
                'pending' => 'var(--pp-pending)',
                default => 'var(--pp-success)',
            };
        @endphp

        <div class="txn"
            onclick="openReceipt(this)"
            data-service="{{ $title }}"
            data-amount="₦{{ number_format($trx->amount,2) }}"
            data-status="{{ strtoupper($trx->status) }}"
            data-ref="{{ $trx->reference }}"
            data-date="{{ $trx->created_at->format('d M Y • h:i A') }}"
        >
            <div class="txn-left">
                <div class="txn-icon">
                    {{ $trx->category === 'data' ? '📡' : '📱' }}
                </div>
                <div>
                    <div class="txn-title">{{ $title }}</div>
                    <div class="txn-date">{{ $trx->created_at->format('d M • h:i A') }}</div>
                </div>
            </div>

            <div class="txn-right">
                <div class="txn-amount">-₦{{ number_format($trx->amount,2) }}</div>
                <div class="txn-status" style="color:{{ $statusColor }}">
                    {{ $trx->status }}
                </div>
            </div>
        </div>
    @empty
        <p style="text-align:center;color:var(--pp-muted);margin-top:80px;">
            No transactions yet.
        </p>
    @endforelse
</div>

{{-- RECEIPT MODAL --}}
<div id="receiptModal" onclick="closeReceipt()">
    <div class="receipt" onclick="event.stopPropagation()">
        <div class="receipt-head">
            <div class="receipt-check">✓</div>
            <h3>Transaction Receipt</h3>
        </div>

        <div class="receipt-row">
            <span class="receipt-label">Service</span>
            <span class="receipt-val" id="r-service"></span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Amount</span>
            <span class="receipt-val" id="r-amount"></span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Status</span>
            <span class="receipt-val" id="r-status"></span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Reference</span>
            <span class="receipt-val" id="r-ref" style="font-size:11px;"></span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Date</span>
            <span class="receipt-val" id="r-date"></span>
        </div>

        <button class="btn-close" onclick="closeReceipt()">Done</button>
    </div>
</div>

<script>
function openReceipt(el){
    document.getElementById('r-service').innerText = el.dataset.service;
    document.getElementById('r-amount').innerText  = el.dataset.amount;
    document.getElementById('r-status').innerText  = el.dataset.status;
    document.getElementById('r-ref').innerText     = el.dataset.ref;
    document.getElementById('r-date').innerText    = el.dataset.date;

    document.getElementById('r-status').style.color =
        el.dataset.status === 'FAILED' ? 'var(--pp-failed)' :
        el.dataset.status === 'PENDING' ? 'var(--pp-pending)' :
        'var(--pp-success)';

    document.getElementById('receiptModal').style.display = 'flex';
}

function closeReceipt(){
    document.getElementById('receiptModal').style.display = 'none';
}
</script>
</x-app-layout>

@php
    // Logic to determine if money is going out or coming in
    $isOut = in_array($tx->type, ['debit', 'withdrawal', 'transfer', 'airtime']);

    // Pro Max Icon Mapping
    $categoryIcons = [
        'airtime'     => '📱',
        'data'        => '📶',
        'electricity' => '💡',
        'cabletv'     => '📺',
        'transfer'    => $isOut ? '📤' : '📥',
        'deposit'     => '💰'
    ];
@endphp

<div class="pm-tx-item" onclick="showReceipt({
    'amount': '{{ number_format($tx->amount, 2) }}',
    'desc': '{{ $tx->description }}',
    'date': '{{ $tx->created_at->format('d M, g:i A') }}',
    'ref': '{{ $tx->reference }}',
    'token': '{{ $tx->token }}',
    'isOut': {{ $isOut ? 'true' : 'false' }}
})">
    <div class="pm-tx-info">
        <div class="pm-tx-icon {{ $isOut ? 'pm-out' : 'pm-in' }}">
            {{ $categoryIcons[$tx->category] ?? ($isOut ? '➘' : '➚') }}
        </div>
        <div>
            <p class="pm-tx-title">{{ ucfirst($tx->category ?? $tx->type) }}</p>
            <p class="pm-tx-date">{{ $tx->created_at->format('d M, g:i A') }}</p>
        </div>
    </div>
    <div class="pm-tx-amount {{ $isOut ? 'pm-out' : 'pm-in' }}">
        {{ $isOut ? '-' : '+' }}₦{{ number_format($tx->amount, 2) }}
    </div>
</div>

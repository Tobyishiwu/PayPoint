<x-app-layout>
    <style>
        .receipt-wrap {
            padding: 24px 18px;
        }

        .receipt-card {
            background: #ffffff;
            border-radius: 26px;
            padding: 28px 22px;
            box-shadow: 0 12px 30px rgba(0,0,0,.06);
            border: 1px solid #f1f5f9;
            text-align: center;
        }

        .check-icon {
            width: 54px;
            height: 54px;
            background: #16A34A;
            color: #fff;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 26px;
            margin: 0 auto 14px;
        }

        .receipt-title {
            font-size: 18px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 18px;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px dashed #e5e7eb;
            font-size: 14px;
        }

        .receipt-row:last-child {
            border-bottom: none;
        }

        .label {
            color: #64748b;
            font-weight: 600;
        }

        .value {
            color: #0f172a;
            font-weight: 800;
            text-align: right;
        }

        .status {
            color: #16A34A;
            font-weight: 900;
            letter-spacing: .4px;
        }

        .receipt-btn {
            margin-top: 26px;
            width: 100%;
            padding: 16px;
            border-radius: 18px;
            background: #0f172a;
            color: #fff;
            font-weight: 800;
            font-size: 15px;
            border: none;
            cursor: pointer;
        }
    </style>

    <div class="receipt-wrap">
        <div class="receipt-card">
            <div class="check-icon">✓</div>

            <div class="receipt-title">Transaction Receipt</div>

            <div class="receipt-row">
                <span class="label">Service</span>
                <span class="value">{{ strtoupper($transaction->type) }}</span>
            </div>

            <div class="receipt-row">
                <span class="label">Amount</span>
                <span class="value">₦{{ number_format($transaction->amount, 2) }}</span>
            </div>

            <div class="receipt-row">
                <span class="label">Status</span>
                <span class="value status">{{ strtoupper($transaction->status) }}</span>
            </div>

            <div class="receipt-row">
                <span class="label">Reference</span>
                <span class="value">{{ $transaction->reference }}</span>
            </div>

            <div class="receipt-row">
                <span class="label">Date</span>
                <span class="value">
                    {{ $transaction->created_at->format('d M, Y • h:i A') }}
                </span>
            </div>

            <button class="receipt-btn" onclick="window.location.href='{{ route('dashboard') }}'">
                Done
            </button>
        </div>
    </div>
</x-app-layout>

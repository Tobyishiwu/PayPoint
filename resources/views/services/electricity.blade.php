<x-app-layout>
    <style>
        :root { --pp-blue: #2B64E3; --pp-dark: #0F172A; }
        .service-surface { background: #F8FAFC; min-height: 100vh; padding: 20px; font-family: 'Inter', sans-serif; }
        .form-card { background: #fff; border-radius: 24px; padding: 25px; border: 1px solid #F1F5F9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-size: 11px; font-weight: 800; color: #94A3B8; text-transform: uppercase; margin-bottom: 8px; }
        .input-field { width: 100%; padding: 16px; border-radius: 16px; border: 1px solid #F1F5F9; background: #F8FAFC; font-weight: 700; outline: none; transition: 0.3s; }
        .input-field:focus { border-color: var(--pp-blue); background: #fff; }

        #verify-box { display: none; background: #F0F7FF; border: 1.5px dashed var(--pp-blue); padding: 15px; border-radius: 16px; margin-bottom: 20px; }
        .btn-pay { width: 100%; padding: 20px; background: var(--pp-dark); color: #fff; border: none; border-radius: 18px; font-weight: 800; cursor: pointer; transition: 0.3s; }
        .btn-pay:disabled { background: #E2E8F0; cursor: not-allowed; color: #94A3B8; }

        .loading-dots:after { content: '.'; animation: dots 1s steps(5, end) infinite; }
        @keyframes dots { 0%, 20% { color: rgba(0,0,0,0); text-shadow: .25em 0 0 rgba(0,0,0,0), .5em 0 0 rgba(0,0,0,0); } 40% { color: #2B64E3; text-shadow: .25em 0 0 rgba(0,0,0,0), .5em 0 0 rgba(0,0,0,0); } 60% { text-shadow: .25em 0 0 #2B64E3, .5em 0 0 rgba(0,0,0,0); } 80%, 100% { text-shadow: .25em 0 0 #2B64E3, .5em 0 0 #2B64E3; } }
    </style>

    <div class="service-surface">
        <div class="back-nav" style="display:flex; align-items:center; gap:15px; margin-bottom:25px;">
            <a href="{{ route('dashboard') }}" style="text-decoration:none; font-size:24px; color:var(--pp-dark);">←</a>
            <h1 style="font-size:20px; font-weight:900; margin:0;">Electricity Bill</h1>
        </div>

        <div class="form-card">
            <form action="{{ route('services.electricity.process') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label>Provider</label>
                    <select name="provider" id="provider" class="input-field" onchange="resetValidation()">
                        <option value="">Select DISCO</option>
                        @foreach($providers as $disco)
                            {{-- Value is the serviceID slug needed for VTpass --}}
                            <option value="{{ $disco['serviceID'] }}">{{ $disco['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <label>Meter Type</label>
                    <select name="meter_type" id="meter_type" class="input-field" onchange="resetValidation()">
                        <option value="prepaid">Prepaid</option>
                        <option value="postpaid">Postpaid</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Meter Number</label>
                    <input type="number" name="meter_number" id="meter_number" class="input-field" placeholder="Try 1111111111111" oninput="handleMeterInput()">
                </div>

                <div id="verify-box">
                    <p style="margin:0; font-size:10px; color:var(--pp-blue); font-weight:800;">ACCOUNT HOLDER</p>
                    <h4 id="customer_name" style="margin:5px 0 0; font-weight:900; color:var(--pp-dark);">Searching...</h4>
                </div>

                <div class="input-group">
                    <label>Amount (₦)</label>
                    <input type="number" name="amount" class="input-field" placeholder="Min 1,000">
                </div>

                <div class="input-group" style="margin-bottom:30px;">
                    <label>Transaction PIN</label>
                    <input type="password" name="pin" class="input-field" maxlength="4" placeholder="••••">
                </div>

                <button type="submit" id="pay-btn" class="btn-pay" disabled>Verify Meter First</button>
            </form>
        </div>
    </div>



    <script>
        let timer;
        function handleMeterInput() {
            clearTimeout(timer);
            resetValidation();
            const meter = document.getElementById('meter_number').value;
            const provider = document.getElementById('provider').value;
            if(meter.length >= 10 && provider) {
                timer = setTimeout(validateMeter, 800);
            }
        }

        async function validateMeter() {
            const meter = document.getElementById('meter_number').value;
            const provider = document.getElementById('provider').value;
            const type = document.getElementById('meter_type').value;
            const box = document.getElementById('verify-box');
            const nameEl = document.getElementById('customer_name');
            const btn = document.getElementById('pay-btn');

            box.style.display = 'block';
            nameEl.innerHTML = 'Verifying meter<span class="loading-dots"></span>';
            nameEl.style.color = "var(--pp-dark)";

            try {
                const res = await fetch(`{{ route('services.electricity.validate') }}?meter=${meter}&provider=${provider}&type=${type}`);
                const data = await res.json();

                // Check for successful validation in VTpass response
                if(data.content && data.content.Customer_Name) {
                    nameEl.innerText = data.content.Customer_Name;
                    nameEl.style.color = "#10B981";
                    btn.disabled = false;
                    btn.innerText = "Pay Bill Now";
                } else {
                    nameEl.innerText = data.response_description || "Invalid Meter Number";
                    nameEl.style.color = "#EF4444";
                    btn.disabled = true;
                }
            } catch (e) {
                nameEl.innerText = "Connection Error";
                btn.disabled = true;
            }
        }

        function resetValidation() {
            document.getElementById('verify-box').style.display = 'none';
            document.getElementById('pay-btn').disabled = true;
            document.getElementById('pay-btn').innerText = "Verify Meter First";
        }
    </script>
</x-app-layout>

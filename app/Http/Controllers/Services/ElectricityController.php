<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\{Auth, DB, Hash, Http};

class ElectricityController extends Controller
{
    public function index() {
        // Fetch real DISCOs from VTpass Sandbox
        $response = Http::get('https://sandbox.vtpass.com/api/services', [
            'identifier' => 'electricity-bill'
        ]);

        $providers = $response->successful() ? $response->json()['content'] : [];
        return view('services.electricity', compact('providers'));
    }

    public function validateMeter(Request $request) {
        // Ensure keys are set in config/services.php
        $apiKey = config('services.vtpass.api_key');
        $publicKey = config('services.vtpass.public_key');

        $response = Http::withHeaders([
            'api-key' => $apiKey,
            'public-key' => $publicKey,
        ])->post('https://sandbox.vtpass.com/api/merchant-verify', [
            'serviceID' => $request->provider, // Must be the slug (e.g., ikeja-electric)
            'billersCode' => $request->meter,
            'type' => $request->type,
        ]);

        return response()->json($response->json());
    }

    public function process(Request $request) {
        $request->validate([
            'meter_number' => 'required',
            'provider' => 'required',
            'meter_type' => 'required',
            'amount' => 'required|numeric|min:1000',
            'pin' => 'required|size:4'
        ]);

        $user = Auth::user();
        $account = $user->account; // Account format: SB-00000002

        if (!Hash::check($request->pin, $user->transaction_pin)) {
            return back()->withErrors(['pin' => 'Incorrect Transaction PIN.']);
        }

        if (!$account || $account->balance < $request->amount) {
            return back()->withErrors(['amount' => "Insufficient balance in account."]);
        }

        return DB::transaction(function () use ($request, $account, $user) {
            $account->decrement('balance', $request->amount);

            $ref = 'PP-ELEC-'.date('YmdHis').strtoupper(bin2hex(random_bytes(2)));
            $discoName = strtoupper(str_replace(['-electric', '-electricity', '-payment'], '', $request->provider));

            Transaction::create([
                'user_id' => $user->id,
                'account_id' => $account->id,
                'type' => 'debit',
                'category' => 'electricity',
                'title' => "$discoName BILL",
                'amount' => $request->amount,
                'balance_after' => $account->fresh()->balance,
                'description' => "Meter: $request->meter_number ($request->meter_type)",
                'reference' => $ref,
                'status' => 'completed'
            ]);

            return redirect()->route('dashboard')->with('electricity_success', [
                'amount' => $request->amount,
                'meter' => $request->meter_number,
                'provider' => "$discoName Electric",
                'ref' => $ref,
                'token' => '9928-1102-3394-1102-8821' // Sandbox sample token
            ]);
        });
    }
}

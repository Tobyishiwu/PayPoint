<?php
namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Support\Str;

class CableTvController extends Controller
{
    public function store(Request $request) {
        $request->validate(['service_id' => 'required', 'variation_code' => 'required', 'amount' => 'required', 'identifier' => 'required']);
        $user = Auth::user(); $account = $user->account;

        if ($account->balance < $request->amount) return back()->with('error', 'Insufficient funds for TV subscription.');

        try {
            DB::transaction(function () use ($user, $account, $request) {
                $account->decrement('balance', $request->amount);
                Transaction::create([
                    'user_id' => $user->id, 'account_id' => $account->id, 'category' => 'tv',
                    'type' => 'debit', 'amount' => $request->amount, 'balance_after' => $account->balance,
                    'status' => 'successful', 'reference' => 'SB_TV_'.strtoupper(Str::random(8)),
                    'description' => strtoupper($request->service_id)." (".$request->variation_code.") for SmartCard: ".$request->identifier,
                    'service_id' => $request->service_id, 'variation_code' => $request->variation_code
                ]);
            });
            return back()->with('success', 'TV Subscription Successful!');
        } catch (\Exception $e) { return back()->with('error', 'Cable service timed out.'); }
    }
}

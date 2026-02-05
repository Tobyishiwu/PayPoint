<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FundWalletController extends Controller
{
    /**
     * Show the dedicated funding page.
     * This follows the "Chest" section of our Head-to-Toe flow.
     */
    public function show()
    {
        $user = Auth::user();
        $account = $user->account;

        // Structured data for the view
        $bankDetails = [
            'bank_name'      => 'Simple Bank PLC',
            'account_name'   => strtoupper($user->name),
            'account_number' => $account->account_number ?? 'SB-00000002',
            'support_email'  => 'support@simplebank.com',
            // Generate a unique session reference for the transfer
            'pay_reference'  => 'SB-REF-' . strtoupper(bin2hex(random_bytes(4)))
        ];

        return view('fund.show', compact('bankDetails'));
    }

    /**
     * Optional: Handle "I have made payment" notifications
     */
    public function notify(Request $request)
    {
        // This is where you would logic for manual confirmation or API triggers
        return redirect()->route('dashboard')->with('success', 'Our team is verifying your deposit.');
    }
}

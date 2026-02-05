<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard with account balance and branding.
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure we have the account relationship loaded
        $account = $user->account;

        // Virtual bank details strictly following the SB-00000002 logic
        $bankDetails = [
            'bank_name'      => 'Simple Bank PLC', // Updated from Wema to match our brand
            'account_name'   => $user->name,
            'account_number' => $account->account_number ?? 'SB-00000002'
        ];

        return view('dashboard', [
            'account'     => $account,
            'bankDetails' => $bankDetails,
            'user'        => $user
        ]);
    }

    /**
     * Fetch and display the full transaction history.
     */
    public function history()
    {
        $user = Auth::user();

        // Fetching the last 50 transactions to keep the 2026 UI snappy
        $transactions = $user->transactions()
            ->latest()
            ->limit(50)
            ->get();

        return view('history', [
            'transactions' => $transactions
        ]);
    }
}

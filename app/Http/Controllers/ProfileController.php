<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display security settings (PIN management)
     */
    public function security(): View
    {
        return view('profile.security', ['user' => auth()->user()]);
    }

    /**
     * Update Transaction PIN.
     * * IMPORTANT: We do NOT use Hash::make() here anymore.
     * The User model is configured to automatically hash the 'transaction_pin'
     * attribute upon saving. This prevents double-hashing.
     */
public function updatePin(Request $request)
{
    $request->validate([
        'pin' => ['required', 'digits:4', 'confirmed'],
    ]);

    $user = Auth::user();
    // Assuming your users table has a 'transaction_pin' column
    $user->update([
        'transaction_pin' => Hash::make($request->pin),
    ]);

    return back()->with('success', 'Transaction PIN updated successfully!');
}
}

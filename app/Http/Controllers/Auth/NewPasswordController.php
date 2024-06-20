<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Events\PasswordResetEvent;
use App\Models\PasswordReset;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Find the password reset token in your custom table
        $passwordReset = PasswordReset::where('token', $request->token)->first();

        // Check if the token exists and is still valid
        if (!$passwordReset || Carbon::now()->timestamp > $passwordReset->expires_in) {
            return back()->withErrors(['token' => __('This password reset token is invalid.')]);
        }

        // Load the associated user
        $user = $passwordReset->user;

        // Update the user's password and remember token
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Set the token status to 'USED' instead of deleting
        $passwordReset->status = 'USED';
        $passwordReset->save();

        // Dispatch the password reset event
        Event::dispatch(new PasswordResetEvent($user));

        // Redirect to login with success message
        return redirect()->route('login')->with('status', __('Password has been reset successfully.'));
    }
}

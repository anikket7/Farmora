<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function showForgot(): View
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->email;
        $otp = (string) rand(10000, 99999);

        // Save OTP to password_reset_tokens table (hashed for security)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        // Send OTP email
        try {
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($email, $otp) {
                $message->to($email)
                    ->subject('Farmora - Password Reset OTP')
                    ->html("
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px;'>
                            <h2 style='color: #cba358; text-align: center;'>Farmora</h2>
                            <p>Hello,</p>
                            <p>We received a request to reset the password for your Farmora account.</p>
                            <p>Your 5-digit One-Time Password (OTP) is:</p>
                            <div style='text-align: center; margin: 30px 0;'>
                                <span style='font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #cba358; background-color: #f3f4f6; padding: 15px 30px; border-radius: 8px; border: 1px solid #e5e7eb; display: inline-block;'>{$otp}</span>
                            </div>
                            <p>This OTP will expire in 15 minutes.</p>
                            <p>If you did not request a password reset, no further action is required.</p>
                            <hr style='border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;' />
                            <p style='font-size: 12px; color: #6b7280; text-align: center;'>This is an automated email. Please do not reply to this message.</p>
                        </div>
                    ");
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage());
        }

        // Log to laravel.log so it's guaranteed to be seen locally/in tests
        \Illuminate\Support\Facades\Log::info("Password reset OTP for {$email} is: {$otp}");

        // Flash message telling user check mail (do NOT flash the code for security)
        session()->flash('success', 'A 5-digit OTP verification code has been sent to your email. Please check your inbox.');

        // Redirect to the reset password view, pre-passing only the email
        return redirect()->route('password.reset', ['email' => $email]);
    }

    public function showReset(Request $request): View
    {
        return view('auth.reset-password', [
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string|size:5',
            'password' => 'required|min:8|confirmed',
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $resetRecord) {
            return back()->withErrors(['email' => 'Invalid email or reset request expired.']);
        }

        // Check if OTP is expired (older than 15 minutes)
        if (now()->subMinutes(15)->gt($resetRecord->created_at)) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'The OTP has expired. Please request a new one.']);
        }

        // Check if OTP matches
        if (! Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['token' => 'The code you entered is invalid.']);
        }

        // Update the user's password
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            // Clear the reset token
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('login')->with('success', 'Your password has been successfully updated!');
        }

        return back()->withErrors(['email' => 'Unable to find a user with this email address.']);
    }
}

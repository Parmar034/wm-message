<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot_pass');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(100000, 999999);

        DB::table('password_otps')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
                'updated_at' => now(),
                'created_at' => now()
            ]
        );

        Mail::send('emails.password-otp', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email)->subject('Password Reset OTP');
        });

        session(['reset_email' => $request->email]);

        return redirect()->route('password.otp')->with('success', 'OTP sent to your email');
    }

    public function showOtpForm()
    {
        return view('auth.verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $record = DB::table('password_otps')
            ->where('email', session('reset_email'))
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        return redirect()->route('password.reset');
    }

    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        User::where('email', session('reset_email'))
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_otps')
            ->where('email', session('reset_email'))
            ->delete();

        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password reset successfully');
    }
}

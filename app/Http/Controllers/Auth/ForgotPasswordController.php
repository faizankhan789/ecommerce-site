<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use DB;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Simple implementation - just redirect with message
        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            // Generate a simple token
            $token = Str::random(60);
            
            // Store in password_resets table
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);
            
            // In production, you would send an email here
            // For now, we'll just show a success message
            return back()->with('status', 'If that email exists, we sent a password reset link!');
        }
        
        return back()->with('status', 'If that email exists, we sent a password reset link!');
    }
}
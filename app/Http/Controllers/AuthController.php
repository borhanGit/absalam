<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $company = CompanySetting::first() ?? new CompanySetting([
            'company_name' => 'Dhaka Thai & Aluminium Glass House',
            'phone' => '01712-345678',
            'address' => 'Mirpur-10, Dhaka, Bangladesh',
        ]);

        return view('auth.login', compact('company'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'আপনার অ্যাকাউন্টটি বর্তমানে নিষ্ক্রিয় (Deactivated)। অ্যাডমিনের সাথে যোগাযোগ করুন।',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))->with('success', "স্বাগতম, {$user->name}! আপনি সফলভাবে লগইন করেছেন।");
        }

        return back()->withErrors([
            'email' => 'প্রদত্ত ইমেইল বা পাসওয়ার্ড সঠিক নয়।',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'আপনি সফলভাবে লগআউট হয়েছেন।');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;

        if (!empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'আপনার প্রোফাইল ও নিরাপত্তা তথ্য সফলভাবে আপডেট করা হয়েছে!');
    }
}

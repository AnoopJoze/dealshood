<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function show()
    {
        if (Auth::check()) return redirect()->route('home');
        return view('frontend.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()          // at least one letter
                    ->mixedCase()        // upper + lower
                    ->numbers()          // at least one digit
                    ->symbols()          // at least one special char
                    ->uncompromised(),   // not in known breach lists
            ],
        ], [
            'email.unique'       => 'This email is already registered. Please sign in.',
            'password.confirmed' => 'The passwords you entered do not match.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'status'   => 'Active',
        ]);

        $user->assignRole('user');

        Auth::login($user);
        // If using a custom RegisterController, add this after creating the user:
        $user->sendEmailVerificationNotification();
        
        // Then redirect to the verification notice instead of home:
        return redirect()->route('verification.notice')
            ->with('success', 'Account created! Please verify your email.');

        return redirect()->route('home')
               ->with('success', 'Welcome to DealsHood, ' . $user->name . '!');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = request()->boolean('remember');

        if (Auth::attempt($attributes, $remember))
        {
            session()->regenerate();

            \App\Models\PostLike::where('session_id', session()->getId())
                ->whereNull('user_id')
                ->update(['user_id' => auth()->id()]);

            return redirect('admin/dashboard')->with(['success' => 'You are logged in.']);
        }

        return back()->withErrors(['email' => 'Email or password invalid.']);
    }

    public function destroy()
    {
        Auth::logout();

        return redirect('/login')->with(['success' => 'You\'ve been logged out.']);
    }
}

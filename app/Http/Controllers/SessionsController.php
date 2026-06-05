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
            'email'=>'required|email',
            'password'=>'required' 
        ]);

        if(Auth::attempt($attributes))
        {
            session()->regenerate();
            \App\Models\PostLike::where('session_id', session()->getId())
                ->whereNull('user_id')
                ->update(['user_id' => auth()->id()]);
            // if (!auth()->user()->hasVerifiedEmail()) {
            //     // Keep them logged in but send to verification notice
            //     return redirect()->route('verification.notice')
            //         ->with('info', 'Please verify your email before continuing.');
            // }

            // Otherwise proceed to normal redirect
            return redirect('admin/dashboard')->with(['success'=>'You are logged in.']);
        }
        else{

            return back()->withErrors(['email'=>'Email or password invalid.']);
        }
    }
    
    public function destroy()
    {

        Auth::logout();

        return redirect('/login')->with(['success'=>'You\'ve been logged out.']);
    }
}

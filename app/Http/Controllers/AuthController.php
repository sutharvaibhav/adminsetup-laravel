<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function admin(){
        return view('auth.form');
    }
    public function loginForm(){
        return view('auth.form');
    }
    public function signupForm(){
        return view('auth.form');
    }
    public function login(Request $request){
        $request->validate([
            'loginEmail' => 'required|string|email|max:255',
            'loginPass' => 'required|string|min:8'
        ]);
        if(Auth::attempt(['email'=>$request->loginEmail, 'password'=>$request->loginPass])){
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Logged in successfully.');
        }
        return back()->withErrors([
            'loginEmail' => 'The provided credentials do not match our records.',
        ])->onlyInput('loginEmail');
    }
    public function signup(Request $request){
        $request->validate([
            'signupName' => 'required|string|max:255',
            'signupEmail' => 'required|string|email|max:255',
            'signupPass' => 'required|string|min:8',
        ]); 
        $user = User::create([
            'name' => $request->signupName,
            'email' => $request->signupEmail,
            'password' => Hash::make($request->signupPass)
        ]);
        Auth::login($user);
        return redirect()->route('dashboard');
    }
    public function dashboard(){
        return view('backend.dashboard');
    }
}

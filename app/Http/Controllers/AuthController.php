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
            'signupEmail' => 'required|string|email|max:255|unique:users,email',
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
    public function changepassword(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|min:8'
        ]);

        $user=Auth::user();

        if(!Hash::check($request->current_password, $user->password)){
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        if ($request->new_password !== $request->confirm_password) {
            return back()->withErrors(['confirm_password' => 'The new password and confirmation do not match.']);
        }
        
        $user->update([
            'password'=>Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
    
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        // $request->session()->regenrateToken();
        return redirect()->route('admin');
    }
}

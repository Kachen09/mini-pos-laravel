<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',    
            'email' => 'required|string|email|max:255|unique:users',    
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'สมัครสมาชิกเรียบร้อยแล้ว! กรุณาเข้าสู่ระบบ');
    }

    public function checkLogin(Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return redirect()->route('products.index')
            ->with('success', 'เข้าสู่ระบบสำเร็จ');
    }

    return back()->withErrors(['email' => 'Email หรือ Password ไม่ถูกต้อง']);
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect()->route('login')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }
}

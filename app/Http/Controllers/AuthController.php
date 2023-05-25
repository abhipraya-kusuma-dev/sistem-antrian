<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  public function login()
  {
    return view('auth.login');
  }

  public function authenticate(Request $request)
  {
    $data = $request->validate([
      'username' => ['required', 'exists:users'],
      'password' => ['required']
    ]);

    if (Auth::attempt($data)) {
      $request->session()->regenerate();

      return redirect()->intended('/antrian/admin');
    }

    return back()->with('login-error', 'Gagal Login bang');
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class LoginController extends Controller
{
    public function login()
    {
        // Check if the user is already authenticated
        if (Auth::check()) {
            // Redirect based on user role
            return $this->redirectUserBasedOnRole();
        } else {
            return view('login');
        }
    }

    public function actionlogin(Request $request)
    {
        // Validate login credentials from request
        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            return $this->redirectUserBasedOnRole();
        } else {
            // Flash an error message to the session if login fails
            return redirect()->route('login')->with('error', 'Username atau Password Salah');
            // return redirect()->back()->with('error', 'File tidak ditemukan.');
        }
    }

    // Redirect users based on their role
    private function redirectUserBasedOnRole()
    {
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.reports')->with('success', 'Selamat Datang!'); // Redirect admin to "Laporan Keluar"
        } elseif (Auth::user()->hasRole('user')) {
            return redirect()->route('user.reports')->with('success', 'Selamat Datang!'); // Redirect user to "Laporan Masuk"
        }
        // Optionally, redirect to a default route if role is undefined
        return redirect()->route('login')->with('error', 'Username atau Password Salah');
    }

    public function actionlogout()
    {
        Auth::logout();
        return redirect('/');
    }
}

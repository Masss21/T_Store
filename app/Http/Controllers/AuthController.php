<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show Register Form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang di T-Store 🎉');
    }

    // Show Login Form
    public function showLogin(Request $request)
    {
        // Store intended URL if exists (from guest redirect)
        if ($request->has('redirect')) {
            session(['url.intended' => $request->redirect]);
        }
        
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if admin
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang kembali, Admin! 👋');
            }

            // Regular user - redirect to intended URL or home
            $intendedUrl = session('url.intended', route('home'));
            session()->forget('url.intended'); // Clear the intended URL
            
            return redirect($intendedUrl)
                ->with('success', 'Selamat datang kembali, ' . $user->name . '! 👋');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->only('email'));
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout. Sampai jumpa! 👋');
    }
}
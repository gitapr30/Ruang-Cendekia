<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip_nisn' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Update kolom last_login_at
            $user = Auth::user(); // Mendapatkan pengguna yang sedang login
            $user->last_login_at = now(); // Menyimpan waktu login saat ini ke kolom last_login_at
            return redirect()->intended('/books'); // Redirect ke halaman tujuan
        }

        return back()->with('errorMessage', 'Login failed!');
    }

    public function register(Request $request)
{
    $defaultImages = ['image_post/profdef1.jpg', 'image_post/profdef2.jpg'];

    $credentials = $request->validate([
        'nip_nisn' => 'required',
        'name' => 'required',
        'username' => 'required|unique:users',
        'email' => 'required|email|unique:users', // Email harus unik
        'password' => 'required|min:6',
    ]);

    $credentials['password'] = Hash::make($credentials['password']);
    $credentials['role'] = 'pengguna';
    $credentials['image'] = $defaultImages[array_rand($defaultImages)];

    $user = User::create($credentials);

    if ($user) {
        Auth::login($user);
        return redirect()->route('books.index');
    }

    return back()->withErrors(['register' => 'Registration failed']);
}

    public function index()
{
    $users = User::all(); // Get all users
    return view('user.index', compact('users')); // Pass users to the view
}

public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/'); // Langsung ke homepage tanpa route() jika masih error
}

}

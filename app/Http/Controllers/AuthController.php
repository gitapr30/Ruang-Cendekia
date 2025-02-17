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
            'nip_nisn' => ['required', 'regex:/^[0-9]+$/'],
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

        return back()->with('errorMessage', 'Gagal Masuk!');
    }

    public function register(Request $request)
{
    $defaultImages = ['storage/profil/no-pict.jpg', 'storage/profil/no-pict.jpg'];

    $credentials = $request->validate([
        'nip_nisn' => 'required',
        'name' => 'required',
        'username' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);

    $credentials['password'] = Hash::make($credentials['password']);
    $credentials['role'] = 'pengguna';
    $credentials['image'] = $defaultImages[array_rand($defaultImages)];

    $user = User::create($credentials);

    if ($user) {
        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'user' => [
                'id' => $user->id,
                'nip_nisn' => $user->nip_nisn,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'image' => asset('storage/' . $user->image)
            ]
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Registrasi gagal'], 500);
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

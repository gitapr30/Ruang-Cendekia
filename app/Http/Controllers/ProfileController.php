<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PasswordChangedNotification;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil pengguna yang sedang login.
     */
    public function index()
    {
        return view('profile.index', ['user' => Auth::user()]);
    }

    /**
     * Perbarui profil pengguna langsung dari halaman profil.
     */
    public function update(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'no_telp' => 'nullable|string|max:15',
        'password' => 'nullable|string|min:8|confirmed',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Update image jika ada
    if ($request->hasFile('image')) {
        if ($user->image && $user->image !== 'default-profile.jpg') {
            Storage::disk('public')->delete('profil/' . basename($user->image));
        }

        $originalName = $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->storeAs('/profil', $originalName, 'public');
        $user->image = 'storage/profil/' . $originalName;
    }

    $passwordChanged = false;
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
        $passwordChanged = true;
    }
    // Update data pengguna
    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;
    $user->no_telp = $request->no_telp;

    // Update password jika diisi
    if ($passwordChanged) {
        $user->notify(new PasswordChangedNotification());
    }

    $user->save();

    return redirect()->route('profile.index')->with('successMessage', 'Profil berhasil diperbarui.');
}

    /**
     * Hapus akun pengguna.
     */
    public function destroy()
    {
        $user = Auth::user();
        
        // Hapus gambar jika ada dan bukan default
        if ($user->image && $user->image !== 'default-profile.jpg') {
            Storage::disk('public')->delete('profil/' . basename($user->image));
        }

        $user->delete();

        return response()->json(['message' => 'Akun berhasil dihapus']);
    }
}

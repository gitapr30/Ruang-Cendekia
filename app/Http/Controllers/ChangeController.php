<?php

namespace App\Http\Controllers;

use App\Models\Change;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChangeController extends Controller
{
    // Menampilkan daftar semua perubahan
    public function index()
    {
        return view('changes.index', [
            'title' => 'Change List', // Judul halaman
            'changes' => Change::all(), // Mengambil semua data perubahan dari model
        ]);
    }

    // Menampilkan form untuk membuat perubahan baru
    public function create()
    {
        return view('changes.create', [
            'title' => 'Create Change' // Judul halaman
        ]);
    }

    // Menyimpan data perubahan baru ke database
    public function store(Request $request)
    {
        // Validasi data input dari form
        $validateData = $request->validate([
            'user_id' => 'required|exists:users,id', // Harus ada dan valid user ID
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Harus gambar dengan format tertentu
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Harus gambar dengan format tertentu
            'nama_website' => 'required|string|max:255', // Harus diisi, maksimal 255 karakter
            'alamat' => 'required|string|max:255', // Harus diisi, maksimal 255 karakter
            'no_telp' => 'required|string|max:15', // Harus diisi, maksimal 15 karakter
            'email' => 'required|email|max:255', // Harus format email valid
            'maps' => 'required|string', // Harus diisi
            'tittle' => 'required|string|max:255', // Harus diisi, maksimal 255 karakter
            'description' => 'required|string', // Harus diisi
            'content' => 'required|string', // Harus diisi
            'footer' => 'required|string', // Harus diisi
        ]);

        // Menyimpan file logo dan gambar ke storage
        $validateData['logo'] = $request->file('logo')->store('logos');
        $validateData['image'] = $request->file('image')->store('images');

        // Membuat record baru di database
        Change::create($validateData);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'New Change has been added!');
    }

    // Menampilkan detail perubahan tertentu berdasarkan ID
    public function show($id)
    {
        // Mencari perubahan berdasarkan ID atau gagal jika tidak ditemukan
        $change = Change::findOrFail($id);
        return view('changes.show', compact('change')); // Menampilkan view dengan data perubahan
    }

    // Menampilkan form untuk mengedit perubahan tertentu
    public function edit($id)
    {
        // Mencari perubahan berdasarkan ID atau gagal jika tidak ditemukan
        $change = Change::findOrFail($id);
        return view('changes.update', compact('change')); // Menampilkan view edit dengan data perubahan
    }

    // Memperbarui data perubahan tertentu di database
    public function update(Request $request, $id)
    {
        // Mencari perubahan berdasarkan ID atau gagal jika tidak ditemukan
        $change = Change::findOrFail($id);

        // Validasi data input dari form
        $validateData = $request->validate([
            'user_id' => 'required|exists:users,id', // Harus ada dan valid user ID
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Opsional, tapi jika ada harus gambar valid
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Opsional, tapi jika ada harus gambar valid
            'nama_website' => 'required|string|max:255', // Harus diisi, maksimal 255 karakter
            'alamat' => 'required|string|max:255', // Harus diisi, maksimal 255 karakter
            'no_telp' => 'required|string|max:15', // Harus diisi, maksimal 15 karakter
            'email' => 'required|email|max:255', // Harus format email valid
            'maps' => 'required|string', // Harus diisi
            'tittle' => 'required|string|max:255', // Harus diisi, maksimal 255 karakter
            'description' => 'required|string', // Harus diisi
            'content' => 'required|string', // Harus diisi
            'footer' => 'required|string', // Harus diisi
            'denda' => 'required|numeric', // Harus angka
            'max_peminjaman' => 'required|integer', // Harus bilangan bulat
            'waktu_operasional' => 'required|string', // Harus diisi
        ]);

        // Jika ada file logo baru diupload
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($change->logo) {
                Storage::delete($change->logo);
            }
            // Simpan logo baru
            $validateData['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Jika ada gambar baru diupload
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($change->image) {
                Storage::delete($change->image);
            }
            // Simpan gambar baru
            $validateData['image'] = $request->file('image')->store('images', 'public');
        }

        // Memperbarui data perubahan di database
        $change->update($validateData);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Change has been updated!');
    }

    // Menghapus perubahan tertentu dari database
    public function destroy(Change $change)
    {
        // Hapus file logo jika ada
        if ($change->logo) {
            Storage::delete($change->logo);
        }

        // Hapus file gambar jika ada
        if ($change->image) {
            Storage::delete($change->image);
        }

        // Hapus record perubahan dari database
        $change->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Change has been deleted!');
    }
}

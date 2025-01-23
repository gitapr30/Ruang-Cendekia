@extends('layouts.app')

@section('content')
<div class="p-4 h-full">
    <div class="w-full bg-white rounded-lg p-6 shadow-lg">
        <h1 class="text-2xl font-semibold text-slate-800">Form Pengajuan Peminjaman</h1>
        <p class="text-slate-600 text-sm mt-2">Lengkapi form berikut untuk mengajukan peminjaman buku.</p>
        <form action="{{ route('peminjaman.store') }}" method="post" class="mt-6 space-y-4">
            @csrf
            <!-- Input ID Buku -->
            <div>
                <label for="id_buku" class="block text-sm font-medium text-slate-700">ID Buku</label>
                <input type="text" id="id_buku" name="id_buku" 
                       class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                       placeholder="Masukkan ID Buku" required>
            </div>
            
            <!-- Input Code Peminjaman -->
            <div>
                <label for="code_peminjaman" class="block text-sm font-medium text-slate-700">Kode Peminjaman</label>
                <input type="text" id="code_peminjaman" name="code_peminjaman" 
                       class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                       placeholder="Masukkan Kode Peminjaman" required>
            </div>

            <!-- Input Denda -->
            <div>
                <label for="denda" class="block text-sm font-medium text-slate-700">Denda (Jika Terlambat)</label>
                <input type="number" id="denda" name="denda" 
                       class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                       placeholder="Masukkan Denda (dalam rupiah)" required>
            </div>

            <!-- Input Tanggal Pinjam -->
            <div>
                <label for="tanggal_pinjam" class="block text-sm font-medium text-slate-700">Tanggal Pinjam</label>
                <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" 
                       class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                       required>
            </div>

            <!-- Input Tanggal Kembali -->
            <div>
                <label for="tanggal_kembali" class="block text-sm font-medium text-slate-700">Tanggal Kembali</label>
                <input type="date" id="tanggal_kembali" name="tanggal_kembali" 
                       class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                       required>
            </div>

            <!-- Dropdown Jaminan -->
            <div>
                <label for="jaminan" class="block text-sm font-medium text-slate-700">Jaminan</label>
                <select id="jaminan" name="jaminan" 
                        class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        required>
                    <option value="" disabled selected>Pilih Jaminan</option>
                    <option value="KTP">KTP</option>
                    <option value="SIM">SIM</option>
                    <option value="Kartu Pelajar">Kartu Pelajar</option>
                    <option value="Passport">Passport</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full bg-gradient-to-br from-blue-400 to-blue-600 text-white font-medium py-3 rounded-lg shadow-lg hover:shadow-xl hover:bg-blue-700 transition-all duration-300 text-sm">
                    Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

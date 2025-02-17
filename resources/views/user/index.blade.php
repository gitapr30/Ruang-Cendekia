@extends('layouts.main')

@section('contentAdmin')
<div class="container mt-5">
    <h1 class="mb-4 text-center text-lg font-semibold">Daftar Pengguna</h1>

    <!-- Table -->
    <div class="overflow-auto rounded-lg shadow hidden lg:block w-full mt-5 md:mt-0 md:col-span-2">
        <table class="table-auto w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th class="w-6 p-3 text-sm font-semibold tracking-wide text-left">#</th>
                    <th class="w-44 p-3 text-sm font-semibold tracking-wide text-left">NIP/NISN</th>
                    <th class="w-44 p-3 text-sm font-semibold tracking-wide text-left">Nama</th>
                    <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Username</th>
                    <th class="w-44 p-3 text-sm font-semibold tracking-wide text-left">Email</th>
                    <th class="w-24 p-3 text-sm font-semibold tracking-wide text-left">Aktor</th>
                    <th class="w-28 p-3 text-sm font-semibold tracking-wide text-left">Terakhir Masuk</th>
                    <th class="w-16 p-3 text-sm font-semibold tracking-wide text-left">Foto Profil</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if ($users->isEmpty())
                <tr>
                    <td colspan="8">
                        <p class="text-sm p-5">Tidak terdapat user</p>
                    </td>
                </tr>
                @endif
                @foreach($users as $index => $user)
                <tr>
                    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $index + 1 }}</td>
                    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $user->nip_nisn }}</td>
                    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $user->name }}</td>
                    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $user->username }}</td>
                    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                        <span class="badge bg-{{ $user->role == 'Admin' ? 'success' : 'info' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                        {{ $user->last_login_at ? $user->last_login_at->format('d-m-Y H:i') : 'Never' }}
                    </td>
                    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" alt="Profile Image" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <span>No Image</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection


@section('contentPustakawan')
<div class="container mt-5">
    <h1 class="mb-4 text-center text-lg font-semibold">User List</h1>

    <!-- Tombol untuk membuka modal -->
    <button class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="openModal()">Register User</button>

    <!-- Modal -->
    <div id="registerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-md w-1/3">
            <h2 class="text-lg font-semibold mb-3">Register User</h2>
            <form id="registerForm">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIP/NISN</label>
                        <input type="text" name="nip_nisn" class="mt-1 p-2 w-full border rounded-md" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" class="mt-1 p-2 w-full border rounded-md" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" class="mt-1 p-2 w-full border rounded-md" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="mt-1 p-2 w-full border rounded-md" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" class="mt-1 p-2 w-full border rounded-md" required>
                    </div>
                    <input type="hidden" name="role" value="pengguna">
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md mr-2" onclick="closeModal()">Cancel</button>
                    <button type="button" id="submitRegister" class="bg-blue-500 text-white px-4 py-2 rounded-md">Register</button>

                </div>
            </form>
            
        </div>
    </div>

    <!-- Table User List -->
    <div class="overflow-auto rounded-lg shadow hidden lg:block w-full mt-5">
        <table class="table-auto w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">#</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">NIP/NISN</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Name</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Username</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Email</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Role</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Last Login</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Profile Image</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if ($users->isEmpty())
                <tr>
                    <td colspan="8" class="p-5 text-sm text-center">Tidak terdapat user</td>
                </tr>
                @endif
                @foreach($users as $index => $user)
                <tr>
                    <td class="p-3 text-sm text-gray-700">{{ $index + 1 }}</td>
                    <td class="p-3 text-sm text-gray-700">{{ $user->nip_nisn }}</td>
                    <td class="p-3 text-sm text-gray-700">{{ $user->name }}</td>
                    <td class="p-3 text-sm text-gray-700">{{ $user->username }}</td>
                    <td class="p-3 text-sm text-gray-700">{{ $user->email }}</td>
                    <td class="p-3 text-sm text-gray-700">{{ $user->role }}</td>
                    <td class="p-3 text-sm text-gray-700">{{ $user->last_login_at ? $user->last_login_at->format('d-m-Y H:i') : 'Never' }}</td>
                    <td class="p-3 text-sm text-gray-700">
                        @if($user->image)
                        <img src="{{ asset($user->image) }}" alt="Profile Image" class="rounded-full w-12 h-12 object-cover">
                        @else
                        <span>No Image</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Script untuk modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $("#submitRegister").click(function () {
            let formData = new FormData($("#registerForm")[0]);

            $.ajax({
                url: "{{ route('register') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                success: function (response) {
                    if (response.success) {
                        alert("Registrasi berhasil!");

                        // Tambahkan user ke tabel tanpa reload
                        let tableBody = $("tbody");
                        let newRow = `
                            <tr>
                                <td class="p-3 text-sm text-gray-700">${tableBody.children().length + 1}</td>
                                <td class="p-3 text-sm text-gray-700">${response.user.nip_nisn}</td>
                                <td class="p-3 text-sm text-gray-700">${response.user.name}</td>
                                <td class="p-3 text-sm text-gray-700">${response.user.username}</td>
                                <td class="p-3 text-sm text-gray-700">${response.user.email}</td>
                                <td class="p-3 text-sm text-gray-700">${response.user.role}</td>
                                <td class="p-3 text-sm text-gray-700">${new Date().toLocaleString()}</td>
                                <td class="p-3 text-sm text-gray-700">
                                    <img src="${response.user.image}" alt="Profile Image" class="rounded-full w-12 h-12 object-cover">
                                </td>
                            </tr>
                        `;
                        tableBody.append(newRow);

                        // Reset form & tutup modal
                        $("#registerForm")[0].reset();
                        closeModal();
                    } else {
                        alert("Registrasi gagal: " + response.message);
                    }
                },
                error: function (xhr) {
                    alert("Terjadi kesalahan: " + xhr.responseJSON.message);
                }
            });
        });
    });

    function openModal() {
        $("#registerModal").removeClass("hidden");
    }

    function closeModal() {
        $("#registerModal").addClass("hidden");
    }
</script>

@endsection
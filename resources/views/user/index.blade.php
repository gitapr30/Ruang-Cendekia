@extends('layouts.main')

@section('contentAdmin')
<div class="container mt-5">
    <h1 class="mb-4 text-center text-lg font-semibold">User List</h1>

    <!-- Table -->
    <div class="overflow-auto rounded-lg shadow hidden lg:block w-full mt-5 md:mt-0 md:col-span-2">
        <table class="table-auto w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th class="w-6 p-3 text-sm font-semibold tracking-wide text-left">#</th>
                    <th class="w-44 p-3 text-sm font-semibold tracking-wide text-left">NIP/NISN</th>
                    <th class="w-44 p-3 text-sm font-semibold tracking-wide text-left">Name</th>
                    <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Username</th>
                    <th class="w-44 p-3 text-sm font-semibold tracking-wide text-left">Email</th>
                    <th class="w-24 p-3 text-sm font-semibold tracking-wide text-left">Role</th>
                    <th class="w-28 p-3 text-sm font-semibold tracking-wide text-left">Last Login</th>
                    <th class="w-16 p-3 text-sm font-semibold tracking-wide text-left">Profile Image</th>
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

    <!-- Form untuk menambahkan user baru -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-5">
        <h2 class="text-lg font-semibold mb-3">Register User</h2>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                <div>
                    <input type="hidden" name="role" value="visitor">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Register</button>
            </div>
        </form>
    </div>
    <!-- Table User List -->
    <div class="overflow-auto rounded-lg shadow hidden lg:block w-full mt-5 md:mt-0 md:col-span-2">
        <table class="table-auto w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th class="w-6 p-3 text-sm font-semibold tracking-wide text-left">#</th>
                    <th class="w-44 p-3 text-sm font-semibold tracking-wide text-left">NIP/NISN</th>
                    <th class="w-44 p-3 text-sm font-semibold tracking-wide text-left">Name</th>
                    <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Username</th>
                    <th class="w-44 p-3 text-sm font-semibold tracking-wide text-left">Email</th>
                    <th class="w-24 p-3 text-sm font-semibold tracking-wide text-left">Role</th>
                    <th class="w-28 p-3 text-sm font-semibold tracking-wide text-left">Last Login</th>
                    <th class="w-16 p-3 text-sm font-semibold tracking-wide text-left">Profile Image</th>
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

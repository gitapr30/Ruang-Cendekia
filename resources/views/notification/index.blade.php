@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
            <button id="markAllAsRead" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                Tandai semua sudah dibaca
            </button>
        </div>

        <div id="notificationsContainer">
            @if(count($notifications) > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        <li class="py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 pt-1">
                                    @if($notification['type'] === 'overdue')
                                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($notification['type'] === 'due-today')
                                        <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @else
                                        <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $notification['message'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($notification['type'] === 'overdue')
                                            Terlambat {{ $notification['days_overdue'] }} hari
                                        @elseif($notification['type'] === 'due-today')
                                            Jatuh tempo hari ini
                                        @else
                                            Jatuh tempo besok
                                        @endif
                                        • {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada notifikasi</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda tidak memiliki notifikasi baru.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark all as read
    document.getElementById('markAllAsRead').addEventListener('click', function() {
        fetch('{{ route("notification.markAsRead") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the page or update UI
                location.reload();
            }
        });
    });

    // Poll for new notifications every 60 seconds
    setInterval(function() {
        fetch('{{ route("notification.get") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.notifications.length > 0) {
                    // Update notification count in the navbar
                    document.getElementById('notifCount').textContent = data.notifications.length;
                    document.getElementById('notifCount').classList.remove('hidden');
                } else {
                    document.getElementById('notifCount').classList.add('hidden');
                }
            });
    }, 60000);
});
</script>
@endsection
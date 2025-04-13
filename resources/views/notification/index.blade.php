@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div class="flex items-center gap-2">
        <button onclick="window.history.back()" class="text-gray-600 hover:text-gray-800">
            <!-- Icon panah kembali -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
    </div>
    <button id="markAllAsRead" class="text-blue-500 hover:text-blue-700 text-sm font-medium whitespace-nowrap">
        Tandai semua sudah dibaca
    </button>
</div>


        {{-- Container for notifications list --}}
        <div id="notificationsContainer">
            {{-- Check if there are notifications to display --}}
            @if(count($notifications) > 0)
                {{-- List of notifications --}}
                <ul class="divide-y divide-gray-200">
                    {{-- Loop through each notification --}}
                    @foreach($notifications as $notification)
                        <li class="py-4">
                            <div class="flex items-start">
                                {{-- Notification icon based on type --}}
                                <div class="flex-shrink-0 pt-1">
                                    @if($notification['type'] === 'overdue')
                                        {{-- Red exclamation icon for overdue notifications --}}
                                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($notification['type'] === 'due-today')
                                        {{-- Orange warning icon for due today notifications --}}
                                        <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @else
                                        {{-- Yellow warning icon for other notifications --}}
                                        <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @endif
                                </div>
                                {{-- Notification content --}}
                                <div class="ml-3">
                                    {{-- Notification message --}}
                                    <p class="text-sm font-medium text-gray-900">{{ $notification['message'] }}</p>
                                    {{-- Notification metadata (days overdue/time created) --}}
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
                {{-- Empty state when there are no notifications --}}
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
// Execute when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Event listener for mark all as read button
    document.getElementById('markAllAsRead').addEventListener('click', function() {
        // Send AJAX request to mark all notifications as read
        fetch('{{ route("notification.markAsRead") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Reload page if successful
            if (data.success) {
                location.reload();
            }
        });
    });

    // Poll server for new notifications every 60 seconds
    setInterval(function() {
        fetch('{{ route("notification.get") }}')
            .then(response => response.json())
            .then(data => {
                // Update notification count in navbar if new notifications exist
                if (data.success && data.notifications.length > 0) {
                    document.getElementById('notifCount').textContent = data.notifications.length;
                    document.getElementById('notifCount').classList.remove('hidden');
                } else {
                    document.getElementById('notifCount').classList.add('hidden');
                }
            });
    }, 60000); // 60 second interval
});
</script>
@endsection

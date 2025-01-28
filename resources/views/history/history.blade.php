@extends('layouts.main')

@section('content')
<div class="container">
    <h1>History List</h1>

    <!-- Display success message if any -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- Check if there are any histories -->
    @if ($histories->isEmpty())
        <div class="alert alert-warning">
            Belum ada data history.
        </div>
    @else
        <!-- Loop through each history record -->
        <div class="row">
            @foreach ($histories as $history)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <strong>History ID:</strong> {{ $history->id }}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">User: {{ $history->user->name }}</h5>
                            <p class="card-text"><strong>Book Title:</strong> {{ $history->book->title }}</p>
                            <p><strong>Created At:</strong> {{ $history->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

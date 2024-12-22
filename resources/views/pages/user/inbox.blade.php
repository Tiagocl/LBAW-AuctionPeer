@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Notifications Inbox</h1>
        <div id="notifications-list">
            @foreach ($notifications as $notification)
                <div class="notification-item {{ $notification->hidden ? 'hidden' : '' }}">
                    <div class="notification-content">
                        <p>{{ $notification->content }}</p>
                        <small>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                    </div>
                    <div class="notification-actions">
                        @if (!$notification->hidden)
                            <form action="{{ route('notifications.markAsRead') }}" method="POST" onsubmit="event.preventDefault(); markAsRead('{{ $notification->id }}');" class="mark-as-read-form">
                                @csrf
                                <button type="submit">Mark as read</button>
                            </form>
                        @endif
                        @if ($notification->link)
                        <button onclick="window.location.href='{{ $notification->link }}'" type="submit">Check it out</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination links --}}
        {{-- {{ $notifications->links() }} --}}
    </div>

    <script>
        function markAsRead(id) {
            console.log('Full UUID:', id); // Log the full UUID to the console
            fetch('{{ route('notifications.markAsRead') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
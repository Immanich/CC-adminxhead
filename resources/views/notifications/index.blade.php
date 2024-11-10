@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold mb-4">Notifications</h2>
    <div class="space-y-4">
        @foreach($notifications as $notification)
            <a href="{{ route('notifications.read', $notification->id) }}" class="block bg-blue-100 p-4 rounded-lg shadow-md hover:bg-blue-200 transition-all duration-300">
                <h3 class="font-semibold">{{ $notification->title }}</h3>
                <p>{{ $notification->description }}</p>
                <small>{{ $notification->created_at->diffForHumans() }}</small>
            </a>
        @endforeach
    </div>
@endsection

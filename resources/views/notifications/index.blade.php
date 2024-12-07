@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-bold mb-4 ml-3">Notifications</h2>
    <div class="space-y-4">
        @foreach($notifications as $notification)
            <a href="{{ route('notifications.read', $notification->id) }}"
                class="block p-4 rounded-lg shadow-md transition-all duration-300
                    @if($notification->is_read)
                        bg-white hover:bg-gray-100
                    @else
                        bg-blue-100 hover:bg-blue-200
                    @endif">
                <h3 class="font-semibold">{{ $notification->title }}</h3>
                <p>{!! $notification->description !!}</p>
                <small>{{ $notification->created_at->diffForHumans() }}</small>
            </a>
        @endforeach
    </div>
@endsection

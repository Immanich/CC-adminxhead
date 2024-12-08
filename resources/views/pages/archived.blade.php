@extends('layouts.admin')

@section('content')
    <div class="">
        <h1 class="text-2xl font-bold text-gray-800 ml-2">Archived Events</h1>
        <div class="mt-6">
            @if($archivedEvents->isEmpty())
                <p class="text-gray-500">No archived events found.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($archivedEvents as $event)
                        <div class="relative rounded-lg shadow-md overflow-hidden">
                            @if ($event->image)
                            <img src="{{ asset($event->image) }}" alt="{{ $event->title }}">
                        @else
                            <p>No image available</p>
                        @endif                           <div class="absolute inset-0 flex flex-col justify-end p-4 bg-gradient-to-t from-black to-transparent transition-opacity duration-300 ease-in-out group-hover:bg-black group-hover:bg-opacity-50">
                                <h2 class="text-xl font-semibold text-white opacity-100">{{ $event->title }}</h2>
                                <h3 class="text-sm font-medium text-gray-300">{{ \Carbon\Carbon::parse($event->date_time)->format('M d, Y') }}</h3>
                                <p class="text-sm text-gray-200 opacity-0 transform translate-y-4 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0">
                                    {{ Str::limit($event->description, 50) }}
                                </p>

                                <a href="{{ route('events.showExpiredEvent', $event->id) }}" class="px-3 py-2 text-xs font-medium text-center  items-center text-white bg-blue-500 rounded-lg hover:bg-blue-800 focus:ring-4"> <i class="fas fa-eye mr-1"></i>View Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('content')
    <!-- Title and Back Button -->
    <div class="flex items-center justify-center mt-8 mb-6">
        <a href="{{ route('events.page') }}" class="text-gray-500 hover:text-gray-700 transition duration-300 mr-4">
            <i class="fas fa-arrow-left text-2xl"></i> <!-- Left Arrow Icon -->
        </a>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            {{ $event->title }}
        </h1>
    </div>

    <!-- Event Image with Centered Alignment and Shadow -->
    <div class="flex justify-center mb-6">
        <img src="{{ $event->image }}" alt="Event Image" class="w-full max-w-3xl h-auto rounded-lg shadow-lg transition duration-300 hover:shadow-2xl">
    </div>

    <!-- Description -->
    <div class="text-lg text-gray-700 leading-relaxed tracking-wide mx-auto max-w-3xl mb-10 px-4">
        {{ $event->description }}
    </div>
@endsection

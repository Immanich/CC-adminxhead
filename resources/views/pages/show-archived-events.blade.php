@extends('layouts.admin')

@section('content')
    <div class="flex flex-col items-center mt-8 mb-6">
        <a href="{{ route('events.archived') }}" class="self-start ml-5 rounded-full bg-gray-600 py-2 px-4 text-white hover:bg-slate-700">
            <i class="bi bi-arrow-left"> Back</i>
        </a>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $event->title }}</h1>
        <p class="text-lg font-semibold text-gray-600">When: {{ \Carbon\Carbon::parse($event->date_time)->format('M d, Y - h:i A') }}</p>
        <p class="text-lg font-semibold text-gray-600">Expires at: {{ \Carbon\Carbon::parse($event->expires_at)->format('M d, Y - h:i A') }}</p>
        <p class="text-lg font-semibold text-red-600 mt-1">Status: Expired</p>
    </div>

    <div class="flex justify-center mb-6">
        <img src="{{ $event->image }}" alt="Event Image" class="w-full max-w-3xl rounded-lg shadow-lg">
    </div>

    <div class="text-lg text-gray-700 mx-auto max-w-3xl mb-10 px-4">
        {!! nl2br(e($event->description)) !!}
    </div>
@endsection

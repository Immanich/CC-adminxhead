@extends('layouts.admin')

@section('content')
    <!-- Title and Back Button -->
    <div class="flex flex-col items-center mt-8 mb-6">
        <!-- Back Button -->
        <a href="{{ route('events.page') }}" class="self-start ml-5 rounded-full bg-gray-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-sm hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
            <i class="bi bi-arrow-left"> Back</i>
        </a>

        <!-- Event Title -->
        <h1 class="text-4xl font-bold text-gray-800 mb-2 text-center">
            {{ $event->title }}
        </h1>

        <!-- Event DateTime -->
        <p class="text-lg font-semibold text-gray-600 mt-1 text-center">
            {{ \Carbon\Carbon::parse($event->date_time)->format('M d, Y - h:i A') }}
        </p>
    </div>

    <!-- Event Image -->
    <div class="flex justify-center mb-6">
        <img src="{{ $event->image }}" alt="Event Image" class="w-full max-w-3xl h-auto rounded-lg shadow-lg transition duration-300 hover:shadow-2xl">
    </div>

    <!-- Event Description -->
    <!-- Event Description -->
<div class="text-lg text-gray-700 leading-relaxed tracking-wide mx-auto max-w-3xl mb-10 px-4">
    {!! nl2br(e($event->description)) !!}
</div>

@endsection

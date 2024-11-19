@extends('layouts.admin')

@section('content')
    <div class="p-6 rounded-lg">
        <!-- Navbar for Pending Requests -->
        <div class="flex justify-center space-x-8 border-b border-gray-300">
            <!-- Navigation for Pending Categories -->
            <a href="{{ route('pending.events') }}"
                class="flex items-center space-x-2 px-6 py-2 rounded-t-lg text-sm font-semibold
                @if(request()->routeIs('pending.events'))  text-black border-b-2 border-blue-600
                @else text-gray-600 hover:text-blue-600 @endif">
                <i class="fas fa-calendar-alt"></i>
                <span>Events</span>
            </a>

            <a href="{{ route('pending.services') }}"
                class="flex items-center space-x-2 px-6 py-2 rounded-t-lg text-sm font-semibold
                @if(request()->routeIs('pending.services'))  text-black border-b-2 border-blue-600
                @else text-gray-600 hover:text-blue-600 @endif">
                <i class="fas fa-concierge-bell"></i>
                <span>Services</span>
            </a>
        </div>

        <!-- Content for Pending Requests -->
        <div class="rounded-lg shadow-md mt-3">
            <!-- Placeholder for the specific content based on the selected tab -->
            @yield('pending-content')
        </div>
    </div>
@endsection

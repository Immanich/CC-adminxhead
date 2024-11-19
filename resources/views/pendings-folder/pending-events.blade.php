@extends('pages.pendings')

@section('pending-content')
    <div class="">
        {{-- Check if there are no pending events --}}
        @if($pendingEvents->isEmpty())
            <div class="text-center bg-gray-100 p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-gray-800">0 pending events.</h3>
                <p class="text-gray-600 mt-2">There are currently no events awaiting approval.</p>
            </div>
        @else
            {{-- Display pending events --}}
            <div class="space-y-8">
                @foreach($pendingEvents as $event)
                    <div class="bg-gray-100 p-6 rounded-lg shadow-lg flex flex-col items-center">
                        <!-- Event Image -->
                        <div class="w-full flex justify-center mb-4">
                            <img src="{{ $event->image }}" alt="Event Image" class="max-w-full h-56 object-cover rounded-lg shadow-md">
                        </div>

                        <!-- Event Title and Description -->
                        <div class="text-center mb-4">
                            <h3 class="text-2xl font-semibold text-gray-800 mb-2">{{ $event->title }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $event->description }}</p>
                        </div>

                        <!-- Approve/Reject Buttons -->
                        <div class="flex justify-center space-x-4 mt-4">
                            <!-- Approve Button -->
                            <form action="{{ route('events.approve', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white font-semibold px-5 py-2 rounded-full hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                                    Approve
                                </button>
                            </form>

                            <!-- Reject Button -->
                            <form action="{{ route('events.reject', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white font-semibold px-5 py-2 rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

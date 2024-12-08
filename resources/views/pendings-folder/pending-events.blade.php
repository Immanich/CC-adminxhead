@extends('pages.pendings')

@section('pending-content')
    <div class="">
        @if(session('success'))
        <div id="successMessage" class="bg-green-100 text-green-700 px-4 py-3 rounded relative mb-4 opacity-100 transition-opacity duration-1000 ease-in-out">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if ($errors->any())
        <div id="errorMessage" class="bg-red-100 text-red-700 p-4 rounded mb-4 opacity-100 transition-opacity duration-1000 ease-in-out">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
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
                        {{-- <div class="w-full flex justify-center mb-4">
                            <img src="{{ $event->image }}" alt="Event Image" class="max-w-full h-56 object-cover rounded-lg shadow-md">
                        </div> --}}

                        <!-- Event Title and Description -->
                        <div class="text-center mb-4">
                            <h3 class="text-2xl font-semibold text-gray-800 mb-2">{{ $event->title }}</h3>
                            {{-- <p class="text-xl font-semibold text-gray-800 mb-2">{{ \Carbon\Carbon::parse($event->date_time)->format('M d, Y') }}</p>
                            <p class="text-gray-600 leading-relaxed">{!! nl2br(e($event->description)) !!}</p> --}}
                        </div>

                        <!-- Approve/Reject Buttons -->
                        <div class="flex justify-center space-x-4 mt-4">

                            <a href="{{ route('pending.events.show', ['id' => $event->id]) }}" class="px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-800">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>


                            <!-- Approve Button -->
                            <form action="{{ route('events.approve', $event->id) }}" method="POST" onsubmit="return confirmApproval()">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-green-500 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    <i class="bi bi-check-circle mr-1"></i>Approve
                                </button>
                            </form>

                            <!-- Reject Button -->
                            <form action="{{ route('events.reject', $event->id) }}" method="POST" onsubmit="return confirmReject()">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-red-500 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                    <i class="bi bi-x-circle mr-1"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <script>

function confirmApproval() {
        return confirm('Are you sure you want to approve this event?');
    }
    function confirmReject() {
        return confirm('Are you sure you want to reject this event?');
    }

        window.onload = function() {
    var successMessage = document.getElementById('successMessage');
    if (successMessage) {
        // Fade out the success message after 2 seconds
        setTimeout(function() {
            successMessage.style.transition = "opacity 1s ease-out";
            successMessage.style.opacity = 0;
            setTimeout(function() {
                successMessage.remove(); // Remove the element from the DOM
            }, 1000); // Allow fade-out animation to complete
        }, 2000); // 2 seconds delay before starting fade-out
    }

    var errorMessage = document.getElementById('errorMessage');
    if (errorMessage) {
        // Fade out the error message after 2 seconds
        setTimeout(function() {
            errorMessage.style.transition = "opacity 1s ease-out";
            errorMessage.style.opacity = 0;
            setTimeout(function() {
                errorMessage.remove(); // Remove the element from the DOM
            }, 1000); // Allow fade-out animation to complete
        }, 2000); // 2 seconds delay before starting fade-out
    }
};
    </script>
@endsection

@extends('pages.pendings')

@section('pending-content')
    <div class="">
        <div class="flex items-center ">
            {{-- <h1 class="text-2xl font-bold text-gray-700">Pending Services</h1> --}}
        </div>

        @if(session('success'))
            <div class="alert {{ strpos(session('success'), 'rejected') !== false ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} px-4 py-3 rounded relative mb-4 opacity-100 transition-opacity duration-1000 ease-in-out">
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

        @if($pendingServices->isEmpty())
        <div class="text-center bg-gray-100 p-6 rounded-lg shadow-lg">
            <h3 class="text-2xl font-semibold text-gray-800">0 pending services.</h3>
            <p class="text-gray-600 mt-2">There are currently no services awaiting approval.</p>
        </div>
        @else
            <table class="w-full bg-white rounded-lg shadow-md overflow-hidden">
                <thead class="bg-[#9fb3fb] text-black">
                    <tr>
                        <th class="py-3 px-6 text-left font-semibold text-sm">Service Name</th>
                        <th class="py-3 px-6 text-left font-semibold text-sm">Description</th>
                        <th class="py-3 px-6 text-center font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingServices as $service)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-4 px-6 text-gray-700">{{ $service->service_name }}</td>
                            <td class="py-4 px-6 text-gray-600">{!! nl2br(e($service->description)) !!}</td>
                            <td class="py-4 px-6 flex justify-center space-x-2">

                                <a href="{{ route('pending.services.show', ['id' => $service->id]) }}" class="px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <!-- Approve Button -->
                                <form action="{{ route('services.approve', $service->id) }}" method="POST" class="inline-block" onsubmit="return confirmApproval()">
                                    @csrf
                                    <button type="submit" class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-green-500 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        <i class="bi bi-check-circle mr-1"></i>Approve
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <form action="{{ route('services.reject', $service->id) }}" method="POST" class="inline-block" onsubmit="return confirmReject()">
                                    @csrf
                                    <button type="submit" class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-red-500 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                        <i class="bi bi-x-circle mr-1"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script>

function confirmApproval() {
        return confirm('Are you sure you want to approve this service?');
    }
    function confirmReject() {
        return confirm('Are you sure you want to reject this service?');
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

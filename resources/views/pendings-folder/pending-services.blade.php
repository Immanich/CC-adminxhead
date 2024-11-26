@extends('pages.pendings')

@section('pending-content')
    <div class="">
        <div class="flex items-center ">
            {{-- <h1 class="text-2xl font-bold text-gray-700">Pending Services</h1> --}}
        </div>

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
                                <!-- Approve Button -->
                                <form action="{{ route('services.approve', $service->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                                        Approve
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <form action="{{ route('services.reject', $service->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                        Reject
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

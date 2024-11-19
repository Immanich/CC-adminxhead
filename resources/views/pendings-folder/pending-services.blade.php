@extends('pages.pendings')

@section('pending-content')
    <div class="">
        <div class="flex items-center ">
            {{-- <h1 class="text-2xl font-bold text-gray-700">Pending Services</h1> --}}
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success:</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
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
                            <td class="py-4 px-6 text-gray-600">{{ $service->description }}</td>
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
@endsection

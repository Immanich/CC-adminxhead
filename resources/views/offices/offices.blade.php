@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <!-- Feedback Button -->
        <a href="{{ route('feedbacks') }}" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">
            Feedback
        </a>

        <!-- List of Offices Title -->
        <h1 class="text-2xl font-bold text-center flex-1">
            List of Offices
        </h1>

        <!-- Add Office Button (Visible only to Admin) -->
        @role('admin')
            <button id="openAddOfficeModalButton" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                Add Office
            </button>
        @endrole
    </div>

    <hr class="mb-6 border-2 border-gray-300">

    <!-- Cards for Offices -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($offices as $office)
            <a href="{{ route('offices.show', $office->id) }}" class="block bg-[#ccd8fe] p-4 rounded-lg shadow-md hover:bg-[#bbc4fb]">
                <h2 class="text-xl font-semibold text-center">{{ $office->office_name }}</h2>
                {{-- <p class="text-gray-600 text-sm">{{ $office->description }}</p> --}}
                {{-- <div class="flex justify-end space-x-2 mt-2">
                    <a href="#" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600">
                        Edit
                    </a>
                    <form action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">
                            Delete
                        </button>
                    </form>
                </div> --}}
            </a>
        @endforeach
    </div>

    <!-- Add Office Modal -->
    @role('admin')
        <div id="addOfficeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg w-1/3 p-4">
                <h2 class="text-xl font-bold mb-4">Add New Office</h2>
                <form id="addOfficeForm" action="{{ route('admin.storeOffice') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="office_name" class="block text-sm font-medium">Office Name</label>
                        <input type="text" id="office_name" name="office_name" class="mt-1 p-2 block w-full border rounded" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="closeOfficeModalButton" class="mr-2 bg-gray-300 text-black px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endrole

    <script>
        document.getElementById('openAddOfficeModalButton').addEventListener('click', function () {
            document.getElementById('addOfficeForm').reset();
            document.getElementById('addOfficeModal').classList.remove('hidden');
        });

        document.getElementById('closeOfficeModalButton').addEventListener('click', function () {
            document.getElementById('addOfficeModal').classList.add('hidden');
        });
    </script>
@endsection

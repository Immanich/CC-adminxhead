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
            <button type="button"
        id="openAddOfficeModalButton"
        class="absolute right-9 mt-1 text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
    Create
</button>
        @endrole
    </div>

    <hr class="mb-6 border-2 border-gray-300">

    <!-- Cards for Offices -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($offices as $office)
            <div class="bg-gray-100 p-4 rounded-lg shadow-md flex flex-col justify-between h-full">
                <h2 class="text-xl font-semibold mb-4 text-center">{{ $office->office_name }}</h2>
                <div class="mt-auto flex justify-center space-x-2 mt-4">
                    <!-- View Office Services -->
                    <a href="{{ route('offices.services', $office->id) }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-eye"></i>
                    </a>

                    @role('admin')
                        <!-- Edit Button -->
                        <button onclick="openEditOfficeModal({{ $office->id }}, '{{ $office->office_name }}')" class="text-green-500 hover:text-green-700">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.deleteOffice', $office->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this office?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    @endrole
                </div>
            </div>
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

        <!-- Edit Office Modal -->
        <div id="editOfficeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg w-1/3 p-4">
                <h2 class="text-xl font-bold mb-4">Edit Office</h2>
                <form id="editOfficeForm" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="edit_office_name" class="block text-sm font-medium">Office Name</label>
                        <input type="text" id="edit_office_name" name="office_name" class="mt-1 p-2 block w-full border rounded" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="closeEditOfficeModalButton" class="mr-2 bg-gray-300 text-black px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endrole

    <script>
        // Add Office Modal
        document.getElementById('openAddOfficeModalButton').addEventListener('click', function () {
            document.getElementById('addOfficeForm').reset();
            document.getElementById('addOfficeModal').classList.remove('hidden');
        });

        document.getElementById('closeOfficeModalButton').addEventListener('click', function () {
            document.getElementById('addOfficeModal').classList.add('hidden');
        });

        // Edit Office Modal
        function openEditOfficeModal(id, name) {
            document.getElementById('editOfficeForm').action = '/admin/offices/' + id;
            document.getElementById('edit_office_name').value = name;
            document.getElementById('editOfficeModal').classList.remove('hidden');
        }

        document.getElementById('closeEditOfficeModalButton').addEventListener('click', function () {
            document.getElementById('editOfficeModal').classList.add('hidden');
        });
    </script>
@endsection

@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between mb-4">
    <!-- Feedback Button -->
    <!-- <a href="{{ route('feedbacks') }}" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">
        Feedback
    </a> -->

    <!-- Centered Title with Add Button -->
    <div class="flex items-center space-x-4 text-blue-900 flex-1 justify-center">
        <h1 class="text-4xl font-bold text-center">
            List of Offices
        </h1>

        <!-- Add Office Button (Visible only to Admin) -->
        @role('admin')
        <button type="button"
                id="openAddOfficeModalButton"
                class="text-white bg-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm w-10 h-10 flex items-center justify-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
            <i class="fa-solid fa-plus"></i>
        </button>
        @endrole
    </div>
</div>


    <hr class="mb-6 border-2 border-gray-300">

    @if(session('success'))
<div id="successMessage" class="bg-green-200 text-green-700 px-4 py-3 rounded relative mb-4 opacity-100 transition-opacity duration-1000 ease-in-out">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if ($errors->any())
<div id="errorMessage" class="bg-red-200 text-red-700 p-4 rounded mb-4 opacity-100 transition-opacity duration-1000 ease-in-out">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    <!-- Cards for Offices -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($offices as $office)
            <div class="bg-gray-200 p-4 rounded-lg shadow-md flex flex-col justify-between h-full">
                <h2 class="text-xl font-semibold mb-4 text-center">{{ $office->office_name }}</h2>
                <div class="mt-auto flex justify-center space-x-2 mt-4">
                    <!-- View Office Services -->
                    <a href="{{ route('offices.services', $office->id) }}"
                        class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-blue-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>

                    @role('admin')
                        <!-- Edit Button -->
                        <button onclick="openEditOfficeModal({{ $office->id }}, '{{ $office->office_name }}', '{{ $office->description }}', '{{ $office->address }}', '{{ $office->email }}', '{{ $office->contact_number }}')"
                            class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-green-500 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.deleteOffice', $office->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this office?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-red-500 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                <i class="fas fa-trash-alt mr-1"></i>Delete
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

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium">Description</label>
                        <input type="text" id="description" name="description" class="mt-1 p-2 block w-full border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium">Office Location</label>
                        <input type="text" id="address" name="address" class="mt-1 p-2 block w-full border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium">Office Email</label>
                        <input type="text" id="email" name="email" class="mt-1 p-2 block w-full border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="contact_number" class="block text-sm font-medium">Office Contact #</label>
                        <input type="text" id="contact_number" name="contact_number" class="mt-1 p-2 block w-full border rounded" required>
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

                    <div class="mb-4">
                        <label for="edit_description" class="block text-sm font-medium">Description</label>
                        <input type="text" id="edit_description" name="description" class="mt-1 p-2 block w-full border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="edit_address" class="block text-sm font-medium">Office Location</label>
                        <input type="text" id="edit_address" name="address" class="mt-1 p-2 block w-full border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="edit_email" class="block text-sm font-medium">Office Email</label>
                        <input type="text" id="edit_email" name="email" class="mt-1 p-2 block w-full border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="edit_contact_number" class="block text-sm font-medium">Office Contact #</label>
                        <input type="text" id="edit_contact_number" name="contact_number" class="mt-1 p-2 block w-full border rounded" required>
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
        function openEditOfficeModal(id, name, description, address, email, contact_number) {
            document.getElementById('editOfficeForm').action = '/admin/offices/' + id;
            document.getElementById('edit_office_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_contact_number').value = contact_number;
            document.getElementById('editOfficeModal').classList.remove('hidden');
        }

        document.getElementById('closeEditOfficeModalButton').addEventListener('click', function () {
            document.getElementById('editOfficeModal').classList.add('hidden');
        });

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

@extends('layouts.admin')

@section('content')
<div class="container mx-auto mt-2">
    <!-- Heading with Back Button -->
    <div class="flex items-center justify-between mb-6">
        @role('admin|user|sub_user')
        <a href="{{ route('offices.showServices', $office->id) }}" class="flex items-center rounded-full bg-gray-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-sm hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
            <i class="bi bi-arrow-left"> Back</i>
        </a>
        @endrole

        <h1 class="text-4xl font-extrabold text-center text-blue-900 flex-grow"> {{ $office->office_name }}'s Employees </h1>

        <!-- Add Employee Button -->
        <button onclick="openModal()" class="bg-blue-600 text-white py-2 px-4 rounded shadow hover:bg-blue-700">Add Employee</button>
    </div>


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

    <!-- Employee Cards Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 mt-8 p-3">
        @forelse ($office->employees as $employee)
        <div class="bg-white p-4 rounded-lg shadow-lg text-center hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
            <div class="mb-4">
                <!-- Profile Image -->
                <img src="{{ $employee->image ? (Str::startsWith($employee->image, 'http') ? $employee->image : asset('storage/' . $employee->image)) : 'default-avatar.png' }}"
                alt="Profile Image"
                class="w-24 h-24 rounded-full object-cover mx-auto">




            </div>
            <h2 class="text-xl font-semibold text-blue-900 mb-2">{{ $employee->name }}</h2>
            <p class="text-gray-600">{{ $employee->position }}</p>

            <!-- Edit and Delete Buttons -->
            <div class="mt-4 flex justify-center space-x-2">
                <button onclick="openEditModal({{ $employee }})"class="px-3 py-2 text-sm font-medium text-center inline-flex items-center text-white bg-green-500 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    <i class="fas fa-edit mr-1"></i>Edit</button>
                <form method="POST" action="{{ route('employees.destroy', $employee->id) }}" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2 text-sm font-medium text-center inline-flex items-center text-white bg-red-500 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        <i class="fas fa-trash-alt mr-1"></i>Delete</button>
                </form>
            </div>
        </div>
        @empty
        <p class="col-span-full text-center text-gray-500">No employees found for this office.</p>
        @endforelse
    </div>
</div>

<!-- Add/Edit Employee Modal -->
<div id="employeeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg">
        <h2 id="modalTitle" class="text-2xl font-bold mb-4">Add Employee</h2>
        <form id="employeeForm" method="POST" enctype="multipart/form-data" action="{{ route('employees.store', ['officeId' => $office->id]) }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                <input type="text" name="position" id="position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <!-- Image URL or File Upload -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Image</label>
                <div class="flex items-center space-x-2">
                    <!-- Image URL Field -->
                    <input type="url" name="image_url" id="image_url" placeholder="Image URL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="text-sm text-gray-500">or</span>
                    <!-- File Input Field -->
                    <input type="file" name="image_file" id="image_file" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>


            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('employeeModal');
    const form = document.getElementById('employeeForm');
    const modalTitle = document.getElementById('modalTitle');

    function openModal() {
        const officeId = '{{ $office->id }}';
        form.action = `/offices/${officeId}/employees`;
        form.reset();
        modalTitle.textContent = 'Add Employee';
        modal.classList.remove('hidden');

        // Clear image fields when opening modal for adding
        document.getElementById('image_url').value = '';
        document.getElementById('image_file').value = '';
    }

    function openEditModal(employee) {
        const officeId = '{{ $office->id }}';
        form.action = `{{ route('employees.update', ':id') }}`.replace(':id', employee.id);

        // Add the PUT method field to the form for update
        const existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        // Populate fields with current employee data
        document.getElementById('name').value = employee.name;
        document.getElementById('position').value = employee.position;

        // Handle displaying current image if available
        const imageUrl = employee.image ? `{{ asset('storage') }}/${employee.image}` : '';
        document.getElementById('image_url').value = imageUrl;
        document.getElementById('image_file').value = ''; // Clear the file input on edit

        modalTitle.textContent = 'Edit Employee';
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
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

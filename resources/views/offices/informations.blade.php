@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <!-- Back Button -->
        <a href="{{ url()->previous() }}" class="inline-flex items-center rounded-full bg-gray-600 py-2 px-4 text-sm font-medium text-white transition-all hover:bg-gray-700 focus:ring-2 focus:ring-gray-500">
            <i class="bi bi-arrow-left mr-2"></i> Back
        </a>
        <!-- Page Title -->
        <h1 class="text-4xl font-bold text-gray-800 flex-1 text-center">Office Information</h1>
    </div>

    <!-- Cards Container -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <!-- Office Card Loop -->
        @foreach ($offices as $office)
        <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200 relative">
            <h2 class="text-lg font-bold text-gray-800 mb-2">{{ $office->office_name }}</h2>
            <p class="text-sm text-gray-600 mb-1"><strong>Address:</strong> {{ $office->address }}</p>
            <p class="text-sm text-gray-600 mb-1"><strong>Email:</strong> {{ $office->email }}</p>
            <p class="text-sm text-gray-600 mb-1"><strong>Contact:</strong> {{ $office->contact_number }}</p>

            @if(auth()->user()->hasRole('admin') || (auth()->user()->hasRole('head|sub_head') && auth()->user()->office_id == $office->id))
                <!-- Pen Icon in the Upper Right Corner -->
                <button onclick="openEditOfficeModal({{ $office->id }}, '{{ $office->office_name }}', '{{ $office->description }}', '{{ $office->address }}', '{{ $office->email }}', '{{ $office->contact_number }}')"
                    class="absolute top-2 right-2 text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="bi bi-pencil-fill"></i> <!-- Font Awesome Pen Icon -->
                </button>
            @endif
        </div>
        @endforeach


<!-- Modal HTML -->
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


<script>
function openEditOfficeModal(id, name, description, address, email, contact_number) {
    document.getElementById('editOfficeForm').action = '/admin/offices/' + id;
    document.getElementById('edit_office_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_address').value = address;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_contact_number').value = contact_number;
    document.getElementById('editOfficeModal').classList.remove('hidden');
}

// Close modal
document.getElementById('closeEditOfficeModalButton').addEventListener('click', function () {
    document.getElementById('editOfficeModal').classList.add('hidden');
});
</script>
@endsection

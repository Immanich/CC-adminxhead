@extends('layouts.admin')

@section('content')
<!-- Success Message -->
@if (session('success'))
<div class="bg-green-500 text-white p-4 rounded mb-4">
    {{ session('success') }}
</div>
@endif
    <div class="flex items-center justify-between mb-4">
        <!-- Feedback Button -->
        <a href="{{ route('feedbacks') }}" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">
            Feedback
        </a>

        <!-- List of Services Title -->
        <h1 class="text-2xl font-bold text-center flex-1">
            {{ $office->office_name }} Services
        </h1>

        <!-- Add Service Button (Visible only to Admin) -->
        @role('admin|head')
            <button id="openAddServiceModalButton" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                Add Service
            </button>
        @endrole
    </div>

    <hr class="mb-6 border-2 border-gray-300">

    <!-- Cards for Services -->
    <div class="w-full gap-6">
        @foreach($services as $service)
        @if($service->status == 'approved')
            <div class="bg-[#ccd8fe] p-4 mb-3 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-center">{{ $service->service_name }}</h2>
                <p class="text-center">{{ $service->description }}</p>

                <div class="flex justify-center space-x-4 mt-4">
                    <a href="{{ route('services.show', ['id' => $service->id]) }}" class="bg-blue-500 text-white py-1 px-2 rounded-lg hover:bg-blue-600">
                        View
                    </a>
                    @role('admin|head')
                        <!-- Edit Button -->
                        <button type="button" onclick="openEditServiceModal({{ $service->id }})" class="bg-green-500 text-white py-1 px-2 rounded-lg hover:bg-green-600">
                            Edit
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.deleteService', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded-lg hover:bg-red-600">
                                Delete
                            </button>
                        </form>
                    @endrole
                </div>
            </div>
            @endif
        @endforeach
    </div>

    <!-- Add Service Modal -->
    @role('admin|head')
        <div id="addServiceModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg w-1/3 p-4">
                <h2 class="text-xl font-bold mb-4">Add New Service</h2>
                <form id="addServiceForm" action="{{ route('admin.storeService', $office->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="service_name" class="block text-sm font-medium">Service Name</label>
                        <input type="text" id="service_name" name="service_name" class="mt-1 p-2 block w-full border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium">Service Description</label>
                        <textarea id="description" name="description" class="mt-1 p-2 block w-full border rounded" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="classification" class="block text-sm font-medium">Classification</label>
                        <select id="classification" name="classification" class="mt-1 p-2 block w-full border rounded" required>
                            <option value="SIMPLE">SIMPLE</option>
                            <option value="COMPLEX">COMPLEX</option>
                            <option value="SIMPLE - COMPLEX">SIMPLE - COMPLEX</option>
                            <option value="HIGHLY TECHNICAL">HIGHLY TECHNICAL</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="transaction_id" class="block text-sm font-medium">Transaction</label>
                        <select id="transaction_id" name="transaction_id" class="mt-1 p-2 block w-full border rounded" required>
                            @foreach($transactions as $transaction)
                                <option value="{{ $transaction->id }}">{{ $transaction->type_of_transaction }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="checklist_of_requirements" class="block text-sm font-medium">Checklist of Requirements</label>
                        <textarea id="checklist_of_requirements" name="checklist_of_requirements" class="mt-1 p-2 block w-full border rounded"></textarea>
                    </div>

                    {{-- <div class="mb-4">
                        <label for="where_to_secure" class="block text-sm font-medium">Where to Secure</label>
                        <textarea id="where_to_secure" name="where_to_secure" class="mt-1 p-2 block w-full border rounded"></textarea>
                    </div> --}}

                    <div class="flex justify-end">
                        <button type="button" id="closeServiceModalButton" class="mr-2 bg-gray-300 text-black px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Service Modal -->
        <div id="editServiceModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg w-1/3 p-4">
                <h2 class="text-xl font-bold mb-4">Edit Service</h2>
                <form id="editServiceForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="edit_service_name" class="block text-sm font-medium">Service Name</label>
                        <input type="text" id="edit_service_name" name="service_name" class="mt-1 p-2 block w-full border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="edit_description" class="block text-sm font-medium">Service Description</label>
                        <textarea id="edit_description" name="description" class="mt-1 p-2 block w-full border rounded" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="edit_classification" class="block text-sm font-medium">Classification</label>
                        <select id="edit_classification" name="classification" class="mt-1 p-2 block w-full border rounded" required>
                            <option value="SIMPLE">SIMPLE</option>
                            <option value="COMPLEX">COMPLEX</option>
                            <option value="SIMPLE - COMPLEX">SIMPLE - COMPLEX</option>
                            <option value="HIGHLY TECHNICAL">HIGHLY TECHNICAL</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="edit_transaction_id" class="block text-sm font-medium">Transaction</label>
                        <select id="edit_transaction_id" name="transaction_id" class="mt-1 p-2 block w-full border rounded" required>
                            @foreach($transactions as $transaction)
                                <option value="{{ $transaction->id }}">{{ $transaction->type_of_transaction }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="edit_checklist_of_requirements" class="block text-sm font-medium">Checklist of Requirements</label>
                        <textarea id="edit_checklist_of_requirements" name="checklist_of_requirements" class="mt-1 p-2 block w-full border rounded"></textarea>
                    </div>

                    {{-- <div class="mb-4">
                        <label for="edit_where_to_secure" class="block text-sm font-medium">Where to Secure</label>
                        <textarea id="edit_where_to_secure" name="where_to_secure" class="mt-1 p-2 block w-full border rounded"></textarea>
                    </div> --}}

                    <div class="flex justify-end">
                        <button type="button" id="closeEditServiceModalButton" class="mr-2 bg-gray-300 text-black px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endrole

    <script>
        // Add Service Modal
        document.getElementById('openAddServiceModalButton').addEventListener('click', function () {
            document.getElementById('addServiceForm').reset();
            document.getElementById('addServiceModal').classList.remove('hidden');
        });

        document.getElementById('closeServiceModalButton').addEventListener('click', function () {
            document.getElementById('addServiceModal').classList.add('hidden');
        });

        // Edit Service Modal
        function openEditServiceModal(id) {
            fetch(`/admin/services/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_service_name').value = data.service_name;
                    document.getElementById('edit_description').value = data.description;
                    document.getElementById('edit_classification').value = data.classification;
                    document.getElementById('edit_transaction_id').value = data.transaction_id;
                    document.getElementById('edit_checklist_of_requirements').value = data.checklist_of_requirements;
                    // document.getElementById('edit_where_to_secure').value = data.where_to_secure;

                    // Set the form action to the update route
                    document.getElementById('editServiceForm').action = `/admin/services/${id}/update`;

                    // Show the modal
                    document.getElementById('editServiceModal').classList.remove('hidden');
                });
        }

        document.getElementById('closeEditServiceModalButton').addEventListener('click', function () {
            document.getElementById('editServiceModal').classList.add('hidden');
        });
    </script>
@endsection

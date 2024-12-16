@extends('layouts.admin')

@section('content')
<div class="container rounded-lg shadow-md" style="max-width: 100%;">
    <!-- Fetch the first organizational chart record -->
    @php
        $orgChart = \App\Models\OrganizationalChart::first();
    @endphp

    <div class="relative">
        <!-- Display Organizational Chart Image -->
        @if($orgChart && $orgChart->image)
            <img src="{{ asset('assets/images/' . $orgChart->image) }}" alt="Organizational Chart" style="width: 100%; height: auto; display: block;">
        @else
            <img src="/assets/images/org.jpg" alt="Default Organizational Chart" style="width: 100%; height: auto; display: block;">
        @endif

        <!-- Dropdown Menu -->
        @role('admin')
        <div class="absolute top-4 right-4">
            <div class="relative">
                <button onclick="toggleDropdown()" class="text-white bg-gray-400 px-2 rounded-full hover:bg-gray-600 focus:outline-none">
                    <i class="bi bi-three-dots text-2xl"></i>
                </button>
                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
                    <ul class="py-1">
                        @if($orgChart)
                            <li>
                                <button onclick="toggleModal('editModal')" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Edit Image
                                </button>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('org-chart.destroy', $orgChart->id) }}" onsubmit="return confirmDelete()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        Delete Image
                                    </button>
                                </form>
                            </li>
                        @else
                            <li>
                                <button onclick="toggleModal('addModal')" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Add Image
                                </button>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @endrole
    </div>
</div>

<!-- Add Image Modal -->
<div id="addModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-md p-6 w-1/3">
        <h2 class="text-lg font-semibold mb-4">Add Organizational Chart Image</h2>
        <form method="POST" action="{{ route('org-chart.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Upload New Image</label>
                <input type="file" name="image" id="image" class="mt-2 p-2 w-full border rounded" required>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="toggleModal('addModal')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                    Cancel
                </button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Image Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-md p-6 w-1/3">
        <h2 class="text-lg font-semibold mb-4">Edit Organizational Chart Image</h2>
        <form method="POST" action="{{ route('org-chart.update', $orgChart->id ?? 0) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Upload New Image</label>
                <input type="file" name="image" id="image" class="mt-2 p-2 w-full border rounded" required>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="toggleModal('editModal')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.toggle('hidden');
    }

    function toggleDropdown() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('hidden');
    }

    function confirmDelete() {
        return confirm('Are you sure you want to delete this image?');
    }
</script>
@endsection

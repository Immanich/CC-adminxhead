@extends('layouts.admin')

@section('content')
<div class="container bg-black p-6 rounded-lg shadow-md bg-main-content" style="min-height: 100vh; padding-bottom: 5rem;">
    <h1 class="text-6xl font-bold text-center mb-6" style="font-family: 'Old English Text MT', serif;">Municipal Officials</h1>
    <h2 class="text-2xl text-center font-bold mb-4 text-red-500">TUBIGON, BOHOL, PHILIPPINES</h2>
    <h3 class="text-xl text-center font-bold mb-8 text-red-800">
        ({{ $officials->first()->start_year }} - {{ $officials->first()->end_year }})
       @role('admin') <i class="fas fa-pen text-sm text-gray-500 cursor-pointer" onclick="openYearEditModal()"></i> @endrole
    </h3>


    <!-- Officials Layout -->
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

<div class="text-center">
    <!-- Mayor and Vice Mayor -->
    <div class="grid grid-cols-2 gap-6 mb-20">
        @foreach($officials->whereIn('title', ['Municipal Mayor', 'Municipal Vice Mayor']) as $official)
        <div class="relative mb-10 group">
            <div class="relative w-40 h-40 mx-auto border-4 border-gray-300 rounded-full shadow-lg p-2 bg-white">
                <!-- Official Image -->
                <img src="{{ $official->image }}" alt="{{ $official->name }}" class="w-full h-full rounded-full">
                <!-- Edit Icon -->
                @role('admin')
                <button
                    onclick="openEditModal({{ $official }})"
                    class="absolute inset-0 bg-black bg-opacity-40 text-white flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <i class="fas fa-pen text-xl"></i>
                </button>
                @endrole
            </div>
            <div class="absolute -bottom-20 left-1/2 transform -translate-x-1/2 w-64 h-16 text-center">
                <div class="px-2 py-1 rounded-lg shadow-lg flex flex-col items-center justify-center h-full"
                     style="background: linear-gradient(90deg, #BF953F, #FCF6BA, #FCF6BA, #FBF5B7, #AA771C);">
                    <h3 class="text-base font-bold text-red-600">{{ $official->name }}</h3>
                    <p class="text-sm font-semibold text-red-800">{{ $official->title }}</p>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    <!-- First Row of SB Members -->
    <div class="grid grid-cols-4 gap-6 text-center mb-20">
        @foreach($officials->where('title', 'SB Member')->take(4) as $sbMember)
        <div class="relative mb-10 group">
            <div class="relative w-36 h-36 mx-auto border-4 border-gray-300 rounded-full shadow-lg p-2 bg-white">
                <!-- Official Image -->
                <img src="{{ $sbMember->image }}" alt="{{ $sbMember->name }}" class="w-full h-full rounded-full">
                <!-- Edit Icon -->
                @role('admin')
                <button
                    onclick="openEditModal({{ $sbMember }})"
                    class="absolute inset-0 bg-black bg-opacity-40 text-white flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <i class="fas fa-pen text-xl"></i>
                </button>
                @endrole
            </div>
            <div class="absolute -bottom-20 left-1/2 transform -translate-x-1/2 w-64 h-16 text-center">
                <div class="px-2 py-1 rounded-lg shadow-lg flex flex-col items-center justify-center h-full"
                     style="background: linear-gradient(90deg, #BF953F, #FCF6BA, #FCF6BA, #FBF5B7, #AA771C);">
                    <h3 class="text-base font-bold text-red-600">{{ $sbMember->name }}</h3>
                    <p class="text-sm font-semibold text-red-800">{{ $sbMember->title }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Second Row of SB Members -->
    <div class="grid grid-cols-4 gap-6 text-center mb-20">
        @foreach($officials->where('title', 'SB Member')->skip(4)->take(4) as $sbMember)
        <div class="relative mb-10 group">
            <div class="relative w-40 h-40 mx-auto border-4 border-gray-300 rounded-full shadow-lg p-2 bg-white">
                <!-- Official Image -->
                <img src="{{ $sbMember->image }}" alt="{{ $sbMember->name }}" class="w-full h-full rounded-full">
                <!-- Edit Icon -->
                @role('admin')
                <button
                    onclick="openEditModal({{ $sbMember }})"
                    class="absolute inset-0 bg-black bg-opacity-40 text-white flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <i class="fas fa-pen text-xl"></i>
                </button>
                @endrole
            </div>
            <div class="absolute -bottom-20 left-1/2 transform -translate-x-1/2 w-64 h-16 text-center">
                <div class="px-2 py-1 rounded-lg shadow-lg flex flex-col items-center justify-center h-full"
                     style="background: linear-gradient(90deg, #BF953F, #FCF6BA, #FCF6BA, #FBF5B7, #AA771C);">
                    <h3 class="text-base font-bold text-red-600">{{ $sbMember->name }}</h3>
                    <p class="text-sm font-semibold text-red-800">{{ $sbMember->title }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Additional Positions -->
    <div class="grid grid-cols-3 gap-6 text-center mt-8">
        @foreach($officials->whereIn('title', ['ABC President', 'SK Federation President', 'SB Secretary']) as $official)
        <div class="relative mb-10 group">
            <div class="relative w-36 h-36 mx-auto border-4 border-gray-300 rounded-full shadow-lg p-2 bg-white">
                <!-- Official Image -->
                <img src="{{ $official->image }}" alt="{{ $official->name }}" class="w-full h-full rounded-full">
                <!-- Edit Icon -->
                @role('admin')
                <button
                    onclick="openEditModal({{ $official }})"
                    class="absolute inset-0 bg-black bg-opacity-40 text-white flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <i class="fas fa-pen text-xl"></i>
                </button>
                @endrole
            </div>
            <div class="absolute -bottom-20 left-1/2 transform -translate-x-1/2 w-64 h-16 text-center">
                <div class="px-2 py-1 rounded-lg shadow-lg flex flex-col items-center justify-center h-full"
                     style="background: linear-gradient(90deg, #BF953F, #FCF6BA, #FCF6BA, #FBF5B7, #AA771C);">
                    <h3 class="text-base font-bold text-red-600">{{ $official->name }}</h3>
                    <p class="text-sm font-semibold text-red-800">{{ $official->title }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
        <h2 class="text-xl font-bold mb-4">Edit Official</h2>
        <form id="editForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium">Name</label>
                <input type="text" id="name" name="name" class="w-full border rounded p-2" required>
            </div>

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium">Title</label>
                <input type="text" id="title" name="title" class="w-full border rounded p-2" required>
            </div>

            <!-- Toggle Between Link or File -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Image Source</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="image_type" value="link" id="image_type_link" class="form-radio" checked>
                        <span>Image URL</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="image_type" value="file" id="image_type_file" class="form-radio">
                        <span>Upload Image</span>
                    </label>
                </div>
            </div>

            <!-- Image URL -->
            <div id="imageUrlField" class="mb-4">
                <label for="image" class="block text-sm font-medium">Image URL</label>
                <input type="url" id="image" name="image" class="w-full border rounded p-2">
            </div>

            <!-- Image File Upload -->
            <div id="imageFileField" class="mb-4 hidden">
                <label for="image_file" class="block text-sm font-medium">Upload Image</label>
                <input type="file" id="image_file" name="image_file" class="w-full border rounded p-2">
            </div>

            <!-- Modal Buttons -->
            <div class="flex justify-end space-x-4">
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500" onclick="closeEditModal()">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<div id="yearEditModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Edit Year Range</h2>
        <form action="{{ route('updateYear') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="start_year" class="block text-sm font-medium">Start Year</label>
                <input type="text" id="start_year" name="start_year" value="{{ $officials->first()->start_year }}" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label for="end_year" class="block text-sm font-medium">End Year</label>
                <input type="text" id="end_year" name="end_year" value="{{ $officials->first()->end_year }}" class="w-full border rounded p-2" required>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500" onclick="closeYearEditModal()">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-main-content {
    /* background-image: url('/assets/images/bg.jpg');  */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-color: rgba(255, 255, 255, 0);
    background-blend-mode: overlay; /* Blend color with the image */
}


</style>
<script>
    // Function to toggle between Image URL and Upload Image
    function toggleImageFields() {
        const linkField = document.getElementById('imageUrlField');
        const fileField = document.getElementById('imageFileField');
        const linkRadio = document.getElementById('image_type_link');
        const fileRadio = document.getElementById('image_type_file');

        linkRadio.addEventListener('change', function () {
            if (this.checked) {
                linkField.classList.remove('hidden');
                fileField.classList.add('hidden');
            }
        });

        fileRadio.addEventListener('change', function () {
            if (this.checked) {
                linkField.classList.add('hidden');
                fileField.classList.remove('hidden');
            }
        });
    }

    toggleImageFields(); // Call the function to add event listeners

    function openEditModal(official) {
    document.getElementById('name').value = official.name;
    document.getElementById('title').value = official.title;

    // Check if the official's image is a URL
    if (official.image.startsWith('http')) {
        document.getElementById('image').value = official.image;
        document.getElementById('image_type_link').checked = true;
        document.getElementById('imageUrlField').classList.remove('hidden');
        document.getElementById('imageFileField').classList.add('hidden');
    } else {
        // Clear the image URL field and toggle to file upload
        document.getElementById('image').value = '';
        document.getElementById('image_type_file').checked = true;
        document.getElementById('imageFileField').classList.remove('hidden');
        document.getElementById('imageUrlField').classList.add('hidden');
    }

    // Set the form action correctly
    const form = document.getElementById('editForm');
    form.action = `/municipal-officials/${official.id}`; // Corrected URL without `update`

    // Show the modal
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function openYearEditModal() {
    document.getElementById('yearEditModal').classList.remove('hidden');
    document.getElementById('yearEditModal').classList.add('flex');
}

function closeYearEditModal() {
    document.getElementById('yearEditModal').classList.add('hidden');
    document.getElementById('yearEditModal').classList.remove('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
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


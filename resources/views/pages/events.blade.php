@extends('layouts.admin')

@section('content')
    <!-- Header with Centered Title and Right-Aligned Add Button -->
    <div class="flex items-center space-x-4 justify-center relative mb-4">
        <h1 class="text-4xl font-bold text-center">
            Events
        </h1>

        <!-- Add Button -->
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('user') || auth()->user()->hasRole('sub_user'))
        <button type="button"
                id="openAddModal"
                class="text-white bg-blue-700 hover:text-white border border-blue-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm w-10 h-10 flex items-center justify-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
            <i class="fa-solid fa-plus"></i>
        </button>
        @endif
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

    <h2 class="text-lg font-bold mb-2">Events for You</h2>

    <!-- Events Grid Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($approvedEvents as $event)
            <div class="relative group border border-gray-300 rounded-lg shadow-md bg-white overflow-hidden transition-transform duration-300 transform hover:scale-105 w-full max-w-lg">
                <!-- Event Image with Hover Overlay -->
                <div class="relative">
                    <img src="{{ $event->image }}" alt="Event Image" class="w-full h-72 object-cover">

                    <!-- Title and Description Overlay -->
                    <div class="absolute inset-0 flex flex-col justify-end p-4 bg-gradient-to-t from-black to-transparent transition-opacity duration-300 ease-in-out group-hover:bg-black group-hover:bg-opacity-50">
                        <!-- Title - Positioned close to the bottom when not hovered, moves slightly up on hover -->
                        <h2 class="text-xl font-semibold text-white opacity-100 transition-transform duration-300 ease-in-out transform group-hover:translate-y-[-5px]">
                            {{ $event->title }}
                        </h2>

                        <h3 class="text-sm font-medium text-gray-300 opacity-90 transition-transform duration-300 ease-in-out transform group-hover:translate-y-[-5px]">
                            {{ \Carbon\Carbon::parse($event->date_time)->format('M d, Y') }}
                        </h3>

                        <!-- Description - Initially hidden, fades in on hover -->
                        <p class="text-sm text-gray-200 opacity-0 transform translate-y-4 transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:translate-y-0">
                            {{ Str::limit($event->description, 50) }}
                        </p>
                    </div>
                </div>

                <!-- Centered Action Buttons -->
                <div class="flex justify-center space-x-4 p-4">
                    <!-- View Button -->
                    <a href="{{ route('events.show', $event->id) }}"
                        class="text-white bg-blue-500 hover:bg-blue-600 border border-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm w-8 h-8 flex items-center justify-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if(auth()->user()->hasRole('admin') || auth()->id() === $event->user_id)
                    <button onclick="editEvent({{ $event->id }})"
                        class="text-white bg-green-500 hover:bg-green-600 border border-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-full text-sm w-8 h-8 flex items-center justify-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <i class="fas fa-edit"></i>
                    </button>
                @endif

                <!-- Delete Button -->
                @if(auth()->user()->hasRole('admin') || auth()->id() === $event->user_id)
                    <form action="{{ route('events.delete', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-white bg-red-500 hover:bg-red-600 border border-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm w-8 h-8 flex items-center justify-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                @endif
                </div>

            </div>
        @endforeach
    </div>

 <!-- Add/Edit Event Modal -->
<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-4 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-lg font-bold mb-2" id="modalTitle">Create New Event</h2>
        <form id="eventForm" action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Hidden Method Field -->
            <input type="hidden" name="_method" value="POST" id="formMethod">

            <input type="hidden" id="eventId" name="event_id">

            <!-- Event Title -->
            <div class="mb-2">
                <label for="title" class="block text-sm font-medium">Event Title</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full p-1 border rounded" required>
            </div>

            <!-- Event Description -->
            <div class="mb-2">
                <label for="description" class="block text-sm font-medium">Event Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full p-1 border rounded" rows="3" required></textarea>
            </div>

            <!-- Event Date and Time -->
            <div class="mb-2">
                <label for="date_time" class="block text-sm font-medium">Event Date and Time</label>
                <input type="datetime-local" id="date_time" name="date_time" class="mt-1 block w-full p-1 border rounded" required>
            </div>


            <!-- Image Type Selector -->
            <div class="mb-2">
                <label class="block text-sm font-medium">Image Source</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="image_type" value="url" id="image_type_url" class="form-radio" checked>
                        <span>Image URL</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="image_type" value="file" id="image_type_file" class="form-radio">
                        <span>Upload Image</span>
                    </label>
                </div>
            </div>

            <!-- Image URL Input -->
            <div id="imageUrlField" class="mb-2">
                <label for="image" class="block text-sm font-medium">Image URL</label>
                <input type="url" id="image" name="image" class="mt-1 block w-full p-1 border rounded" placeholder="Paste image URL here">
            </div>

            <!-- Image File Upload -->
            <div id="imageFileField" class="mb-2 hidden">
                <label for="image_file" class="block text-sm font-medium">Upload Image</label>
                <input type="file" id="image_file" name="image_file" class="mt-1 block w-full p-1 border rounded">
            </div>

            <!-- Modal Buttons -->
            <div class="flex justify-end space-x-4">
                <button type="button" id="closeModal" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                    Cancel
                </button>
                <button type="submit" id="submitButton" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Save Event
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Modal Script -->
<script>
   const openAddModal = document.getElementById('openAddModal');
const closeModalButton = document.getElementById('closeModal');
const modal = document.getElementById('eventModal');
const eventForm = document.getElementById('eventForm');
const formMethodField = document.getElementById('formMethod');
const imageTypeUrl = document.getElementById('image_type_url');
const imageTypeFile = document.getElementById('image_type_file');
const imageUrlField = document.getElementById('imageUrlField');
const imageFileField = document.getElementById('imageFileField');

// Toggle between Image URL and File Upload
function toggleImageFields() {
    if (imageTypeUrl.checked) {
        imageUrlField.classList.remove('hidden');
        imageFileField.classList.add('hidden');
    } else if (imageTypeFile.checked) {
        imageFileField.classList.remove('hidden');
        imageUrlField.classList.add('hidden');
    }
}

// Attach event listeners to the radio buttons
imageTypeUrl.addEventListener('change', toggleImageFields);
imageTypeFile.addEventListener('change', toggleImageFields);

// Open modal for adding a new event
openAddModal.addEventListener('click', () => {
    document.getElementById('eventId').value = '';
    document.getElementById('title').value = '';
    document.getElementById('description').value = '';
    document.getElementById('date_time').value = '';
    document.getElementById('image').value = '';
    document.getElementById('image_file').value = '';
    imageTypeUrl.checked = true; // Default to Image URL
    toggleImageFields(); // Reset input fields

    // Set form for creating
    formMethodField.value = 'POST';
    eventForm.action = '{{ route("events.store") }}';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
});

// Close modal
closeModalButton.addEventListener('click', () => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
});

// Open modal for editing an event
function editEvent(id) {
    fetch(`/events/${id}/edit`)
        .then(response => response.json())
        .then(event => {
            document.getElementById('eventId').value = event.id;
            document.getElementById('title').value = event.title;
            document.getElementById('description').value = event.description;
            document.getElementById('date_time').value = event.date_time;

            if (event.image.startsWith('http')) {
                imageTypeUrl.checked = true;
                document.getElementById('image').value = event.image;
            } else {
                imageTypeFile.checked = true;
                document.getElementById('image_file').value = '';
            }

            toggleImageFields();

            // Correct the form action to match the route
            eventForm.action = `/events/${event.id}/update`;
            formMethodField.value = 'PUT'; // Set method as PUT
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
};

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

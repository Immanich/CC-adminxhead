@extends('layouts.admin')

@section('content')
    <!-- Header with Centered Title and Right-Aligned Add Button -->
    <div class="flex items-center justify-center mb-4 relative">
        <h1 class="text-3xl font-bold">EVENTS</h1>
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('user'))
            <button class="absolute right-0 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600" id="openAddModal">
                Add Event
            </button>
        @endif
    </div>

    @if (session('success'))
        <div class="bg-green-500 text-white p-2 rounded mb-4 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-500 text-white p-2 rounded mb-4 text-center">
            {{ session('error') }}
        </div>
    @endif

    <h2 class="text-lg font-semibold mb-2">Events for You</h2>

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
                        <h2 class="text-lg font-semibold text-white opacity-100 transition-transform duration-300 ease-in-out transform group-hover:translate-y-[-5px]">
                            {{ $event->title }}
                        </h2>
                        <!-- Description - Initially hidden, fades in on hover -->
                        <p class="text-sm text-gray-200 opacity-0 transform translate-y-4 transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:translate-y-0">
                            {{ Str::limit($event->description, 50) }}
                        </p>
                    </div>
                </div>

                <!-- Centered Action Buttons -->
                <div class="flex justify-center space-x-4 p-4">
                    <a href="{{ route('events.show', $event->id) }}" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button onclick="editEvent({{ $event->id }})" class="text-green-500 hover:text-green-700">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('events.delete', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add/Edit Event Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-lg font-bold mb-2" id="modalTitle">Create New Event</h2>
            <form id="eventForm" action="{{ route('events.store') }}" method="POST">
                @csrf
                <input type="hidden" id="eventId" name="event_id">
                <div class="mb-2">
                    <label for="title" class="block text-sm font-medium">Event Title</label>
                    <input type="text" id="title" name="title" class="mt-1 block w-full p-1 border rounded" required>
                </div>

                <div class="mb-2">
                    <label for="description" class="block text-sm font-medium">Event Description</label>
                    <textarea id="description" name="description" class="mt-1 block w-full p-1 border rounded" rows="3" required></textarea>
                </div>

                <div class="mb-2">
                    <label for="image" class="block text-sm font-medium">Event Image URL</label>
                    <input type="url" id="image" name="image" class="mt-1 block w-full p-1 border rounded" placeholder="Paste image URL here" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="submitButton" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600">
                        Save Event
                    </button>
                    <button type="button" id="closeModal" class="ml-2 bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">
                        Cancel
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
        const submitButton = document.getElementById('submitButton');
        const eventForm = document.getElementById('eventForm');

        openAddModal.addEventListener('click', () => {
            document.getElementById('eventId').value = '';
            document.getElementById('title').value = '';
            document.getElementById('description').value = '';
            document.getElementById('image').value = '';
            eventForm.action = '{{ route("events.store") }}';
            document.getElementById('modalTitle').textContent = "Create New Event";
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        closeModalButton.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        function editEvent(id) {
            fetch(`/events/${id}/edit`)
                .then(response => response.json())
                .then(event => {
                    document.getElementById('eventId').value = event.id;
                    document.getElementById('title').value = event.title;
                    document.getElementById('description').value = event.description;
                    document.getElementById('image').value = event.image;
                    eventForm.action = `/events/${event.id}/update`;
                    submitButton.form.method = 'POST';
                    submitButton.insertAdjacentHTML('beforebegin', '<input type="hidden" name="_method" value="PUT">');
                    document.getElementById('modalTitle').textContent = "Edit Event";
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
        }
    </script>
@endsection

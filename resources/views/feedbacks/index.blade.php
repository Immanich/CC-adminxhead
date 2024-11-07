@extends('layouts.admin')

@section('content')
    <!-- <div class="mb-4">
            <h1 class="text-2xl font-bold text-center mb-6">Submit Feedback</h1>

            <form action="{{ route('feedbacks.store') }}" method="POST" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
                @csrf

                <div class="mb-4">
                    <label for="office_id" class="block text-sm font-medium">Select Office</label>
                    <select id="office_id" name="office_id" class="mt-1 block w-full p-2 border rounded" required>
                        <option value="">-- Select Office --</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium">Feedback</label>
                    <textarea id="comment" name="comment" class="mt-1 block w-full p-2 border rounded" rows="4" required></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Submit</button>
                </div>
            </form>
    </div> -->

    @role(['admin', 'head'])
        <div>
            <h2 class="text-2xl font-bold text-center mb-4">Feedbacks</h2>

            <div class="grid grid-cols-1 gap-6">
                @foreach($feedbacks as $feedback)
                    <div class="bg-[#eef2fe] p-4 rounded-lg shadow-md">
                        <div class="header flex justify-between w-full">
                            <p class="text-gray-500 mb-2">Sent by: {{ $feedback->name ?? 'Anonymous'}}</p>
                            <span class="text-xs text-gray-500">
                                {{ $feedback->created_at->setTimezone('Asia/Manila')->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <p class="font-semibold text-lg text-gray-700">{{ $feedback->office->office_name }}</p>
                        <p class="font-semibold text-lg text-gray-700">{{ $feedback->service->service_name }}</p>
                        <p class="text-gray-600">{{ $feedback->feedback }}</p>

                        <div class="flex justify-between items-center mt-4">
                            <div class="mt-4">
                                @if($feedback->reply)
                                    <div class="bg-green-100 p-3 rounded-lg border border-green-300">
                                        <p class="text-green-700"><strong>Replied:</strong> {{ $feedback->reply }}</p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <button data-feedback-id="{{ $feedback->id }}" class="text-blue-500 hover:underline" onclick="toggleEditForm(this)">Edit</button>
                                        </div>
                                    </div>
                                    <form id="edit-form-{{ $feedback->id }}" action="{{ route('feedbacks.updateReply', $feedback->id) }}" method="POST" class="mt-2 hidden">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center gap-2">
                                            <input type="text" name="reply" class="flex-1 p-2 border rounded" value="{{ $feedback->reply }}" required>
                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save</button>
                                            <button type="button" data-feedback-id="{{ $feedback->id }}" class="text-red-500 hover:underline" onclick="toggleEditForm(this)">Cancel</button>
                                        </div>
                                    </form>
                                @else
                                    <form action="{{ route('feedbacks.reply', $feedback->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        <div class="flex items-center gap-2">
                                            <input type="text" name="reply" class="flex-1 p-2 border rounded" placeholder="Write a reply..." required>
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Reply</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endrole
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const updateTimestamps = () => {
            document.querySelectorAll('[data-timestamp]').forEach(element => {
                const timestamp = element.getAttribute('data-timestamp');
                const localTime = moment.tz(timestamp, moment.tz.guess()).fromNow();
                element.textContent = localTime;
            });
        };

        updateTimestamps();
        setInterval(updateTimestamps, 60000);
    });
    
    function toggleEditForm(button) {
        const feedbackId = button.getAttribute('data-feedback-id');
        const form = document.getElementById(`edit-form-${feedbackId}`);
        if (form) {
            form.classList.toggle('hidden');
        }
    }

    function submitReply(feedbackId) {
        const replyInput = document.querySelector(`#edit-form-${feedbackId} input[name="reply"]`);
        const reply = replyInput.value;

        $.ajax({
            url: `/feedbacks/${feedbackId}/reply`,  // Make sure the route matches your route definition
            method: 'POST',
            data: {
                reply: reply,
                _token: '{{ csrf_token() }}'  // Include CSRF token
            },
            success: function(response) {
                // Update the frontend with the new reply
                const replyDiv = document.querySelector(`#feedback-${feedbackId} .reply-display`);
                replyDiv.innerHTML = `<strong>Replied:</strong> ${response.reply}`;
                replyDiv.classList.remove('hidden');

                // Hide the edit form and reset input value
                document.getElementById(`edit-form-${feedbackId}`).classList.add('hidden');
                replyInput.value = '';
            },
            error: function(error) {
                console.error('Error:', error);
                alert('Failed to submit reply.');
            }
        });
    }
</script>


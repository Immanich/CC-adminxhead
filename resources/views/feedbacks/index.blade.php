@extends('layouts.admin')

@section('content')
    <div class="mb-4">
        <!-- Feedback Submission Form for Testing (Hide this in production) -->
        {{-- @guest --}}
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
        {{-- @endguest --}}
    </div>

    <!-- Displaying Feedbacks (Visible to user and Admin) -->
    @role(['admin', 'user'])
        <div>
            <h2 class="text-2xl font-bold text-center mb-4">Feedbacks</h2>

            <div class="grid grid-cols-1 gap-6">
                @foreach($feedbacks as $feedback)
                    <div class="bg-[#eef2fe] p-4 rounded-lg shadow-md">
                        <!-- Display Office Name -->
                        <p class="font-semibold text-lg text-gray-700">{{ $feedback->office->office_name }}</p>

                        <!-- Display Anonymous as Sender -->
                        <p class="text-gray-500 mb-2">Sent by: Anonymous</p>

                        <!-- Display Feedback Comment -->
                        <p class="text-gray-600">{{ $feedback->comment }}</p>

                        <!-- Feedback Date and Reply Section -->
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-xs text-gray-500">{{ $feedback->created_at->format('d M Y, H:i') }}</span>
                            <div>
                                @if($feedback->reply)
                                    <p class="text-green-500">{{ $feedback->reply }}</p>
                                @else
                                    <form action="{{ route('feedbacks.reply', $feedback->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="text" name="reply" class="p-2 border rounded" placeholder="Reply..." required>
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Reply</button>
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

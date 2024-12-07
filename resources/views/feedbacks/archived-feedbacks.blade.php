@extends('layouts.admin')

@section('content')
    @role(['admin', 'user'])    
    <div>
        <h2 class="text-2xl font-bold text-center mb-4 w-full">Archived Feedbacks</h2>
        <div class="flex justify-between mb-2">
            <span></span>
            <a href="{{ route('feedbacks.index')}}" class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-500">Back</a>
        </div>

        @if($feedbacks->isEmpty())
            <div class="bg-yellow-100 p-4 rounded-lg shadow-md text-center">
                <p class="text-gray-700">No archived feedbacks yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($feedbacks as $feedback)
                    <div class="bg-[#f4f4f4] p-4 rounded-lg shadow-md">
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
                            <form action="{{ route('feedbacks.restore', $feedback->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Restore</button>
                            </form>
                        </div>
                        <div>
                            <form action="{{ route('feedbacks.archivedDestroy', $feedback->id) }}" method="POST" onsubmit="return confirmDelete(event, this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-white hover:underline">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endrole
@endsection
<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        if (confirm('Are you sure you want to delete this feedback?')) {
            form.submit();
        }
    }
</script>
<!-- @extends('layouts.admin')

@section('content')
    @role(['admin', 'user'])
        <div>
            <h2 class="text-2xl font-bold text-center mb-4">Archived Feedbacks</h2>

            @if($feedbacks->isEmpty())
                <div class="bg-yellow-100 p-4 rounded-lg shadow-md text-center">
                    <p class="text-gray-700">No archived feedbacks yet!</p>
                </div>
            @else
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
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endrole
@endsection -->

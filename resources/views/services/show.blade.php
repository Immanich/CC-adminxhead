@extends('layouts.admin')

@section('content')
    <!-- Success and Error Messages -->
    <!-- User Section -->
    <div class="flex items-center justify-between mb-4">
        <!-- Back Button -->
        @role('admin|user|sub_user')
        <a href="{{ route('offices.showServices', $service->office_id) }}" class="flex items-center rounded-full bg-gray-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-sm hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
            <i class="bi bi-arrow-left"> Back</i>
        </a>
        @endrole

        <!-- Office Name + Services Title -->
        <div class="flex items-center space-x-4 flex-1 justify-center">
            <h1 class="text-2xl font-bold text-center">
                {{ $service->service_name }}
            </h1>

            <!-- Add Service Button (Visible only to Admin or user) -->
            @role('admin|user|sub_user')
            <button class="text-white bg-blue-700 hover:text-white border border-blue-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm w-12 h-12 flex items-center justify-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800"
                    onclick="document.getElementById('addServiceModal').style.display='flex'">
                <i class="fa-solid fa-plus"></i>
            </button>
            @endrole
        </div>
    </div>


    <hr class="mb-6 border-2 border-gray-300">


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

    <!-- Services List -->
    <div id="servicesList" class="grid grid-cols-1 gap-6">
        {{-- <h2>About this service:</h2> --}}
        <h2 class="text-justify font-semibold">{!! nl2br(e($service->description)) !!}</h2>

        <!-- Office Information -->
        <table class="w-full border border-gray-300 rounded-lg shadow-md">
            <tbody>
                <!-- Office or Division -->
                <tr class="bg-gray-50">
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-800">Office or Division:</td>
                    <td class="border border-gray-300 px-4 py-3 text-gray-700">{{ $service->office ? $service->office->office_name : 'N/A' }}</td>
                </tr>
                <!-- Classification -->
                <tr>
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-800">Classification:</td>
                    <td class="border border-gray-300 px-4 py-3 text-gray-700">{{ $service->classification }}</td>
                </tr>
                <!-- Type of Transaction -->
                <tr class="bg-gray-50">
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-800">Type of Transaction:</td>
                    <td class="border border-gray-300 px-4 py-3 text-gray-700">{{ $service->transaction->type_of_transaction }}</td>
                </tr>
                <!-- Checklist Header -->
                <tr class="bg-gray-200">
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-900">Checklist of Requirements</td>
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-900">Where to Secure</td>
                </tr>
                <!-- Checklist Items -->
                @foreach(json_decode($service->checklist_of_requirements, true) ?? [] as $index => $checklist)
                <tr class="{{ $loop->odd ? 'bg-gray-50' : '' }}">
                    <td class="border border-gray-300 px-4 py-3 text-gray-700">{{ $checklist }}</td>
                    <td class="border border-gray-300 px-4 py-3 text-gray-700">
                        {{ json_decode($service->where_to_secure, true)[$index] ?? ($service->office ? $service->office->office_name : 'N/A') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Service Info Table -->
        <table class="w-full text-sm text-left text-gray-700 border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-gray-200 text-gray-800 uppercase font-semibold text-sm">
                <tr>
                    <th class="px-4 py-3 border border-gray-300">Info Title</th>
                    <th class="px-4 py-3 border border-gray-300">Follow These Steps</th>
                    <th class="px-4 py-3 border border-gray-300">Agency Action</th>
                    <th class="px-4 py-3 border border-gray-300">Fees</th>
                    <th class="px-4 py-3 border border-gray-300">Processing Time</th>
                    <th class="px-4 py-3 border border-gray-300">Person Responsible</th>
                    <th class="px-4 py-3 border border-gray-300 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($service->serviceInfos as $info)
                    @php
                        $clients = implode('<br>', json_decode($info->clients, true) ?? []);
                        $agencyActions = implode('<br>', json_decode($info->agency_action, true) ?? []);
                        $processingTimes = implode('<br>', json_decode($info->processing_time, true) ?? []);
                        $personsResponsible = implode('<br>', json_decode($info->person_responsible, true) ?? []);
                    @endphp
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-3 border border-gray-300">{{ $info->info_title }}</td>
                        <td class="px-4 py-3 border border-gray-300">{!! $clients !!}</td>
                        <td class="px-4 py-3 border border-gray-300">{!! $agencyActions !!}</td>
                        <td class="px-4 py-3 border border-gray-300">{{ $info->fees }}</td>
                        <td class="px-4 py-3 border border-gray-300">{!! $processingTimes !!}</td>
                        <td class="px-4 py-3 border border-gray-300">{!! $personsResponsible !!}</td>
                        <td class="px-4 py-3 border border-gray-300 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Edit Button -->
                                <button
                                    onclick="openEditModal({{ $info->id }}, '{{ $info->step }}', '{{ $info->info_title }}', `{!! $clients !!}`, `{!! $agencyActions !!}`, '{{ $info->fees }}', `{!! $processingTimes !!}`, `{!! $personsResponsible !!}`)"
                                    class="text-green-600 hover:text-green-800"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- Delete Button -->
                                <form
                                    action="{{ route('services.info.delete', ['service_id' => $service->id, 'info_id' => $info->id]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this service info?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <!-- Summary Row -->
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="3" class="px-4 py-3 border border-gray-300 text-right">Total:</td>
                    <td class="px-4 py-3 border border-gray-300">
                        @php
                            $totalFees = $service->serviceInfos->sum(fn($info) => is_numeric($info->fees) ? (float) $info->fees : 0);
                        @endphp
                        {{ $totalFees > 0 ? '₱' . number_format($totalFees, 2) : 'Depends' }}
                    </td>
                    <td colspan="3" class="px-4 py-3 border border-gray-300"></td>
                </tr>
            </tbody>
        </table>

    </div>

    <!-- Modal for Adding Service Info -->
    <div id="addServiceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Add Service Info</h2>

            <form action="{{ route('services.store') }}" method="POST">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <input type="hidden" name="office_id" value="{{ $service->office->id }}">

                <div class="mb-2">
                    <label for="step" class="block text-sm font-medium">Step</label>
                    <input type="text" id="step" name="step" class="mt-1 p-2 block w-full border rounded" required>
                </div>

                <div class="mb-2">
                    <label for="info_title" class="block text-sm font-medium">Info Title</label>
                    <input type="text" id="info_title" name="info_title" class="mt-1 p-2 block w-full border rounded" required>
                </div>

                <div class="mb-2">
                    <label for="clients" class="block text-sm font-medium">Clients</label>
                    <textarea id="clients" name="clients" class="mt-1 p-2 block w-full border rounded" required></textarea>
                </div>

                <div class="mb-2">
                    <label for="agency_action" class="block text-sm font-medium">Agency Action</label>
                    <textarea id="agency_action" name="agency_action" class="mt-1 p-2 block w-full border rounded" required></textarea>
                </div>

                <div class="mb-2">
                    <label for="fees" class="block text-sm font-medium">Fees</label>
                    <input type="text" id="fees" name="fees" class="mt-1 p-2 block w-full border rounded" required>
                </div>

                <div class="mb-2">
                    <label for="processing_time" class="block text-sm font-medium">Processing Time</label>
                    <textarea id="processing_time" name="processing_time" class="mt-1 p-2 block w-full border rounded" required></textarea>
                </div>

                <div class="mb-2">
                    <label for="person_responsible" class="block text-sm font-medium">Person Responsible</label>
                    <textarea id="person_responsible" name="person_responsible" class="mt-1 p-2 block w-full border rounded" required></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="button" class="bg-gray-300 px-4 py-2 rounded mr-2" onclick="document.getElementById('addServiceModal').style.display='none'">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Editing Service Info -->
    <div id="editServiceInfoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Edit Service Info</h2>

            <form id="editServiceInfoForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-2">
                    <label for="edit_step" class="block text-sm font-medium">Step</label>
                    <input type="text" id="edit_step" name="step" class="mt-1 p-2 block w-full border rounded" required>
                </div>

                <div class="mb-2">
                    <label for="edit_info_title" class="block text-sm font-medium">Info Title</label>
                    <input type="text" id="edit_info_title" name="info_title" class="mt-1 p-2 block w-full border rounded" required>
                </div>

                <div class="mb-2">
                    <label for="edit_clients" class="block text-sm font-medium">Clients</label>
                    <textarea id="edit_clients" name="clients" class="mt-1 p-2 block w-full border rounded" required></textarea>
                </div>

                <div class="mb-2">
                    <label for="edit_agency_action" class="block text-sm font-medium">Agency Action</label>
                    <textarea id="edit_agency_action" name="agency_action" class="mt-1 p-2 block w-full border rounded" required></textarea>
                </div>

                <div class="mb-2">
                    <label for="edit_fees" class="block text-sm font-medium">Fees</label>
                    <input type="text" id="edit_fees" name="fees" class="mt-1 p-2 block w-full border rounded" required>
                </div>

                <div class="mb-2">
                    <label for="edit_processing_time" class="block text-sm font-medium">Processing Time</label>
                    <textarea id="edit_processing_time" name="processing_time" class="mt-1 p-2 block w-full border rounded" required></textarea>
                </div>

                <div class="mb-2">
                    <label for="edit_person_responsible" class="block text-sm font-medium">Person Responsible</label>
                    <textarea id="edit_person_responsible" name="person_responsible" class="mt-1 p-2 block w-full border rounded" required></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="button" class="bg-gray-300 px-4 py-2 rounded mr-2" onclick="document.getElementById('editServiceInfoModal').style.display='none'">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modal and Form -->
    <script>
        function openEditModal(infoId, step, infoTitle, clients, agencyAction, fees, processingTime, personResponsible) {
            document.getElementById('edit_step').value = step;
            document.getElementById('edit_info_title').value = infoTitle;
            document.getElementById('edit_clients').innerHTML = clients;
            document.getElementById('edit_agency_action').innerHTML = agencyAction;
            document.getElementById('edit_fees').value = fees;
            document.getElementById('edit_processing_time').innerHTML = processingTime;
            document.getElementById('edit_person_responsible').innerHTML = personResponsible;

            document.getElementById('editServiceInfoForm').action = '/services/' + {{ $service->id }} + '/info/' + infoId;
            document.getElementById('editServiceInfoModal').style.display = 'flex';
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

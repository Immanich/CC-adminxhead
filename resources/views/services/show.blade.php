@extends('layouts.admin')

@section('content')
    <!-- Success and Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- User Section -->
    <div class="flex items-center justify-between mb-4">
        <!-- Back Button -->
        @role('admin|user|sub_user')
        <a href="{{ route('offices.showServices', $service->office_id) }}" class="bg-gray-300 text-black py-2 px-4 rounded-lg hover:bg-gray-400">
            ← Back
        </a>
        @endrole

        <!-- Office Name + Services Title -->
        <h1 class="text-2xl font-bold text-center flex-1">
            {{ $service->service_name }}
        </h1>

        <!-- Add Service Button (Visible only to Admin or user) -->
        @role('admin|user')
            <button class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600"
                    onclick="document.getElementById('addServiceModal').style.display='flex'">
                Add
            </button>
        @endrole
    </div>

    <hr class="mb-6 border-2 border-gray-300">

    <!-- Services List -->
    <div id="servicesList" class="grid grid-cols-1 gap-6">
        <h2 class="text-justify">{{ $service->description }}</h2>

        <!-- Office Information -->
        <table class="border-collapse w-full">
            <tbody>
                <tr>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">Office or Division:</td>
                    <td class="border border-black px-4 py-2">{{ $service->office ? $service->office->office_name : 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">Classification</td>
                    <td class="border border-black px-4 py-2">{{ $service->classification }}</td>
                </tr>
                <tr>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">Type of Transaction:</td>
                    <td class="border border-black px-4 py-2">{{ $service->transaction->type_of_transaction }}</td>
                </tr>
                <tr>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">CHECKLIST OF REQUIREMENTS</td>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">WHERE TO SECURE</td>
                </tr>
                @foreach(json_decode($service->checklist_of_requirements, true) ?? [] as $index => $checklist)
                    <tr>
                        <td class="border border-black px-4 py-2">{{ $checklist }}</td>
                        <td class="border border-black px-4 py-2">
                            {{ json_decode($service->where_to_secure, true)[$index] ?? ($service->office ? $service->office->office_name : 'N/A') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Service Info Table -->
        <table class="border-collapse w-full mt-1">
            <tbody>
                <tr>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">INFO TITLE</td>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">FOLLOW THESE STEPS</td>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">AGENCY ACTION</td>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">FEES TO BE PAID</td>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">PROCESSING TIME</td>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">PERSON RESPONSIBLE</td>
                    <td class="border border-black px-4 py-2 bg-blue-300 font-bold">ACTION</td>
                </tr>

                @foreach($service->serviceInfos as $info)
                    @php
                        $clients = implode('<br>', json_decode($info->clients, true) ?? []);
                        $agencyActions = implode('<br>', json_decode($info->agency_action, true) ?? []);
                        $processingTimes = implode('<br>', json_decode($info->processing_time, true) ?? []);
                        $personsResponsible = implode('<br>', json_decode($info->person_responsible, true) ?? []);
                    @endphp

                    <tr>
                        <td class="border border-black px-4 py-2">{{ $info->info_title }}</td>
                        <td class="border border-black px-4 py-2">{!! $clients !!}</td>
                        <td class="border border-black px-4 py-2">{!! $agencyActions !!}</td>
                        <td class="border border-black px-4 py-2">{{ $info->fees }}</td>
                        <td class="border border-black px-4 py-2">{!! $processingTimes !!}</td>
                        <td class="border border-black px-4 py-2">{!! $personsResponsible !!}</td>
                        <td class="border border-black px-4 py-2">
                            <!-- Edit and Delete Buttons Side by Side -->
                            <div class="flex space-x-2">
                                <button class="bg-green-500 text-white py-1 px-2 rounded-lg hover:bg-green-600"
                                        onclick="openEditModal({{ $info->id }}, '{{ $info->step }}', '{{ $info->info_title }}', `{!! $clients !!}`, `{!! $agencyActions !!}`, '{{ $info->fees }}', `{!! $processingTimes !!}`, `{!! $personsResponsible !!}`)">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form action="{{ route('services.info.delete', ['service_id' => $service->id, 'info_id' => $info->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service info?');" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded-lg hover:bg-red-600">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach

                <!-- Total Fees and Processing Time -->
                <tr>
                    <td colspan="3" class="border border-gray-300 p-2 text-right font-bold">TOTAL</td>

                    <!-- Calculate the total of numeric fees only -->
                    <td class="border border-gray-300 p-2">
                        @php
                            $totalFees = $service->serviceInfos->filter(function($info) {
                                return is_numeric($info->fees);
                            })->sum(function($info) {
                                return (float) $info->fees;
                            });
                        @endphp

                        {{ $totalFees > 0 ? number_format($totalFees, 2) : 'Depends' }}
                    </td>

                    <!-- Calculate total processing time -->
                    <td class="border border-gray-300 p-2">
                        @php
                            $totalProcessingTime = 0;

                            foreach ($service->serviceInfos as $info) {
                                $processingTimes = json_decode($info->processing_time, true);

                                foreach ($processingTimes as $time) {
                                    if (preg_match('/(\d+)/', $time, $matches)) {
                                        $totalProcessingTime += (int)$matches[1];
                                    }
                                }
                            }
                        @endphp

                        {{ $totalProcessingTime > 0 ? $totalProcessingTime . ' minutes' : 'Depends' }}
                    </td>

                    <td colspan="2" class="border border-gray-300 p-2"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal for Adding Service Info -->
    <div id="addServiceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
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
    <div id="editServiceInfoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
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
        }
    </script>
@endsection

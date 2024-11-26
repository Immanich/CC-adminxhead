@extends('layouts.admin')

@section('content')
    <div class="container bg-white p-10 rounded-lg shadow-md relative">
        <!-- Logo -->
        <div class="absolute top-4 right-4">
            {{-- <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-24 h-24"> --}}
        </div>

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-4xl font-bold text-indigo-800">M V M S P</h1>
            <div class="flex space-x-4">
                @if (empty($officeMvmsp->mandate) && empty($officeMvmsp->vision) && empty($officeMvmsp->mission) && empty($officeMvmsp->service_pledge))
                    <button id="openAddModal"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Create
                    </button>
                @else
                    <button onclick="editMvmsp({{ $officeMvmsp->id ?? 'null' }})"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Edit
                    </button>

                    <form action="{{ route('mvmsp.delete', $officeMvmsp->id ?? 0) }}" method="POST"
                        onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>


        <!-- Success/Error Messages -->
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

        <!-- MVMSP Display -->
        <ul>
            <li class="mb-6 text-xl text-justify">I. <span class="underline font-semibold text-gray-800">MANDATE</span>
                <p class="mt-2 text-gray-700 leading-relaxed">&emsp;&emsp;{{ $officeMvmsp->mandate }}</p>
            </li>
            <li class="mb-6 text-xl text-justify">II. <span class="underline font-semibold text-gray-800">VISION</span>
                <p class="mt-2 text-gray-700 leading-relaxed">&emsp;&emsp;{{ $officeMvmsp->vision }}</p>
            </li>
            <li class="mb-6 text-xl text-justify">III. <span class="underline font-semibold text-gray-800">MISSION</span>
                <p class="mt-2 text-gray-700 leading-relaxed">&emsp;&emsp;{{ $officeMvmsp->mission }}</p>
            </li>
            <li class="mb-6 text-xl text-justify">IV. <span class="underline font-semibold text-gray-800">SERVICE PLEDGE</span>
                <p class="mt-2 text-gray-700 leading-relaxed">&emsp;&emsp;{{ $officeMvmsp->service_pledge }}</p>
            </li>

            <!-- ABANTE Acronym with Vertical Alignment -->
            <div class="mt-8 text-left">
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">A</span>
                    <span class="acronym-description">ccess to enhanced</span>
                </div>
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">B</span>
                    <span class="acronym-description">asic services (Health, Education, Social Welfare & Protective Services) and other services</span>
                </div>
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">A</span>
                    <span class="acronym-description">griculture & Fisheries</span>
                </div>
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">N</span>
                    <span class="acronym-description">frastructure</span>
                </div>
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">T</span>
                    <span class="acronym-description">ourism and Culture</span>
                </div>
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">E</span>
                    <span class="acronym-description">nvironment Management & Economic Development</span>
                </div>
                <div class="town-name text-center mt-8">
                    T U B I G O N
                </div>
                <div class="slogan text-center">
                    “Onward ever, backward never”
                </div>
            </div>
        </ul>
    </div>

    <!-- Add/Edit Modal -->
    <div id="mvmspModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
            <h2 class="text-lg font-bold mb-4" id="modalTitle">Create MVMSP</h2>
            <form id="mvmspForm" action="{{ route('mvmsp.store') }}" method="POST">
                @csrf
                <input type="hidden" id="mvmspId" name="id">
                <div class="mb-4">
                    <label for="mandate" class="block text-sm font-medium">Mandate</label>
                    <textarea id="mandate" name="mandate" class="w-full border rounded p-2" rows="3" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="vision" class="block text-sm font-medium">Vision</label>
                    <textarea id="vision" name="vision" class="w-full border rounded p-2" rows="3" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="mission" class="block text-sm font-medium">Mission</label>
                    <textarea id="mission" name="mission" class="w-full border rounded p-2" rows="3" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="service_pledge" class="block text-sm font-medium">Service Pledge</label>
                    <textarea id="service_pledge" name="service_pledge" class="w-full border rounded p-2" rows="3" required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" id="submitButton"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Save
                    </button>
                    <button type="button" id="closeModal"
                        class="ml-2 bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .acronym-item {
            display: flex;
            align-items: baseline;
            margin-bottom: 10px;
        }

        .acronym-letter {
            font-size: 2rem;
            font-weight: bold;
            color: #4A1D73;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            margin-right: 5px;
        }

        .acronym-description {
            font-size: 1.2rem;
            color: #333;
        }

        .town-name {
            font-size: 2rem;
            font-weight: bold;
            color: #243F8A;
            letter-spacing: 10px;
            margin-top: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .slogan {
            color: green;
            font-style: italic;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }

        #mvmspModal {
            z-index: 1000;
        }
    </style>

    <!-- Modal Script -->
    <script>
        const openAddModal = document.getElementById('openAddModal');
        const closeModalButton = document.getElementById('closeModal');
        const modal = document.getElementById('mvmspModal');
        const form = document.getElementById('mvmspForm');

        openAddModal?.addEventListener('click', () => {
            document.getElementById('mvmspId').value = '';
            document.getElementById('mandate').value = '';
            document.getElementById('vision').value = '';
            document.getElementById('mission').value = '';
            document.getElementById('service_pledge').value = '';
            form.action = '{{ route("mvmsp.store") }}';
            document.getElementById('modalTitle').textContent = "Create MVMSP";
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        closeModalButton.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        function editMvmsp(id) {
            if (!id) {
                alert("No MVMSP data to edit.");
                return;
            }

            fetch(`/mvmsp/${id}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Failed to fetch MVMSP data.");
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('mvmspId').value = data.id;
                    document.getElementById('mandate').value = data.mandate;
                    document.getElementById('vision').value = data.vision;
                    document.getElementById('mission').value = data.mission;
                    document.getElementById('service_pledge').value = data.service_pledge;

                    form.action = `/mvmsp/${data.id}/update`;

                    const existingMethodField = form.querySelector('input[name="_method"]');
                    if (existingMethodField) {
                        existingMethodField.remove();
                    }

                    const hiddenMethod = document.createElement('input');
                    hiddenMethod.type = 'hidden';
                    hiddenMethod.name = '_method';
                    hiddenMethod.value = 'PUT';
                    form.appendChild(hiddenMethod);

                    document.getElementById('modalTitle').textContent = "Edit MVMSP";

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                })
                .catch(error => {
                    console.error(error);
                    alert("Unable to load MVMSP data for editing.");
                });
        }

        window.onload = function () {
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');

            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = "opacity 1s ease";
                    successMessage.style.opacity = "0";
                    setTimeout(() => successMessage.remove(), 1000);
                }, 2000);
            }

            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.transition = "opacity 1s ease";
                    errorMessage.style.opacity = "0";
                    setTimeout(() => errorMessage.remove(), 1000);
                }, 2000);
            }
        };
    </script>
@endsection

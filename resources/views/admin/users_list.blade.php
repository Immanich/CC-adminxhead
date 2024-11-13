@extends('layouts.admin')

@section('content')
    <!-- Success and Error Messages -->
    @if(session('success'))
    <div id="successMessage" class="bg-green-100 text-green-700 px-4 py-3 rounded relative mb-4 opacity-100 transition-opacity duration-1000 ease-in-out">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if ($errors->any())
    <div id="errorMessage" class="bg-red-100 text-red-700 p-4 rounded mb-4 opacity-100 transition-opacity duration-1000 ease-in-out">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    <div class="flex justify-between items-center mb-6">
        <h2 class="text-4xl font-bold flex-1 text-center">User Accounts</h2>
        <button type="button" id="openAddModalButton" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Create</button>
    </div>

    <table class="min-w-full bg-white border border-gray-200 rounded">
        <thead>
            <tr>
                <th class="py-3 px-6 border-b text-left">Username</th>
                <th class="py-3 px-6 border-b text-left">Office</th>
                <th class="py-3 px-6 border-b text-left">Role</th>
                @if(auth()->user()->hasRole('admin'))
                    <th class="py-3 px-6 border-b text-left">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <!-- Username with circle indicator -->
                    <td class="py-3 px-6 border-b {{ $user->is_disabled ? 'text-gray-500' : 'text-black' }}">
                        <div class="flex items-center space-x-2">
                            <!-- Circle indicating user status -->
                            <span class="w-2.5 h-2.5 rounded-full {{ $user->is_disabled ? 'bg-gray-500' : 'bg-green-500' }}"></span>
                            <span>{{ $user->username }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 border-b">{{ $user->office ? $user->office->office_name : 'N/A' }}</td>
                    <td class="py-3 px-6 border-b">{{ $user->roles->pluck('name')->implode(', ') }}</td>

                    @if(auth()->user()->hasRole('admin|user') && $user->roles->pluck('name')->implode(', ') !== 'admin')
                        <td class="py-3 px-6 border-b">
                            <div class="flex space-x-2"> <!-- Flex container to keep buttons side-by-side -->
                                <!-- Edit User Button -->
                                <button type="button" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 editUserButton"
                                        data-user="{{ $user->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete User Button -->
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900" onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                <!-- Enable/Disable Account Button -->
                                <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="text-gray-700 hover:text-white border border-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-400 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-500 dark:focus:ring-gray-900">
                                        @if($user->is_disabled)
                                            <i class="bi bi-toggle2-off"></i>
                                        @else
                                            <i class="bi bi-toggle2-on"></i>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add/Edit User Modal -->
    <div id="userModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-lg w-1/3 p-4 relative">
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Create User</h2>
            <form id="userForm" action="{{ route('admin.storeUser') }}" method="POST">
                @csrf
                <!-- This hidden input is added dynamically when editing to use PUT method -->
                <input type="hidden" name="_method" value="POST" id="methodField">
                <input type="hidden" id="userId" name="user_id" value="">

                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium">Username</label>
                    <input type="text" id="username" name="username" class="mt-1 p-2 block w-full border rounded" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 p-2 block w-full border rounded">
                    <small class="text-gray-500">Leave blank to keep current password</small>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 p-2 block w-full border rounded">
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium">Role</label>
                    <select id="role" name="role" class="mt-1 p-2 block w-full border rounded" required>
                        @if(auth()->user()->hasRole('user'))
                            <option value="sub_user">Sub User</option>
                        @else
                            @foreach($roles as $role)
                                @if($role->name !== 'admin') {{-- Exclude admin role --}}
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mb-4">
                    <label for="office_id" class="block text-sm font-medium">Office</label>
                    <select id="office_id" name="office_id" class="mt-1 p-2 block w-full border rounded" {{ auth()->user()->hasRole('user') ? 'disabled' : '' }}>
                        <option value="">Select Office</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ auth()->user()->hasRole('user') && auth()->user()->office_id == $office->id ? 'selected' : '' }}>
                                {{ $office->office_name }}
                            </option>
                        @endforeach
                    </select>
                    @if(auth()->user()->hasRole('user'))
                        <input type="hidden" name="office_id" value="{{ auth()->user()->office_id }}">
                    @endif
                </div>

                <div class="flex justify-end">
                    <button type="button" id="closeModalButton" class="mr-2 bg-gray-300 text-black px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('openAddModalButton').addEventListener('click', function () {
            document.getElementById('modalTitle').textContent = 'Create User';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('userForm').action = '{{ route('admin.storeUser') }}';
            document.getElementById('methodField').value = 'POST'; // Set form method to POST for creating
            document.getElementById('userModal').classList.remove('hidden');
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.editUserButton').forEach(function(button) {
                button.addEventListener('click', function () {
                    var userId = this.getAttribute('data-user');
                    fetch(`/admin/users/${userId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('modalTitle').textContent = 'Edit User';
                            document.getElementById('username').value = data.username;
                            document.getElementById('office_id').value = data.office_id;
                            document.getElementById('role').value = data.roles[0].name;
                            document.getElementById('userId').value = data.id;
                            document.getElementById('userForm').action = `/admin/users/${userId}`;
                            document.getElementById('methodField').value = 'PUT'; // Set form method to PUT for editing
                            document.getElementById('userModal').classList.remove('hidden');
                        });
                });
            });
        });

        document.getElementById('closeModalButton').addEventListener('click', function () {
            document.getElementById('userModal').classList.add('hidden');
        });

        // Ensure window.onload is defined once, combining both success and error message fade logic
        window.onload = function() {
            var successMessage = document.getElementById('successMessage');
            if (successMessage) {
                // Fade out the success message after 2 seconds
                setTimeout(function() {
                    successMessage.style.opacity = 0;
                }, 2000); // 2 seconds delay before starting fade-out
            }

            var errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                // Fade out the error message after 1.5 seconds
                setTimeout(function() {
                    errorMessage.style.opacity = 0;
                }, 2000); // 1.5 seconds delay before starting fade-out
            }
        };
    </script>


@endsection

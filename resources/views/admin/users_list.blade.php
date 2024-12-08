@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-center mb-6 space-x-4">
    <h2 class="text-4xl font-bold">User Accounts</h2>
    <button type="button" id="openAddModalButton" class="text-white bg-blue-700 hover:text-white border border-blue-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm w-10 h-10 flex items-center justify-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
        <i class="fa-solid fa-plus"></i>
    </button>
</div>

<!-- Success and Error Messages -->
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

<table class="min-w-full bg-white border border-gray-200 rounded">
    <thead>
        <tr>
            <th class="py-3 px-6 border-b text-left">Username</th>
            <th class="py-3 px-6 border-b text-left">Office</th>
            <th class="py-3 px-6 border-b text-left">Role</th>
            @if(auth()->user()->hasRole('admin|user'))
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
                            <button type="button"
                                    class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-green-500 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 editUserButton"
                                    data-user="{{ $user->id }}">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>

                            <!-- Delete User Button -->
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-red-500 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                        onclick="return confirm('Are you sure you want to delete this user?');">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                </button>
                            </form>

                            <!-- Enable/Disable Account Button -->
                            @if(
                            auth()->user()->hasRole('admin') ||
                            (auth()->user()->hasRole('user') && $user->office_id === auth()->user()->office_id && $user->roles->contains('name', 'sub_user'))
                        )
                            <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" class="inline" onsubmit="return confirmStatusChange({{ $user->is_disabled ? 'true' : 'false' }})">
                                @csrf
                                @method('POST')
                                <button type="submit"
                                        class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-gray-500 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-900">
                                    @if($user->is_disabled)
                                        <i class="bi bi-toggle2-off mr-1"></i> Activate
                                    @else
                                        <i class="bi bi-toggle2-on mr-1"></i> Deactivate
                                    @endif
                                </button>
                            </form>
                        @endif
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
                <input type="text" id="username" name="username" class="mt-1 p-2 block w-full border rounded" placeholder="Enter username" required>
            </div>

            <!-- Password Change Field for Editing -->
            <div id="passwordChangeField" class="mb-4 hidden">
                <label for="changePassword" class="block text-sm font-medium">Password Change</label>
                <select id="changePassword" name="changePassword" class="mt-1 p-2 block w-full border rounded" required>
                    <option value="keep">Keep Current Password</option>
                    <option value="edit">Edit Password</option>
                </select>
            </div>

            <!-- Password Fields (Only show when 'Edit Password' is selected) -->
            <div id="passwordFields" class="mb-4 hidden">
                <label for="password" class="block text-sm font-medium">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" class="mt-1 p-2 block w-full border rounded pr-10" placeholder="Enter password">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-2 flex items-center">
                        <i class="fas fa-eye text-gray-500" id="passwordIcon"></i>
                    </button>
                </div>
            </div>

            <div id="passwordConfirmationField" class="mb-4 hidden">
                <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 p-2 block w-full border rounded pr-10" placeholder="Retype your password">
                    <button type="button" id="togglePasswordConfirmation" class="absolute inset-y-0 right-2 flex items-center">
                        <i class="fas fa-eye text-gray-500" id="passwordConfirmationIcon"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium">Role</label>
                <select id="role" name="role" class="mt-1 p-2 block w-full border rounded" required>
                    @if(auth()->user()->hasRole('user'))
                        <option value="sub_user">Sub User</option>
                        <option value="user"> User</option>
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
    document.getElementById('passwordFields').classList.remove('hidden'); // Show password fields when creating
    document.getElementById('passwordConfirmationField').classList.remove('hidden'); // Show confirm password when creating
    document.getElementById('passwordChangeField').classList.add('hidden'); // Hide the password change field
    document.getElementById('userModal').classList.remove('hidden');
});

function confirmStatusChange(isDisabled) {
        var message = isDisabled ? 'Are you sure you want to activate this user?' : 'Are you sure you want to deactivate this user?';
        return confirm(message);
    }

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
                    document.getElementById('passwordFields').classList.add('hidden'); // Hide password fields initially
                    document.getElementById('passwordConfirmationField').classList.add('hidden'); // Hide confirm password initially
                    document.getElementById('passwordChangeField').classList.remove('hidden'); // Show password change field
                    document.getElementById('userModal').classList.remove('hidden');
                });
        });
    });
});

document.getElementById('closeModalButton').addEventListener('click', function () {
    document.getElementById('userModal').classList.add('hidden');
});

document.getElementById('changePassword').addEventListener('change', function() {
    const passwordFields = document.getElementById('passwordFields');
    const passwordConfirmationField = document.getElementById('passwordConfirmationField');
    if (this.value === 'edit') {
        passwordFields.classList.remove('hidden');
        passwordConfirmationField.classList.remove('hidden');
    } else {
        passwordFields.classList.add('hidden');
        passwordConfirmationField.classList.add('hidden');
    }
});


    // Toggle Password Visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const passwordIcon = document.getElementById('passwordIcon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordField.type = 'password';
            passwordIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });

    document.getElementById('togglePasswordConfirmation').addEventListener('click', function () {
        const passwordConfirmationField = document.getElementById('password_confirmation');
        const passwordConfirmationIcon = document.getElementById('passwordConfirmationIcon');
        if (passwordConfirmationField.type === 'password') {
            passwordConfirmationField.type = 'text';
            passwordConfirmationIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordConfirmationField.type = 'password';
            passwordConfirmationIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });


        // Ensure window.onload is defined once, combining both success and error message fade logic
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

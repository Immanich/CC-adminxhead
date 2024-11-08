@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">User Accounts</h2>
        <button id="openAddModalButton" class="bg-blue-500 text-white px-4 py-2 rounded">Add User</button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

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
                    <td class="py-3 px-6 border-b">{{ $user->username }}</td>
                    <td class="py-3 px-6 border-b">{{ $user->office ? $user->office->office_name : 'N/A' }}</td>
                    <td class="py-3 px-6 border-b">{{ $user->roles->pluck('name')->implode(', ') }}</td>

                    @if(auth()->user()->hasRole('admin') && $user->roles->pluck('name')->implode(', ') !== 'admin')
                        <td class="py-3 px-6 border-b">
                            <div class="flex space-x-2"> <!-- Flex container to keep buttons side-by-side -->
                                <button class="bg-green-500 text-white px-4 py-2 rounded editUserButton"
                                        data-user="{{ $user->id }}"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded"
                                            onclick="return confirm('Are you sure you want to delete this user?');">
                                            <i class="fas fa-trash-alt"></i>
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
                        @foreach($roles as $role)
                            @if($role->name !== 'admin') {{-- Exclude admin role --}}
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="office_id" class="block text-sm font-medium">Office</label>
                    <select id="office_id" name="office_id" class="mt-1 p-2 block w-full border rounded">
                        <option value="">Select Office</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                        @endforeach
                    </select>
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
    </script>
@endsection

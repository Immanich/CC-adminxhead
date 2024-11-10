<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index()
{
    // Retrieve all users and sort them by 'is_disabled' (false first, then true for disabled users)
    $users = User::with('roles', 'office')
                 ->orderBy('is_disabled', 'asc')  // 'asc' will place active users first, disabled users at the bottom
                 ->get();

    $roles = Role::all();
    $offices = Office::all();

    return view('admin.users_list', compact('users', 'roles', 'offices'));
}


public function store(Request $request)
{
    // Custom error messages for username and password
    $messages = [
        'username.unique' => 'The selected username already exists. Please choose a different username.',
        'username.required' => 'Username is required.',
        'username.max' => 'Username is too long. Please choose a username with less than 255 characters.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters long.',
        'password.confirmed' => 'Passwords do not match.',
    ];

    // Validation rules focusing on username and password
    $validatedData = $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . $request->user_id,
        'password' => $request->user_id ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
    ], $messages);

    // Create or update the user
    $user = User::updateOrCreate(
        ['id' => $request->user_id],
        [
            'username' => $validatedData['username'],
            'password' => $validatedData['password'] ? Hash::make($validatedData['password']) : null,
        ]
    );

    // Assign the role (this assumes that you are handling roles separately)
    if ($request->has('role')) {
        $user->syncRoles([$request->role]);
    }

    // If the user role is 'user', we can set the office_id as well, assuming that behavior
    if ($request->role === 'user' && $request->has('office_id')) {
        $user->office_id = $request->office_id;
    }

    $user->save();

    return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
}


    // Add the update method to handle PUT/PATCH requests
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'office_id' => 'nullable|exists:offices,id',
        ]);

        $user = User::findOrFail($id);
        $user->username = $validatedData['username'];

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->syncRoles([$validatedData['role']]);
        $user->office_id = $validatedData['role'] === 'user' ? $validatedData['office_id'] : null;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function edit($id)
    {
        $user = User::with('roles', 'office')->findOrFail($id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Check if the logged-in user is an admin
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.users.index')->with('error', 'You are not authorized to perform this action.');
        }

        // Toggle the disabled status
        $user->is_disabled = !$user->is_disabled;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User status updated successfully.');
    }


}

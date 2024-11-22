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
    // If the user is an admin, show all users; otherwise, filter by office
    if (auth()->user()->hasRole('admin')) {
        $users = User::with('roles', 'office')->orderBy('is_disabled', 'asc')->get();
    } else {
        // Get only users from the same office as the logged-in user and sub-users they created
        $users = User::where('office_id', auth()->user()->office_id)
                     ->orWhere('created_by', auth()->id())  // Include sub-users created by this user
                     ->with('roles', 'office')
                     ->orderBy('is_disabled', 'asc')
                     ->get();
    }

    // Show roles based on user's role: admin sees all roles; user sees only 'sub_user'
    $roles = auth()->user()->hasRole('admin') ? Role::all() : Role::where('name', 'sub_user')->get();
    $offices = Office::all();

    return view('admin.users_list', compact('users', 'roles', 'offices'));
}




public function store(Request $request)
{
    // Custom error messages for validation
    $messages = [
        'username.unique' => 'The selected username already exists. Please choose a different username.',
        'username.required' => 'Username is required.',
        'username.max' => 'Username is too long. Please choose a username with less than 255 characters.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters long.',
        'password.confirmed' => 'Passwords do not match.',
        'office_id.required' => 'Please assign the user to an office.',
    ];

    // Validate the request
    $validatedData = $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . $request->user_id,
        'password' => $request->user_id ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
        'office_id' => 'required|exists:offices,id',
    ], $messages);

    // Determine if the current user is creating a sub-user
    $isSubUser = auth()->user()->hasRole('user') && !$request->has('user_id');

    // Prepare user data, setting `created_by` and `office_id` based on whether it's a sub-user
    $userData = [
        'username' => $validatedData['username'],
        'password' => $validatedData['password'] ? Hash::make($validatedData['password']) : null,
        'created_by' => $isSubUser ? auth()->id() : null,
        'office_id' => $isSubUser ? auth()->user()->office_id : $request->office_id,
    ];

    // Create or update the user
    $user = User::updateOrCreate(['id' => $request->user_id], $userData);

    // Assign the appropriate role
    if ($isSubUser) {
        $user->syncRoles(['sub_user']); // Assign 'sub_user' role if created by a non-admin user
    } elseif ($request->has('role')) {
        $user->syncRoles([$request->role]);
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
        'office_id' => 'nullable|exists:offices,id', // Ensure office_id can be nullable and exists
    ]);

    $user = User::findOrFail($id);
    $user->username = $validatedData['username'];

    // Update password if provided
    if (!empty($validatedData['password'])) {
        $user->password = Hash::make($validatedData['password']);
    }

    // Sync role
    $user->syncRoles([$validatedData['role']]);

    // Always set office_id if provided, regardless of role
    $user->office_id = $validatedData['office_id'];
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

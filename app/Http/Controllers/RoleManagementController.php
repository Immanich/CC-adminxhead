<?php

// app/Http/Controllers/RoleManagementController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    public function createUser()
    {
        $roles = Role::whereIn('name', ['user', 'sub_user'])->get();
        $offices = Office::all();  // Get all offices for selection
        return view('admin.create_user', compact('roles', 'offices'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'office_id' => 'required_if:role,user|exists:offices,id', // Office is required for users
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'office_id' => $request->office_id,  // Assign office
        ]);

        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'User created successfully.');
    }
}

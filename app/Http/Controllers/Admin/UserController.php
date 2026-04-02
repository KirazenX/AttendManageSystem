<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['department', 'roles']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $user = new User();
        $departments = Department::where('is_active', true)->get();
        $roles = Role::all();
        return view('admin.users.form', compact('user', 'departments', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8',
            'employee_id'   => 'nullable|string|unique:users,employee_id',
            'department_id' => 'nullable|exists:departments,id',
            'role'          => 'required|exists:roles,name',
            'is_active'     => 'boolean',
        ]);

        $data['password'] = Hash::make($data['password']);
        $roleName = $data['role'];
        unset($data['role']);

        $user = User::create($data);
        $user->assignRole($roleName);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $departments = Department::where('is_active', true)->get();
        $roles = Role::all();
        return view('admin.users.form', compact('user', 'departments', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'employee_id'   => 'nullable|string|unique:users,employee_id,' . $user->id,
            'department_id' => 'nullable|exists:departments,id',
            'role'          => 'required|exists:roles,name',
            'is_active'     => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $roleName = $data['role'];
        unset($data['role']);

        $user->update($data);
        $user->syncRoles([$roleName]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['message' => 'You cannot delete yourself.']);
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}

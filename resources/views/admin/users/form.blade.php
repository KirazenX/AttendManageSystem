@extends('layouts.app')
@section('title', $user->exists ? 'Edit Employee' : 'Add Employee')
@section('page-title', $user->exists ? 'Edit Employee' : 'Add Employee')
@section('page-subtitle', $user->exists ? 'Update employee information and role' : 'Create a new employee account')

@section('content')
<div class="max-w-2xl">
<div class="bg-slate-900 border border-slate-800 rounded-xl p-6">

    @if($errors->any())
    <div class="bg-red-950/50 border border-red-800/50 text-red-300 text-sm px-4 py-3 rounded-lg mb-6">
        <ul class="space-y-1 list-disc list-inside">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" enctype="multipart/form-data">
        @csrf
        @if($user->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                       class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500 transition-colors"
                       placeholder="John Doe">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Employee ID</label>
                <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id ?? '') }}"
                       class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500 font-mono"
                       placeholder="EMP001">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Email <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                       class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500"
                       placeholder="john@company.com">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                       class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500"
                       placeholder="+62 812 3456 7890">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Password {{ $user->exists ? '(leave blank to keep)' : '' }} @if(!$user->exists) <span class="text-red-400">*</span> @endif</label>
                <input type="password" name="password" {{ !$user->exists ? 'required' : '' }}
                       class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500"
                       placeholder="Min 8 characters">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500"
                       placeholder="Repeat password">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Department</label>
                <select name="department_id" class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
                    <option value="">No Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Role</label>
                <select name="role" class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name ?? 'employee') === $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Gender</label>
                <select name="gender" class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
                    <option value="">Not specified</option>
                    <option value="male" {{ old('gender', $user->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $user->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Join Date</label>
                <input type="date" name="join_date" value="{{ old('join_date', $user->join_date?->format('Y-m-d') ?? '') }}"
                       class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
            </div>
            @if($user->exists)
            <div class="sm:col-span-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-10 h-5 bg-slate-700 rounded-full peer-checked:bg-brand-500 transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm text-slate-300">Account is active</span>
                </label>
            </div>
            @endif
        </div>

        <div class="flex gap-3 pt-4 border-t border-slate-800">
            <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                {{ $user->exists ? 'Update Employee' : 'Create Employee' }}
            </button>
            <a href="{{ route('admin.users.index') }}" class="border border-slate-700 text-slate-400 hover:text-slate-200 hover:bg-slate-800 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
</div>
@endsection
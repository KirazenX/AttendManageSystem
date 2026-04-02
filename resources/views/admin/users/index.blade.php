@extends('layouts.app')
@section('title','Employees')
@section('page-title','Employee Management')
@section('page-subtitle','Manage all employee accounts and roles')

@section('content')

<div class="flex items-center justify-between mb-5">
    <form method="GET" class="flex gap-2 flex-1 max-w-lg">
        <div class="flex-1 relative">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, employee ID…"
                   class="w-full bg-slate-900 border border-slate-800 text-slate-200 text-sm rounded-xl pl-9 pr-4 py-2.5 focus:outline-none focus:border-brand-500">
        </div>
        <select name="role" class="bg-slate-900 border border-slate-800 text-slate-300 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-500">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="hr" {{ request('role') === 'hr' ? 'selected' : '' }}>HR</option>
            <option value="employee" {{ request('role') === 'employee' ? 'selected' : '' }}>Employee</option>
        </select>
        <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">Search</button>
    </form>
    <a href="{{ route('admin.users.create') }}"
       class="ml-4 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Employee
    </a>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-800/50">
            <tr class="text-left text-xs text-slate-500">
                <th class="px-5 py-3 font-medium">Employee</th>
                <th class="px-5 py-3 font-medium">ID</th>
                <th class="px-5 py-3 font-medium">Department</th>
                <th class="px-5 py-3 font-medium">Role</th>
                <th class="px-5 py-3 font-medium">Join Date</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/50">
        @forelse($users as $user)
        <tr class="table-row-hover">
            <td class="px-5 py-3">
                <div class="flex items-center gap-3">
                    @if($user->avatar)
                        <img src="{{ asset('storage/'.$user->avatar) }}" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-brand-600/25 flex items-center justify-center text-xs font-bold text-brand-300">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <div class="font-medium text-slate-200">{{ $user->name }}</div>
                        <div class="text-xs text-slate-500">{{ $user->email }}</div>
                    </div>
                </div>
            </td>
            <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $user->employee_id ?? '—' }}</td>
            <td class="px-5 py-3 text-slate-400 text-xs">{{ $user->department?->name ?? '—' }}</td>
            <td class="px-5 py-3">
                @php $role = $user->roles->first()?->name ?? 'employee'; @endphp
                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $role === 'admin' ? 'bg-purple-500/15 text-purple-400' :
                       ($role === 'hr' ? 'bg-blue-500/15 text-blue-400' : 'bg-slate-700 text-slate-400') }}">
                    {{ ucfirst($role) }}
                </span>
            </td>
            <td class="px-5 py-3 text-slate-500 text-xs font-mono">{{ $user->join_date?->format('d M Y') ?? '—' }}</td>
            <td class="px-5 py-3">
                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $user->is_active ? 'bg-emerald-500/15 text-emerald-400' : 'bg-red-500/15 text-red-400' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </td>
            <td class="px-5 py-3">
                <div class="flex items-center gap-1">
                    <a href="{{ route('admin.users.show', $user) }}"
                       class="p-1.5 rounded-lg text-slate-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="p-1.5 rounded-lg text-slate-500 hover:text-yellow-400 hover:bg-yellow-500/10 transition-colors">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </a>
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete {{ $user->name }}?')">
                        @csrf @method('DELETE')
                        <button class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="px-5 py-12 text-center text-slate-600">No employees found</td></tr>
        @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-slate-800 flex items-center justify-between text-xs text-slate-500">
        <span>{{ $users->total() }} employees · Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
        <div class="flex gap-2">
            @if(!$users->onFirstPage())
                <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">← Prev</a>
            @endif
            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">Next →</a>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
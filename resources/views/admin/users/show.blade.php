@extends('layouts.app')
@section('title', $user->name . ' - Employee Details')
@section('page-title', 'Employee Profile')
@section('page-subtitle', 'Detailed information and history for ' . $user->name)

@section('content')
<div class="mb-5 flex items-center justify-between">
    <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-white transition-colors flex items-center gap-2 text-sm font-medium">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Employees
    </a>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-400 px-4 py-2 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2 border border-yellow-500/20">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit Employee
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left Column: Basic Info --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col items-center text-center">
            @if($user->avatar)
                <img src="{{ asset('storage/'.$user->avatar) }}" class="w-24 h-24 rounded-full object-cover mb-4 ring-4 ring-slate-800">
            @else
                <div class="w-24 h-24 rounded-full bg-brand-600/20 flex items-center justify-center text-3xl font-bold text-brand-400 mb-4 border border-brand-500/20">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            @endif
            <h2 class="text-xl font-bold text-white mb-1">{{ $user->name }}</h2>
            <p class="text-slate-500 text-sm mb-4 font-mono">{{ $user->employee_id ?? 'NO-ID' }}</p>
            
            <div class="flex flex-wrap justify-center gap-2 mb-6">
                @php $role = $user->roles->first()?->name ?? 'employee'; @endphp
                <span class="text-xs px-3 py-1 rounded-full font-semibold
                    {{ $role === 'admin' ? 'bg-purple-500/15 text-purple-400 border border-purple-500/20' :
                       ($role === 'hr' ? 'bg-blue-500/15 text-blue-400 border border-blue-500/20' : 'bg-slate-800 text-slate-400 border border-slate-700') }}">
                    {{ ucfirst($role) }}
                </span>
                <span class="text-xs px-3 py-1 rounded-full font-semibold
                    {{ $user->is_active ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/15 text-red-400 border border-red-500/20' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="w-full space-y-3 pt-6 border-t border-slate-800">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Department</span>
                    <span class="text-slate-200 font-medium">{{ $user->department?->name ?? 'Unassigned' }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Join Date</span>
                    <span class="text-slate-200 font-medium font-mono">{{ $user->join_date?->format('d M Y') ?? '—' }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Gender</span>
                    <span class="text-slate-200 font-medium">{{ ucfirst($user->gender ?? '—') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Contact Information</h3>
            <div class="space-y-4">
                <div>
                    <div class="text-xs text-slate-500 mb-1">Email Address</div>
                    <div class="text-sm text-slate-200 flex items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-brand-500"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        {{ $user->email }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-slate-500 mb-1">Phone Number</div>
                    <div class="text-sm text-slate-200 flex items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-emerald-500"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        {{ $user->phone ?? '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Detailed Info & Activities --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Work Schedule Card --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h3 class="text-base font-semibold text-white mb-4">Current Work Schedule</h3>
            @php $activeSchedule = $user->activeSchedule(); @endphp
            @if($activeSchedule)
                <div class="bg-slate-800/50 rounded-xl p-4 border border-slate-700/50">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-sm font-medium text-slate-200">{{ $activeSchedule->workShift->name }}</div>
                            <div class="text-xs text-slate-500">Effective since {{ $activeSchedule->effective_date->format('d M Y') }}</div>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 font-medium border border-emerald-500/10">Active</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-wider mb-1">Check In</div>
                            <div class="text-sm font-mono text-slate-300 font-semibold">{{ $activeSchedule->workShift->start_time->format('H:i') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-wider mb-1">Check Out</div>
                            <div class="text-sm font-mono text-slate-300 font-semibold">{{ $activeSchedule->workShift->end_time->format('H:i') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-wider mb-1">Grace Period</div>
                            <div class="text-sm font-mono text-slate-300 font-semibold">{{ $activeSchedule->workShift->grace_period }}m</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-wider mb-1">Shift Type</div>
                            <div class="text-sm text-slate-300 font-semibold">{{ ucfirst($activeSchedule->workShift->type) }}</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="py-8 text-center bg-slate-800/20 border border-dashed border-slate-700 rounded-xl">
                    <p class="text-slate-500 text-sm">No active work schedule assigned.</p>
                </div>
            @endif
        </div>

        {{-- Recent Attendance Card --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-semibold text-white">Recent Attendance</h3>
                <span class="text-xs text-slate-500">Last 5 records</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-800/50">
                        <tr class="text-left text-xs text-slate-500">
                            <th class="px-6 py-3 font-medium">Date</th>
                            <th class="px-6 py-3 font-medium">Time In</th>
                            <th class="px-6 py-3 font-medium">Time Out</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @php $recentAttendances = $user->attendances()->orderByDesc('attendance_date')->limit(5)->get(); @endphp
                        @forelse($recentAttendances as $att)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-3 font-mono text-xs text-slate-300">{{ $att->attendance_date->format('d M Y') }}</td>
                            <td class="px-6 py-3 font-mono text-xs {{ $att->late_minutes > 0 ? 'text-yellow-400' : 'text-slate-400' }}">
                                {{ $att->check_in_time?->format('H:i') ?? '—' }}
                            </td>
                            <td class="px-6 py-3 font-mono text-xs text-slate-400">{{ $att->check_out_time?->format('H:i') ?? '—' }}</td>
                            <td class="px-6 py-3">
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter badge-{{ $att->status }}">
                                    {{ $att->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-600">No attendance records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

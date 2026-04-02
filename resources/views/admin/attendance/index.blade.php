@extends('layouts.app')
@section('title','Attendance')
@section('page-title','Attendance Management')
@section('page-subtitle','Monitor and manage employee attendance records')

@section('content')

{{-- Filters --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-slate-500 mb-1.5">Month</label>
            <select name="month" class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-brand-500">
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0,0,0,$m,1)) }}
                    </option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-xs text-slate-500 mb-1.5">Year</label>
            <select name="year" class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-brand-500">
                @for($y=now()->year;$y>=now()->year-2;$y--)
                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-xs text-slate-500 mb-1.5">Status</label>
            <select name="status" class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-brand-500">
                <option value="">All Status</option>
                @foreach(['present','late','absent','leave'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-slate-500 mb-1.5">Department</label>
            <select name="department_id" class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-brand-500">
                <option value="">All Departments</option>
                @foreach(\App\Models\Department::where('is_active',true)->get() as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white text-sm px-4 py-2 rounded-lg font-medium transition-colors">
            Apply Filters
        </button>
        <a href="{{ route('admin.attendance.index') }}" class="text-slate-400 hover:text-slate-200 text-sm px-3 py-2 rounded-lg transition-colors">Reset</a>
    </form>
</div>

{{-- Table --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
        <span class="text-sm font-medium text-slate-300">{{ $attendances->total() }} records found</span>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-800/50">
            <tr class="text-left text-xs text-slate-500">
                <th class="px-5 py-3 font-medium">Employee</th>
                <th class="px-5 py-3 font-medium">Date</th>
                <th class="px-5 py-3 font-medium">Check In</th>
                <th class="px-5 py-3 font-medium">Check Out</th>
                <th class="px-5 py-3 font-medium">Duration</th>
                <th class="px-5 py-3 font-medium">Late</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">GPS</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/50">
            @forelse($attendances as $att)
            <tr class="table-row-hover">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-brand-600/25 flex items-center justify-center text-xs font-bold text-brand-300">
                            {{ strtoupper(substr($att->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="text-slate-200 font-medium">{{ $att->user->name }}</div>
                            <div class="text-xs text-slate-600">{{ $att->user->department?->name ?? 'No dept' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3 text-slate-400 font-mono text-xs">{{ $att->attendance_date?->format('d M Y') }}</td>
                <td class="px-5 py-3 text-slate-300 font-mono text-xs">{{ $att->check_in_time?->format('H:i') ?? '—' }}</td>
                <td class="px-5 py-3 text-slate-300 font-mono text-xs">{{ $att->check_out_time?->format('H:i') ?? '—' }}</td>
                <td class="px-5 py-3 text-slate-400 text-xs">{{ $att->working_minutes ? round($att->working_minutes/60,1).'h' : '—' }}</td>
                <td class="px-5 py-3 text-xs {{ $att->late_minutes > 0 ? 'text-yellow-400' : 'text-slate-600' }}">
                    {{ $att->late_minutes > 0 ? $att->late_minutes.'m' : '—' }}
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium badge-{{ $att->status }}">
                        {{ ucfirst($att->status) }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    @if($att->check_in_gps_valid)
                        <span class="text-xs text-emerald-400 flex items-center gap-1">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            Valid
                        </span>
                    @else
                        <span class="text-xs text-slate-600">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-5 py-12 text-center text-slate-600">No records found for the selected filters</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($attendances->hasPages())
    <div class="px-5 py-4 border-t border-slate-800 flex items-center justify-between text-xs text-slate-500">
        <span>Page {{ $attendances->currentPage() }} of {{ $attendances->lastPage() }}</span>
        <div class="flex gap-2">
            @if($attendances->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-600 cursor-not-allowed">← Prev</span>
            @else
                <a href="{{ $attendances->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">← Prev</a>
            @endif
            @if($attendances->hasMorePages())
                <a href="{{ $attendances->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">Next →</a>
            @else
                <span class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-600 cursor-not-allowed">Next →</span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
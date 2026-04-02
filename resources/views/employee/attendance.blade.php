@extends('layouts.app')
@section('title','My Attendance')
@section('page-title','My Attendance')
@section('page-subtitle','Your personal attendance history and records')

@section('content')
@php $user = auth()->user(); @endphp

{{-- Month selector --}}
<div class="flex items-center gap-3 mb-5">
    <form method="GET" class="flex gap-2 items-center">
        <select name="month" class="bg-slate-900 border border-slate-800 text-slate-300 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-brand-500">
            @for($m=1;$m<=12;$m++)
                <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
            @endfor
        </select>
        <select name="year" class="bg-slate-900 border border-slate-800 text-slate-300 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-brand-500">
            @for($y=now()->year;$y>=now()->year-1;$y--)
                <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">View</button>
    </form>
</div>

{{-- Monthly summary --}}
<div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-3 mb-5">
    @foreach(['present'=>['#10b981','Present'], 'late'=>['#eab308','Late'], 'absent'=>['#ef4444','Absent'], 'leave'=>['#3b82f6','Leave']] as $s => [$color, $label])
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
        <div class="text-2xl font-semibold mb-1" style="color:{{ $color }}">{{ $stats[$s]->count ?? 0 }}</div>
        <div class="text-xs text-slate-500">{{ $label }}</div>
    </div>
    @endforeach
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
        <div class="text-2xl font-semibold mb-1 text-brand-400">{{ round($totalMinutes / 60, 1) }}</div>
        <div class="text-xs text-slate-500">Hours worked</div>
    </div>
</div>

{{-- Records table --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-800">
        <h3 class="text-sm font-semibold text-white">{{ date('F Y', mktime(0,0,0,$month,1,$year)) }} Records</h3>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-800/50">
            <tr class="text-left text-xs text-slate-500">
                <th class="px-5 py-3 font-medium">Date</th>
                <th class="px-5 py-3 font-medium">Day</th>
                <th class="px-5 py-3 font-medium">Check In</th>
                <th class="px-5 py-3 font-medium">Check Out</th>
                <th class="px-5 py-3 font-medium">Hours</th>
                <th class="px-5 py-3 font-medium">Late</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">GPS</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/50">
        @forelse($attendances as $att)
        <tr class="table-row-hover {{ $att->attendance_date?->isWeekend() ? 'opacity-60' : '' }}">
            <td class="px-5 py-3 text-slate-300 font-mono text-xs">{{ $att->attendance_date?->format('d M Y') }}</td>
            <td class="px-5 py-3 text-slate-500 text-xs">{{ $att->attendance_date?->format('D') }}</td>
            <td class="px-5 py-3 font-mono text-xs {{ $att->late_minutes > 0 ? 'text-yellow-400' : 'text-slate-300' }}">
                {{ $att->check_in_time?->format('H:i') ?? '—' }}
            </td>
            <td class="px-5 py-3 font-mono text-xs text-slate-300">{{ $att->check_out_time?->format('H:i') ?? '—' }}</td>
            <td class="px-5 py-3 text-slate-400 text-xs">{{ $att->working_minutes ? round($att->working_minutes/60,1).'h' : '—' }}</td>
            <td class="px-5 py-3 text-xs {{ $att->late_minutes > 0 ? 'text-yellow-400' : 'text-slate-600' }}">
                {{ $att->late_minutes > 0 ? $att->late_minutes.'m late' : '—' }}
            </td>
            <td class="px-5 py-3">
                <span class="text-xs px-2 py-0.5 rounded-full font-medium badge-{{ $att->status }}">{{ ucfirst($att->status) }}</span>
            </td>
            <td class="px-5 py-3">
                @if($att->check_in_gps_valid)
                    <span class="text-xs text-emerald-400">✓ {{ $att->check_in_distance_meters }}m</span>
                @elseif($att->check_in_time)
                    <span class="text-xs text-red-400">✗ Invalid</span>
                @else
                    <span class="text-xs text-slate-700">—</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="px-5 py-12 text-center text-slate-600">No records for this month</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    @if($attendances->hasPages())
    <div class="px-5 py-4 border-t border-slate-800 flex items-center justify-between text-xs text-slate-500">
        <span>{{ $attendances->total() }} records</span>
        {{ $attendances->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
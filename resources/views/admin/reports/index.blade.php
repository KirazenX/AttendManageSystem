@extends('layouts.app')
@section('title','Reports')
@section('page-title','Attendance Reports')
@section('page-subtitle','Monthly summary and analytics')

@section('content')

{{-- Filters --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-slate-500 mb-1.5">Month</label>
            <select name="month" class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2">
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-xs text-slate-500 mb-1.5">Year</label>
            <select name="year" class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2">
                @for($y=now()->year;$y>=now()->year-2;$y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white text-sm px-4 py-2 rounded-lg font-medium transition-colors">Generate</button>
    </form>
</div>

{{-- Monthly totals --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
    @foreach(['present'=>['#10b981','Total Present'],'late'=>['#eab308','Total Late'],'absent'=>['#ef4444','Total Absent'],'leave'=>['#3b82f6','On Leave']] as $s=>[$c,$l])
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <div class="text-3xl font-semibold mb-1" style="color:{{ $c }}">{{ $totals[$s] ?? 0 }}</div>
        <div class="text-xs text-slate-500">{{ $l }}</div>
    </div>
    @endforeach
</div>

{{-- Per-employee breakdown --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-white">Employee Summary — {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</h3>
        <a href="{{ route('admin.reports.export', ['month'=>$month,'year'=>$year]) }}"
           class="flex items-center gap-2 text-xs bg-emerald-600/15 hover:bg-emerald-600/25 text-emerald-400 border border-emerald-800/40 px-3 py-1.5 rounded-lg transition-colors font-medium">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export CSV
        </a>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-800/40">
            <tr class="text-left text-xs text-slate-500">
                <th class="px-5 py-3 font-medium">Employee</th>
                <th class="px-5 py-3 font-medium">Department</th>
                <th class="px-5 py-3 font-medium text-center">Present</th>
                <th class="px-5 py-3 font-medium text-center">Late</th>
                <th class="px-5 py-3 font-medium text-center">Absent</th>
                <th class="px-5 py-3 font-medium text-center">Leave</th>
                <th class="px-5 py-3 font-medium text-center">Hours</th>
                <th class="px-5 py-3 font-medium">Attendance</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/50">
        @foreach($employees as $emp)
        @php
            $empStats = $emp->attendances->groupBy('status');
            $presentCount = ($empStats->get('present')?->count() ?? 0) + ($empStats->get('late')?->count() ?? 0);
            $lateCount    = $empStats->get('late')?->count() ?? 0;
            $absentCount  = $empStats->get('absent')?->count() ?? 0;
            $leaveCount   = $empStats->get('leave')?->count() ?? 0;
            $hrs          = round($emp->attendances->sum('working_minutes') / 60, 1);
            
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $workDays = 0;
            for($d = 1; $d <= $daysInMonth; $d++) {
                $dayOfWeek = date('N', mktime(0, 0, 0, $month, $d, $year));
                if($dayOfWeek < 6) $workDays++;
            }
            $attRate = $workDays > 0 ? round(($presentCount / $workDays) * 100) : 0;
        @endphp
        <tr class="table-row-hover">
            <td class="px-5 py-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-brand-600/20 flex items-center justify-center text-xs font-bold text-brand-300">
                        {{ strtoupper(substr($emp->name,0,2)) }}
                    </div>
                    <div>
                        <div class="text-slate-200 font-medium text-xs">{{ $emp->name }}</div>
                        <div class="text-slate-600 text-xs font-mono">{{ $emp->employee_id ?? '—' }}</div>
                    </div>
                </div>
            </td>
            <td class="px-5 py-3 text-slate-500 text-xs">{{ $emp->department?->name ?? '—' }}</td>
            <td class="px-5 py-3 text-center text-emerald-400 font-semibold text-sm">{{ $presentCount }}</td>
            <td class="px-5 py-3 text-center text-yellow-400 text-sm">{{ $lateCount }}</td>
            <td class="px-5 py-3 text-center text-red-400 text-sm">{{ $absentCount }}</td>
            <td class="px-5 py-3 text-center text-blue-400 text-sm">{{ $leaveCount }}</td>
            <td class="px-5 py-3 text-center text-slate-300 text-sm">{{ $hrs }}h</td>
            <td class="px-5 py-3">
                <div class="flex items-center gap-2">
                    <div class="flex-1 h-1.5 bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $attRate >= 80 ? 'bg-emerald-500' : ($attRate >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                             style="width:{{ $attRate }}%"></div>
                    </div>
                    <span class="text-xs text-slate-500 w-9 text-right">{{ $attRate }}%</span>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
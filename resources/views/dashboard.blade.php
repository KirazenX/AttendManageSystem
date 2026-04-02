@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-subtitle', now()->format('l, F j, Y'))

@section('content')
@php $role = auth()->user()->roles->first()?->name ?? 'employee'; @endphp

{{-- ───── ADMIN / HR DASHBOARD ───── --}}
@if(in_array($role, ['admin','hr']))

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    @php
        $today = now()->toDateString();
        $totalEmp = \App\Models\User::where('is_active',true)->count();
        $presentToday = \App\Models\Attendance::where('attendance_date',$today)->whereIn('status',['present','late'])->count();
        $lateToday    = \App\Models\Attendance::where('attendance_date',$today)->where('status','late')->count();
        $absentToday  = max(0, $totalEmp - $presentToday);
        $pendingLeaves= \App\Models\LeaveRequest::where('status','pending')->count();
        $rate = $totalEmp > 0 ? round(($presentToday/$totalEmp)*100) : 0;
    @endphp

    <div class="stat-card bg-slate-900 border border-slate-800 rounded-xl p-5 group hover:border-brand-500/50 transition-all">
        <div class="flex items-start justify-between mb-4">
            <div class="w-9 h-9 rounded-lg bg-brand-500/10 flex items-center justify-center group-hover:bg-brand-500/20 transition-colors">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#4B5EFF" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0-3-3.85"/></svg>
            </div>
            <span class="text-xs text-slate-500 font-mono">today</span>
        </div>
        <div class="text-3xl font-semibold text-white mb-0.5">{{ $totalEmp }}</div>
        <div class="text-sm text-slate-400">Total Employees</div>
    </div>

    <div class="stat-card bg-slate-900 border border-slate-800 rounded-xl p-5 group hover:border-emerald-500/50 transition-all">
        <div class="flex items-start justify-between mb-4">
            <div class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center group-hover:bg-emerald-500/20 transition-colors">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span class="text-xs font-semibold {{ $rate >= 80 ? 'text-emerald-400' : ($rate >= 60 ? 'text-yellow-400' : 'text-red-400') }}">{{ $rate }}%</span>
        </div>
        <div class="text-3xl font-semibold text-white mb-0.5">{{ $presentToday }}</div>
        <div class="text-sm text-slate-400">Present Today</div>
    </div>

    <div class="stat-card bg-slate-900 border border-slate-800 rounded-xl p-5 group hover:border-yellow-500/50 transition-all">
        <div class="flex items-start justify-between mb-4">
            <div class="w-9 h-9 rounded-lg bg-yellow-500/10 flex items-center justify-center group-hover:bg-yellow-500/20 transition-colors">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#eab308" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        <div class="text-3xl font-semibold text-white mb-0.5">{{ $lateToday }}</div>
        <div class="text-sm text-slate-400">Late Today</div>
    </div>

    <div class="stat-card bg-slate-900 border border-slate-800 rounded-xl p-5 group hover:border-orange-500/50 transition-all">
        <div class="flex items-start justify-between mb-4">
            <div class="w-9 h-9 rounded-lg bg-orange-500/10 flex items-center justify-center group-hover:bg-orange-500/20 transition-colors">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            @if($pendingLeaves > 0)
                <span class="text-xs font-semibold text-orange-400 bg-orange-400/10 px-2 py-0.5 rounded-full">{{ $pendingLeaves }} pending</span>
            @endif
        </div>
        <div class="text-3xl font-semibold text-white mb-0.5">{{ $absentToday }}</div>
        <div class="text-sm text-slate-400">Absent Today</div>
    </div>
</div>

{{-- Attendance Rate Bar --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl p-5 mb-5">
    <div class="flex items-center justify-between mb-3">
        <span class="text-sm font-medium text-slate-300">Today's Attendance Rate</span>
        <span class="text-sm font-semibold {{ $rate >= 80 ? 'text-emerald-400' : ($rate >= 60 ? 'text-yellow-400' : 'text-red-400') }}">{{ $rate }}%</span>
    </div>
    <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
        <div class="h-full rounded-full transition-all duration-700"
             style="width:{{ $rate }}%; background: {{ $rate >= 80 ? '#10b981' : ($rate >= 60 ? '#eab308' : '#ef4444') }}"></div>
    </div>
    <div class="flex justify-between mt-2 text-xs text-slate-600">
        <span>0%</span><span>50%</span><span>100%</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    {{-- Recent attendance --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-white">Recent Check-ins</h3>
            <a href="{{ route('admin.attendance.index') }}" class="text-xs text-brand-400 hover:text-brand-300">View all →</a>
        </div>
        @php
            $recent = \App\Models\Attendance::with('user')
                ->where('attendance_date', $today)
                ->whereNotNull('check_in_time')
                ->orderByDesc('check_in_time')->limit(6)->get();
        @endphp
        @forelse($recent as $att)
        <div class="flex items-center gap-3 py-2.5 border-b border-slate-800/60 last:border-0">
            <div class="w-7 h-7 rounded-full bg-brand-600/30 flex items-center justify-center text-xs font-bold text-brand-300 flex-shrink-0">
                {{ strtoupper(substr($att->user->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-slate-200 truncate">{{ $att->user->name }}</div>
                <div class="text-xs text-slate-500">{{ $att->check_in_time?->format('H:i') }}</div>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                {{ $att->status === 'present' ? 'badge-present' : 'badge-late' }}">
                {{ ucfirst($att->status) }}
            </span>
        </div>
        @empty
        <div class="text-center py-8 text-slate-600 text-sm">No check-ins yet today</div>
        @endforelse
    </div>

    {{-- Pending leave requests --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-white">Pending Leave Requests</h3>
            <a href="{{ route('admin.leave.index') }}" class="text-xs text-brand-400 hover:text-brand-300">View all →</a>
        </div>
        @php
            $leaves = \App\Models\LeaveRequest::with(['user','leaveType'])
                ->where('status','pending')->latest()->limit(5)->get();
        @endphp
        @forelse($leaves as $leave)
        <div class="flex items-center gap-3 py-2.5 border-b border-slate-800/60 last:border-0">
            <div class="w-7 h-7 rounded-full bg-orange-600/20 flex items-center justify-center text-xs font-bold text-orange-300 flex-shrink-0">
                {{ strtoupper(substr($leave->user->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-slate-200 truncate">{{ $leave->user->name }}</div>
                <div class="text-xs text-slate-500">{{ $leave->leaveType->name }} · {{ $leave->total_days }}d</div>
            </div>
            <a href="{{ route('admin.leave.show', $leave) }}"
               class="text-xs bg-brand-500/10 hover:bg-brand-500/20 text-brand-400 px-2.5 py-1 rounded-lg transition-colors font-medium">
                Review
            </a>
        </div>
        @empty
        <div class="text-center py-8 text-slate-600 text-sm">No pending requests</div>
        @endforelse
    </div>
</div>

{{-- ───── EMPLOYEE DASHBOARD ───── --}}
@else
@php
    $user = auth()->user();
    $todayAtt = $user->todayAttendance();
    $schedule = $user->activeSchedule();
    $shift = $schedule?->workShift;
    $monthStats = \App\Models\Attendance::where('user_id',$user->id)
        ->whereMonth('attendance_date', now()->month)
        ->whereYear('attendance_date', now()->year)
        ->selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count','status');
@endphp

{{-- Check-in card --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-xl p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <div class="text-xs text-slate-500 mb-1">{{ now()->format('l, d F Y') }}</div>
                <h2 class="text-lg font-semibold text-white">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', $user->name)[0] }} 👋</h2>
            </div>
            <div id="clock" class="text-2xl font-mono font-medium text-brand-400"></div>
        </div>

        @if(!$todayAtt || !$todayAtt->isCheckedIn())
        <form method="POST" action="{{ route('employee.checkin') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="latitude" id="lat" required>
            <input type="hidden" name="longitude" id="lng" required>
            <div class="flex gap-3">
                <button type="button" onclick="getLocationAndSubmit('checkin-form')" id="checkin-btn"
                        class="flex-1 bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 px-5 rounded-xl transition-all hover:shadow-lg hover:shadow-brand-500/25 flex items-center justify-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Check In Now
                </button>
            </div>
        </form>
        <p id="gps-status" class="text-xs text-slate-600 mt-2"></p>

        @elseif($todayAtt->isCheckedIn() && !$todayAtt->isCheckedOut())
        <div class="flex items-center gap-3 bg-emerald-950/40 border border-emerald-800/40 rounded-xl px-4 py-3 mb-4">
            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
            <div class="text-sm text-emerald-300">Checked in at <strong>{{ $todayAtt->check_in_time?->format('H:i') }}</strong></div>
            @if($todayAtt->status === 'late')
                <span class="ml-auto text-xs badge-late px-2 py-0.5 rounded-full">Late {{ $todayAtt->late_minutes }}min</span>
            @endif
        </div>
        <form method="POST" action="{{ route('employee.checkout') }}">
            @csrf
            <input type="hidden" name="latitude" id="lat2">
            <input type="hidden" name="longitude" id="lng2">
            <button type="button" onclick="getLocationAndSubmit('checkout-form')"
                    class="w-full bg-slate-700 hover:bg-slate-600 text-white font-semibold py-3 px-5 rounded-xl transition-colors flex items-center justify-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Check Out
            </button>
        </form>

        @else
        <div class="bg-slate-800/50 rounded-xl px-4 py-4 text-center">
            <div class="text-sm text-slate-400 mb-1">Today's attendance complete</div>
            <div class="text-xs text-slate-600">
                In: {{ $todayAtt->check_in_time?->format('H:i') }} · Out: {{ $todayAtt->check_out_time?->format('H:i') }}
                · {{ round($todayAtt->working_minutes/60, 1) }}h worked
            </div>
        </div>
        @endif
    </div>

    {{-- Shift Info --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <h3 class="text-sm font-semibold text-slate-300 mb-4">My Shift</h3>
        @if($shift)
        <div class="space-y-3">
            <div>
                <div class="text-xs text-slate-600 mb-1">Shift Name</div>
                <div class="text-sm font-medium text-white">{{ $shift->name }}</div>
            </div>
            <div class="flex gap-4">
                <div>
                    <div class="text-xs text-slate-600 mb-1">Start</div>
                    <div class="text-sm font-mono text-brand-400">{{ substr($shift->start_time, 0, 5) }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-600 mb-1">End</div>
                    <div class="text-sm font-mono text-brand-400">{{ substr($shift->end_time, 0, 5) }}</div>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-600 mb-1">Working Days</div>
                @php $map=['Sun','Mon','Tue','Wed','Thu','Fri','Sat']; $today_dow = now()->dayOfWeek; @endphp
                <div class="flex gap-1 flex-wrap">
                    @foreach($map as $i => $day)
                        <span class="text-xs px-2 py-0.5 rounded-md font-medium
                            {{ in_array($i, $shift->working_days ?? []) ? ($i === $today_dow ? 'bg-brand-500 text-white' : 'bg-brand-500/10 text-brand-400') : 'bg-slate-800 text-slate-600' }}">
                            {{ $day }}
                        </span>
                    @endforeach
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-600 mb-1">Late Tolerance</div>
                <div class="text-sm text-slate-300">{{ $shift->late_tolerance_minutes }} minutes</div>
            </div>
        </div>
        @else
        <div class="text-center py-6 text-slate-600 text-sm">No shift assigned</div>
        @endif
    </div>
</div>

{{-- Monthly stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
    @foreach(['present'=>['#10b981','Present'], 'late'=>['#eab308','Late'], 'absent'=>['#ef4444','Absent'], 'leave'=>['#3b82f6','Leave']] as $status => [$color, $label])
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
        <div class="text-2xl font-semibold mb-1" style="color:{{ $color }}">{{ $monthStats[$status] ?? 0 }}</div>
        <div class="text-xs text-slate-500">{{ $label }} this month</div>
    </div>
    @endforeach
</div>

{{-- Recent attendance --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-white">Recent Attendance</h3>
        <a href="{{ route('employee.attendance') }}" class="text-xs text-brand-400 hover:text-brand-300">View all →</a>
    </div>
    @php
        $history = \App\Models\Attendance::where('user_id',$user->id)
            ->orderByDesc('attendance_date')->limit(7)->get();
    @endphp
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-slate-600 border-b border-slate-800">
                <th class="pb-2 font-medium">Date</th>
                <th class="pb-2 font-medium">Check In</th>
                <th class="pb-2 font-medium">Check Out</th>
                <th class="pb-2 font-medium">Hours</th>
                <th class="pb-2 font-medium">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/50">
        @forelse($history as $att)
            <tr class="table-row-hover">
                <td class="py-2.5 text-slate-300">{{ $att->attendance_date?->format('d M') }}</td>
                <td class="py-2.5 font-mono text-slate-400 text-xs">{{ $att->check_in_time?->format('H:i') ?? '—' }}</td>
                <td class="py-2.5 font-mono text-slate-400 text-xs">{{ $att->check_out_time?->format('H:i') ?? '—' }}</td>
                <td class="py-2.5 text-slate-400 text-xs">{{ $att->working_minutes ? round($att->working_minutes/60,1).'h' : '—' }}</td>
                <td class="py-2.5">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium badge-{{ $att->status }}">
                        {{ ucfirst($att->status) }}
                    </span>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="py-6 text-center text-slate-600">No attendance records yet</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
// Live clock
function updateClock() {
    const el = document.getElementById('clock');
    if (!el) return;
    const now = new Date();
    el.textContent = now.toLocaleTimeString('en-US', {hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false});
}
updateClock(); setInterval(updateClock, 1000);

// GPS location helper
function getLocationAndSubmit(formId) {
    const statusEl = document.getElementById('gps-status');
    if (statusEl) statusEl.textContent = 'Getting your location…';
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser.'); return;
    }
    navigator.geolocation.getCurrentPosition(
        pos => {
            const lat = pos.coords.latitude, lng = pos.coords.longitude;
            document.getElementById('lat') && (document.getElementById('lat').value = lat);
            document.getElementById('lng') && (document.getElementById('lng').value = lng);
            document.getElementById('lat2') && (document.getElementById('lat2').value = lat);
            document.getElementById('lng2') && (document.getElementById('lng2').value = lng);
            if (statusEl) statusEl.textContent = `Location acquired (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
            // Submit the appropriate form
            const forms = document.querySelectorAll('form');
            forms[0] && forms[0].submit();
        },
        err => {
            if (statusEl) statusEl.textContent = 'Location access denied. Please enable GPS.';
            alert('Unable to get location: ' + err.message);
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}
</script>
@endpush
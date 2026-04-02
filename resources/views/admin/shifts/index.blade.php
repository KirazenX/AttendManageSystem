@extends('layouts.app')
@section('title','Work Shifts')
@section('page-title','Work Shifts')
@section('page-subtitle','Configure and manage employee shift schedules')

@section('content')

<div class="flex justify-end mb-5">
    <a href="{{ route('admin.shifts.create') }}"
            class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Create Shift
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse($shifts as $shift)
    <div class="bg-slate-900 border {{ $shift->is_active ? 'border-slate-800' : 'border-slate-800/50 opacity-60' }} rounded-xl p-5 hover:border-slate-700 transition-all">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-white">{{ $shift->name }}</h3>
                @if($shift->crosses_midnight)
                    <span class="text-xs text-orange-400 bg-orange-400/10 px-1.5 py-0.5 rounded mt-1 inline-block">Crosses midnight</span>
                @endif
            </div>
            <div class="flex items-center gap-1">
                <span class="w-2 h-2 rounded-full {{ $shift->is_active ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
                <span class="text-xs {{ $shift->is_active ? 'text-emerald-400' : 'text-slate-600' }}">{{ $shift->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
        </div>

        <div class="flex items-center gap-4 mb-4">
            <div class="text-center">
                <div class="text-xl font-mono font-semibold text-brand-400">{{ substr($shift->start_time,0,5) }}</div>
                <div class="text-xs text-slate-600">Start</div>
            </div>
            <div class="flex-1 h-px bg-slate-800 relative">
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#4B5EFF" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
                </div>
            </div>
            <div class="text-center">
                <div class="text-xl font-mono font-semibold text-brand-400">{{ substr($shift->end_time,0,5) }}</div>
                <div class="text-xs text-slate-600">End</div>
            </div>
        </div>

        @php $map=['Sun','Mon','Tue','Wed','Thu','Fri','Sat']; @endphp
        <div class="flex gap-1 mb-4">
            @foreach($map as $i => $day)
                <span class="flex-1 text-center text-[10px] py-1 rounded font-medium
                    {{ in_array($i, $shift->working_days ?? []) ? 'bg-brand-500/20 text-brand-400' : 'bg-slate-800 text-slate-700' }}">
                    {{ $day[0] }}
                </span>
            @endforeach
        </div>

        <div class="flex gap-4 text-xs text-slate-500 mb-4">
            <span>Late tolerance: <strong class="text-slate-400">{{ $shift->late_tolerance_minutes }}m</strong></span>
        </div>

        <div class="flex gap-2 pt-3 border-t border-slate-800">
            <a href="{{ route('admin.shifts.edit', $shift) }}"
               class="flex-1 text-center text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 py-2 rounded-lg transition-colors font-medium">
                Edit
            </a>
            @php $activeUsers = \App\Models\WorkSchedule::where('work_shift_id',$shift->id)->where('is_active',true)->count(); @endphp
            @if($activeUsers === 0)
            <form method="POST" action="{{ route('admin.shifts.destroy', $shift) }}" onsubmit="return confirm('Delete this shift?')">
                @csrf @method('DELETE')
                <button class="px-4 text-xs bg-red-600/10 hover:bg-red-600/20 text-red-400 py-2 rounded-lg transition-colors font-medium border border-red-900/30">
                    Delete
                </button>
            </form>
            @else
            <span class="px-3 text-xs text-slate-600 py-2">{{ $activeUsers }} assigned</span>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center text-slate-600">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-3 text-slate-700"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        No shifts configured yet
    </div>
    @endforelse
</div>

{{-- Create Shift Modal --}}
<div id="shift-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-lg mx-4 animate-fade-up max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-semibold text-white">Create New Shift</h3>
            <button onclick="document.getElementById('shift-modal').classList.replace('flex','hidden')" class="text-slate-500 hover:text-slate-300">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.shifts.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Shift Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Morning Shift"
                       class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Start Time</label>
                    <input type="time" name="start_time" required
                           class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">End Time</label>
                    <input type="time" name="end_time" required
                           class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500 font-mono">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-2">Working Days</label>
                <div class="flex gap-2 flex-wrap">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $i => $d)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="working_days[]" value="{{ $i }}" class="sr-only peer"
                               {{ in_array($i,[1,2,3,4,5]) ? 'checked' : '' }}>
                        <span class="block px-3 py-1.5 text-xs font-medium rounded-lg border transition-all
                              border-slate-700 text-slate-500 peer-checked:border-brand-500 peer-checked:bg-brand-500/15 peer-checked:text-brand-400">
                            {{ $d }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Late Tolerance (min)</label>
                    <input type="number" name="late_tolerance_minutes" value="15" min="0" max="120"
                           class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Early Checkout (min)</label>
                    <input type="number" name="early_checkout_tolerance_minutes" value="15" min="0" max="120"
                           class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Description</label>
                <textarea name="description" rows="2" placeholder="Optional notes about this shift…"
                          class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500 resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-brand-500 hover:bg-brand-600 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">Create Shift</button>
                <button type="button" onclick="document.getElementById('shift-modal').classList.replace('flex','hidden')"
                        class="flex-1 border border-slate-700 text-slate-400 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('shift-modal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.replace('flex','hidden');
});
</script>
@endpush
@extends('layouts.app')
@section('title', $shift->exists ? 'Edit Shift' : 'Add Shift')
@section('page-title', $shift->exists ? 'Edit Shift' : 'Add Shift')
@section('page-subtitle', $shift->exists ? 'Update shift timing and rules' : 'Create a new work shift schedule')

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

    <form method="POST" action="{{ $shift->exists ? route('admin.shifts.update', $shift) : route('admin.shifts.store') }}">
        @csrf
        @if($shift->exists) @method('PUT') @endif

        <div class="space-y-6">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Shift Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $shift->name ?? '') }}" required
                       class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500 transition-colors"
                       placeholder="e.g. Morning Shift">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Start Time <span class="text-red-400">*</span></label>
                    <input type="time" name="start_time" value="{{ old('start_time', substr($shift->start_time, 0, 5) ?? '') }}" required
                           class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">End Time <span class="text-red-400">*</span></label>
                    <input type="time" name="end_time" value="{{ old('end_time', substr($shift->end_time, 0, 5) ?? '') }}" required
                           class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500 font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-2.5">Working Days <span class="text-red-400">*</span></label>
                <div class="flex gap-2 flex-wrap">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $i => $d)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="working_days[]" value="{{ $i }}" class="sr-only peer"
                               {{ in_array($i, old('working_days', $shift->working_days ?? [1,2,3,4,5])) ? 'checked' : '' }}>
                        <span class="block px-3 py-2 text-xs font-medium rounded-lg border transition-all
                              border-slate-700 text-slate-500 peer-checked:border-brand-500 peer-checked:bg-brand-500/15 peer-checked:text-brand-400">
                            {{ $d }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Late Tolerance (minutes) <span class="text-red-400">*</span></label>
                    <input type="number" name="late_tolerance_minutes" value="{{ old('late_tolerance_minutes', $shift->late_tolerance_minutes ?? 15) }}" min="0" max="120" required
                           class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Early Checkout Tolerance (minutes)</label>
                    <input type="number" name="early_checkout_tolerance_minutes" value="{{ old('early_checkout_tolerance_minutes', $shift->early_checkout_tolerance_minutes ?? 15) }}" min="0" max="120"
                           class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Description</label>
                <textarea name="description" rows="3" placeholder="Optional notes about this shift…"
                          class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500 resize-none">{{ old('description', $shift->description ?? '') }}</textarea>
            </div>

            <div class="pt-4 flex gap-3 border-t border-slate-800">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                    {{ $shift->exists ? 'Update Shift' : 'Create Shift' }}
                </button>
                <a href="{{ route('admin.shifts.index') }}" class="border border-slate-700 text-slate-400 hover:text-slate-200 hover:bg-slate-800 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
</div>
@endsection

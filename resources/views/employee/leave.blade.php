@extends('layouts.app')
@section('title','My Leaves')
@section('page-title','Leave Management')
@section('page-subtitle','Submit and track your leave requests')

@section('content')
@php $user = auth()->user(); @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Submit leave form --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <h3 class="text-sm font-semibold text-white mb-4">Submit Leave Request</h3>

        @if($errors->any())
        <div class="bg-red-950/50 border border-red-800/50 text-red-300 text-xs px-3 py-2.5 rounded-lg mb-4">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('employee.leave.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Leave Type <span class="text-red-400">*</span></label>
                <select name="leave_type_id" required class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
                    <option value="">Select type…</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }} (max {{ $type->max_days_per_year }}d/yr)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Start Date <span class="text-red-400">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           min="{{ now()->toDateString() }}"
                           class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">End Date <span class="text-red-400">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                           min="{{ now()->toDateString() }}"
                           class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Reason <span class="text-red-400">*</span></label>
                <textarea name="reason" rows="3" required
                          class="w-full bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-500 resize-none"
                          placeholder="Please describe the reason for your leave…">{{ old('reason') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Supporting Document</label>
                <div class="border border-dashed border-slate-700 rounded-lg px-3 py-4 text-center hover:border-brand-500/50 transition-colors cursor-pointer" onclick="document.getElementById('attach').click()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" class="mx-auto mb-2 text-slate-600"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <p class="text-xs text-slate-500">Click to upload PDF, JPG, PNG (max 5MB)</p>
                </div>
                <input type="file" name="attachment" id="attach" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                       onchange="document.getElementById('attach-label').textContent = this.files[0]?.name || ''">
                <p id="attach-label" class="text-xs text-brand-400 mt-1.5"></p>
            </div>
            <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                Submit Request
            </button>
        </form>
    </div>

    {{-- Leave history --}}
    <div class="lg:col-span-2 space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-white">My Leave Requests</h3>
            <div class="text-xs text-slate-500">{{ $leaveRequests->total() }} total</div>
        </div>

        @forelse($leaveRequests as $leave)
        <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl p-4 transition-all">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-sm font-semibold text-white">{{ $leave->leaveType->name }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium badge-{{ $leave->status }}">{{ ucfirst($leave->status) }}</span>
                    </div>
                    <div class="text-xs text-slate-400 mb-2">
                        {{ $leave->start_date->format('d M Y') }} — {{ $leave->end_date->format('d M Y') }}
                        <span class="text-slate-600 mx-1">·</span>
                        <strong class="text-slate-300">{{ $leave->total_days }} day{{ $leave->total_days > 1 ? 's' : '' }}</strong>
                    </div>
                    <p class="text-xs text-slate-500 line-clamp-2">{{ $leave->reason }}</p>
                    @if($leave->rejection_reason)
                    <div class="mt-2 text-xs text-red-400 bg-red-950/30 border border-red-900/30 rounded-lg px-3 py-2">
                        Rejected: {{ $leave->rejection_reason }}
                    </div>
                    @endif
                </div>
                <div class="text-right flex-shrink-0">
                    <div class="text-xs text-slate-600 font-mono">{{ $leave->created_at->format('d M') }}</div>
                    @if($leave->attachment)
                    <a href="{{ asset('storage/'.$leave->attachment) }}" target="_blank"
                       class="mt-1 text-xs text-brand-400 hover:text-brand-300 flex items-center gap-1 justify-end">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                        Attachment
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="py-16 text-center text-slate-600 bg-slate-900 border border-slate-800 rounded-xl">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-3 text-slate-700"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <p class="text-sm">No leave requests yet</p>
        </div>
        @endforelse

        @if($leaveRequests->hasPages())
        <div class="flex justify-end gap-2 text-xs text-slate-500 pt-2">
            @if(!$leaveRequests->onFirstPage())
                <a href="{{ $leaveRequests->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 transition-colors">← Prev</a>
            @endif
            @if($leaveRequests->hasMorePages())
                <a href="{{ $leaveRequests->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 transition-colors">Next →</a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
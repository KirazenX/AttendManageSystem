@extends('layouts.app')
@section('title','Leave Requests')
@section('page-title','Leave Requests')
@section('page-subtitle','Review and manage employee leave applications')

@section('content')

{{-- Filter tabs --}}
<div class="flex gap-1 mb-5 bg-slate-900 border border-slate-800 rounded-xl p-1 w-fit">
    @foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected'] as $val=>$label)
    <a href="{{ route('admin.leave.index', ['status' => $val === 'all' ? '' : $val]) }}"
       class="px-4 py-2 text-sm font-medium rounded-lg transition-all
       {{ request('status', '') === ($val === 'all' ? '' : $val) ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-slate-200' }}">
        {{ $label }}
        @if($val === 'pending')
            @php $cnt = \App\Models\LeaveRequest::where('status','pending')->count(); @endphp
            @if($cnt > 0) <span class="ml-1 bg-orange-500 text-white text-[10px] px-1.5 rounded-full">{{ $cnt }}</span> @endif
        @endif
    </a>
    @endforeach
</div>

{{-- Cards grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse($leaves as $leave)
    <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl p-5 transition-all">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-brand-600/25 flex items-center justify-center text-xs font-bold text-brand-300">
                    {{ strtoupper(substr($leave->user->name, 0, 2)) }}
                </div>
                <div>
                    <div class="text-sm font-semibold text-white">{{ $leave->user->name }}</div>
                    <div class="text-xs text-slate-500">{{ $leave->user->department?->name ?? 'No dept' }}</div>
                </div>
            </div>
            <span class="text-xs px-2 py-1 rounded-full font-medium badge-{{ $leave->status }}">
                {{ ucfirst($leave->status) }}
            </span>
        </div>

        <div class="bg-slate-800/50 rounded-lg px-3 py-2.5 mb-4">
            <div class="text-xs text-slate-500 mb-1">{{ $leave->leaveType->name }}</div>
            <div class="text-sm font-medium text-slate-200">
                {{ $leave->start_date->format('d M') }} — {{ $leave->end_date->format('d M Y') }}
            </div>
            <div class="text-xs text-slate-500 mt-0.5">{{ $leave->total_days }} working day{{ $leave->total_days > 1 ? 's' : '' }}</div>
        </div>

        <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $leave->reason }}</p>

        @if($leave->status === 'pending')
        <div class="flex gap-2">
            <form method="POST" action="{{ route('admin.leave.approve', $leave) }}" class="flex-1">
                @csrf @method('PUT')
                <input type="hidden" name="action" value="approved">
                <button class="w-full bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-400 text-xs font-semibold py-2 rounded-lg transition-colors border border-emerald-800/50">
                    ✓ Approve
                </button>
            </form>
            <button onclick="openRejectModal({{ $leave->id }})"
                    class="flex-1 bg-red-600/20 hover:bg-red-600/30 text-red-400 text-xs font-semibold py-2 rounded-lg transition-colors border border-red-800/50">
                ✕ Reject
            </button>
        </div>
        @else
        <div class="text-xs text-slate-600">
            Processed by {{ $leave->approvedBy?->name ?? 'System' }} · {{ $leave->approved_at?->format('d M Y') }}
        </div>
        @endif
    </div>
    @empty
    <div class="col-span-3 py-16 text-center text-slate-600">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-3 text-slate-700"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        No leave requests found
    </div>
    @endforelse
</div>

@if($leaves->hasPages())
<div class="mt-5 flex justify-center">{{ $leaves->links() }}</div>
@endif

{{-- Reject Modal --}}
<div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-md mx-4 animate-fade-up">
        <h3 class="text-base font-semibold text-white mb-4">Reject Leave Request</h3>
        <form method="POST" id="reject-form">
            @csrf @method('PUT')
            <input type="hidden" name="action" value="rejected">
            <label class="block text-xs text-slate-400 mb-2">Rejection Reason <span class="text-red-400">*</span></label>
            <textarea name="rejection_reason" rows="3" required
                      class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2.5 text-sm text-slate-200 resize-none focus:outline-none focus:border-brand-500"
                      placeholder="Please provide a reason for rejection…"></textarea>
            <div class="flex gap-3 mt-4">
                <button type="button" onclick="closeRejectModal()"
                        class="flex-1 border border-slate-700 text-slate-400 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-medium transition-colors">
                    Confirm Reject
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openRejectModal(id) {
    document.getElementById('reject-form').action = `/admin/leave/${id}/approve`;
    document.getElementById('reject-modal').classList.replace('hidden','flex');
}
function closeRejectModal() {
    document.getElementById('reject-modal').classList.replace('flex','hidden');
}
document.getElementById('reject-modal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush
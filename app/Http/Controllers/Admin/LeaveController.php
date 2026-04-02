<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['user.department', 'leaveType'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->paginate(20);

        return view('admin.leave.index', compact('leaves'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        return view('admin.leave.show', compact('leaveRequest'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.leave.index')->with('success', 'Leave request approved.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $leaveRequest->update([
            'status'           => 'rejected',
            'approved_by'      => Auth::id(),
            'approved_at'      => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('admin.leave.index')->with('success', 'Leave request rejected.');
    }
}

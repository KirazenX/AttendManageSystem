<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $leaveTypes = LeaveType::where('is_active', true)->get();
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->with('leaveType')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('employee.leave', compact('leaveTypes', 'leaveRequests'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string|min:10',
            'attachment'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        
        // Calculate weekdays (excluding weekends) - simplified for now
        $totalDays = $start->diffInDays($end) + 1; 

        $leaveType = LeaveType::findOrFail($data['leave_type_id']);
        
        // Check quota
        $usedDays = LeaveRequest::where('user_id', $user->id)
            ->where('leave_type_id', $data['leave_type_id'])
            ->where('status', 'approved')
            ->whereYear('start_date', now()->year)
            ->sum('total_days');

        if (($usedDays + $totalDays) > $leaveType->max_days_per_year) {
            $remaining = $leaveType->max_days_per_year - $usedDays;
            return back()->withInput()->withErrors(['leave_type_id' => "Leave quota exceeded. Remaining: {$remaining} day(s)."]);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store("leave-attachments/{$user->id}", 'public');
        }

        LeaveRequest::create([
            'user_id'       => $user->id,
            'leave_type_id' => $data['leave_type_id'],
            'start_date'    => $data['start_date'],
            'end_date'      => $data['end_date'],
            'total_days'    => $totalDays,
            'reason'        => $data['reason'],
            'attachment'    => $attachmentPath,
            'status'        => 'pending',
        ]);

        return redirect()->route('employee.leave')->with('success', 'Leave request submitted successfully.');
    }
}

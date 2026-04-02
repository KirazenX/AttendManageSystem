<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['user.department', 'workShift'])
            ->orderByDesc('attendance_date');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date')) {
            $query->where('attendance_date', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(20);

        return view('admin.attendance.index', compact('attendances'));
    }

    public function show(Attendance $attendance)
    {
        return view('admin.attendance.show', compact('attendance'));
    }
}

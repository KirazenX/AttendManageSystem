<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $attendanceService) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->with('workShift')
            ->orderByDesc('attendance_date')
            ->paginate(15);

        $stats = Attendance::where('user_id', $user->id)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->selectRaw('status, COUNT(*) as count, SUM(working_minutes) as total_minutes')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $totalMinutes = $stats->sum('total_minutes');

        return view('employee.attendance', compact('attendances', 'month', 'year', 'stats', 'totalMinutes'));
    }

    public function checkIn(Request $request)
    {
        $data = $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            $this->attendanceService->checkIn(Auth::user(), $data);
            return back()->with('success', 'Check-in successful.');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function checkOut(Request $request)
    {
        $data = $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            $this->attendanceService->checkOut(Auth::user(), $data);
            return back()->with('success', 'Check-out successful.');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}

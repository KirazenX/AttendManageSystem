<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $totals = Attendance::whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $employees = User::with(['department', 'attendances' => function ($query) use ($month, $year) {
            $query->whereMonth('attendance_date', $month)
                ->whereYear('attendance_date', $year)
                ->selectRaw('user_id, status, working_minutes');
        }])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.reports.index', compact('month', 'year', 'totals', 'employees'));
    }

    public function export(Request $request)
    {
        // Dummy export logic
        return back()->with('success', 'Report exported successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkShift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = WorkShift::all();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        $shift = new WorkShift();
        return view('admin.shifts.form', compact('shift'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                   => 'required|string|max:255',
            'start_time'             => 'required',
            'end_time'               => 'required',
            'late_tolerance_minutes' => 'required|integer',
            'working_days'           => 'required|array',
        ]);

        WorkShift::create($data);
        return redirect()->route('admin.shifts.index')->with('success', 'Shift created.');
    }

    public function edit(WorkShift $shift)
    {
        return view('admin.shifts.form', compact('shift'));
    }

    public function update(Request $request, WorkShift $shift)
    {
        $data = $request->validate([
            'name'                   => 'required|string|max:255',
            'start_time'             => 'required',
            'end_time'               => 'required',
            'late_tolerance_minutes' => 'required|integer',
            'working_days'           => 'required|array',
        ]);

        $shift->update($data);
        return redirect()->route('admin.shifts.index')->with('success', 'Shift updated.');
    }

    public function destroy(WorkShift $shift)
    {
        $shift->delete();
        return redirect()->route('admin.shifts.index')->with('success', 'Shift deleted.');
    }
}

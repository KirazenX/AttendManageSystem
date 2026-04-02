<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeLocation;
use Illuminate\Http\Request;

class OfficeLocationController extends Controller
{
    public function index()
    {
        $offices = OfficeLocation::all();
        return view('admin.offices.index', compact('offices'));
    }

    public function create()
    {
        $office = new OfficeLocation();
        return view('admin.offices.form', compact('office'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            'radius_meters' => 'required|integer|min:10',
            'is_active'     => 'boolean',
        ]);

        OfficeLocation::create($data);

        return redirect()->route('admin.offices.index')->with('success', 'Office location created.');
    }

    public function edit(OfficeLocation $office)
    {
        return view('admin.offices.form', compact('office'));
    }

    public function update(Request $request, OfficeLocation $office)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            'radius_meters' => 'required|integer|min:10',
            'is_active'     => 'boolean',
        ]);

        $office->update($data);

        return redirect()->route('admin.offices.index')->with('success', 'Office location updated.');
    }

    public function destroy(OfficeLocation $office)
    {
        $office->delete();
        return redirect()->route('admin.offices.index')->with('success', 'Office location deleted.');
    }
}

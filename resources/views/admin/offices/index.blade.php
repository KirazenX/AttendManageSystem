@extends('layouts.app')
@section('title', 'Office Locations')
@section('page-title', 'Office Locations')
@section('page-subtitle', 'Manage physical office boundaries for GPS validation')

@section('content')
<div class="mb-5 flex justify-end">
    <a href="{{ route('admin.offices.create') }}" 
       class="bg-brand-500 hover:bg-brand-600 text-white text-sm px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add New Office
    </a>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-800/40">
                <tr class="text-left text-xs text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">Name</th>
                    <th class="px-6 py-4 font-semibold">Address</th>
                    <th class="px-6 py-4 font-semibold">Coordinates</th>
                    <th class="px-6 py-4 font-semibold text-center">Radius</th>
                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/50">
                @forelse($offices as $office)
                <tr class="table-row-hover transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-200">
                        {{ $office->name }}
                    </td>
                    <td class="px-6 py-4 text-slate-400 max-w-xs truncate">
                        {{ $office->address }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs font-mono text-slate-500">Lat: {{ number_format($office->latitude, 6) }}</div>
                        <div class="text-xs font-mono text-slate-500">Lng: {{ number_format($office->longitude, 6) }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-slate-300 font-medium">{{ $office->radius_meters }}m</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($office->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Active</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-500 border border-slate-700">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.offices.edit', $office) }}" 
                               class="p-1.5 text-slate-400 hover:text-brand-400 transition-colors" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form action="{{ route('admin.offices.destroy', $office) }}" method="POST" onsubmit="return confirm('Delete this office location?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 transition-colors" title="Delete">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" class="mb-3 text-slate-700"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <p>No office locations found.</p>
                            <a href="{{ route('admin.offices.create') }}" class="mt-2 text-brand-500 hover:underline text-xs">Add one now</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

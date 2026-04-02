@extends('layouts.app')
@section('title', $office->exists ? 'Edit Office' : 'Add Office')
@section('page-title', $office->exists ? 'Edit Office Location' : 'Add Office Location')
@section('page-subtitle', 'Configure GPS validation boundaries')

@section('content')
<div class="max-w-2xl">
    <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-sm overflow-hidden">
        <form action="{{ $office->exists ? route('admin.offices.update', $office) : route('admin.offices.store') }}" 
              method="POST" class="p-6 space-y-5">
            @csrf
            @if($office->exists) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5">
                {{-- Office Name --}}
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Office Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $office->name ?? '') }}" 
                           placeholder="e.g. Headquarters, Branch Office A"
                           class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 text-sm focus:border-brand-500 transition-colors" required>
                    @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Address --}}
                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Full Address</label>
                    <textarea name="address" id="address" rows="2" 
                              placeholder="Complete physical address..."
                              class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 text-sm focus:border-brand-500 transition-colors" required>{{ old('address', $office->address ?? '') }}</textarea>
                    @error('address') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Latitude --}}
                    <div>
                        <label for="latitude" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Latitude</label>
                        <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $office->latitude ?? '') }}" 
                               placeholder="-6.123456"
                               class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 text-sm font-mono focus:border-brand-500 transition-colors" required>
                        @error('latitude') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Longitude --}}
                    <div>
                        <label for="longitude" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Longitude</label>
                        <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $office->longitude ?? '') }}" 
                               placeholder="106.123456"
                               class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 text-sm font-mono focus:border-brand-500 transition-colors" required>
                        @error('longitude') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-brand-500/5 border border-brand-500/10 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-brand-500/10 flex items-center justify-center text-brand-400">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div class="text-xs text-slate-400">Not sure? Use your current device location.</div>
                    </div>
                    <button type="button" id="get-location" 
                            class="text-xs font-bold text-brand-400 hover:text-brand-300 transition-colors uppercase tracking-widest px-3 py-1.5 rounded-md hover:bg-brand-500/10">
                        Detect Location
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Radius --}}
                    <div>
                        <label for="radius_meters" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Radius (Meters)</label>
                        <div class="relative">
                            <input type="number" name="radius_meters" id="radius_meters" value="{{ old('radius_meters', $office->radius_meters ?? 100) }}" 
                                   class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 text-sm focus:border-brand-500 transition-colors" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500 font-medium">meters</span>
                        </div>
                        @error('radius_meters') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Status</label>
                        <div class="flex items-center gap-4 h-[42px]">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $office->is_active ?? true) ? 'checked' : '' }}>
                                <div class="w-10 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-500"></div>
                                <span class="ml-3 text-sm text-slate-300">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex items-center gap-3 border-t border-slate-800">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white text-sm px-6 py-2.5 rounded-lg font-bold transition-all shadow-lg shadow-brand-500/20 active:scale-95">
                    {{ $office->exists ? 'Update Office Location' : 'Save Office Location' }}
                </button>
                <a href="{{ route('admin.offices.index') }}" class="text-sm text-slate-500 hover:text-slate-300 font-medium transition-colors px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('get-location').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerText;
        
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }

        btn.innerText = 'Detecting...';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            (position) => {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(8);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(8);
                btn.innerText = 'Location Detected!';
                btn.classList.add('text-emerald-400');
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.disabled = false;
                    btn.classList.remove('text-emerald-400');
                }, 2000);
            },
            (error) => {
                alert('Error detecting location: ' + error.message);
                btn.innerText = originalText;
                btn.disabled = false;
            },
            { enableHighAccuracy: true }
        );
    });
</script>
@endpush
@endsection

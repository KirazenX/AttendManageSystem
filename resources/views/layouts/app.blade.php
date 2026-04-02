<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AttendX') — AttendX</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:300,400,500,600|dm-mono:400,500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        mono: ['DM Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50:  '#f0f4ff',
                            100: '#dde6ff',
                            200: '#c2d1ff',
                            300: '#9ab1ff',
                            400: '#7088ff',
                            500: '#4B5EFF',
                            600: '#3340f5',
                            700: '#2a31e0',
                            800: '#252ab5',
                            900: '#24298f',
                            950: '#161755',
                        },
                        slate: {
                            925: '#0d1117',
                        }
                    },
                    animation: {
                        'slide-in': 'slideIn 0.3s ease-out',
                        'fade-up': 'fadeUp 0.4s ease-out',
                        'pulse-dot': 'pulseDot 2s ease-in-out infinite',
                    },
                    keyframes: {
                        slideIn: { from: { transform: 'translateX(-100%)', opacity: '0' }, to: { transform: 'translateX(0)', opacity: '1' } },
                        fadeUp: { from: { transform: 'translateY(12px)', opacity: '0' }, to: { transform: 'translateY(0)', opacity: '1' } },
                        pulseDot: { '0%,100%': { opacity: '1', transform: 'scale(1)' }, '50%': { opacity: '0.5', transform: 'scale(0.85)' } },
                    }
                }
            }
        }
    </script>
    <style>
        * { -webkit-font-smoothing: antialiased; }
        :root {
            --sidebar-w: 260px;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 14px; border-radius: 8px;
            font-size: 14px; font-weight: 500; color: #94a3b8;
            transition: all .18s ease; position: relative;
        }
        .sidebar-link:hover { background: rgba(75,94,255,.08); color: #e2e8f0; }
        .sidebar-link.active { background: rgba(75,94,255,.15); color: #7088ff; }
        .sidebar-link.active::before {
            content: ''; position: absolute; left: 0; top: 20%; height: 60%;
            width: 3px; background: #4B5EFF; border-radius: 0 3px 3px 0;
        }
        .sidebar-link svg { flex-shrink: 0; }
        .stat-card { animation: fadeUp .4s ease-out both; }
        .stat-card:nth-child(1) { animation-delay: .05s }
        .stat-card:nth-child(2) { animation-delay: .1s }
        .stat-card:nth-child(3) { animation-delay: .15s }
        .stat-card:nth-child(4) { animation-delay: .2s }
        .badge-present { background:#dcfce7; color:#166534; }
        .badge-late    { background:#fef9c3; color:#854d0e; }
        .badge-absent  { background:#fee2e2; color:#991b1b; }
        .badge-leave   { background:#dbeafe; color:#1e40af; }
        .badge-pending { background:#fef3c7; color:#92400e; }
        .badge-approved{ background:#d1fae5; color:#065f46; }
        .badge-rejected{ background:#fee2e2; color:#991b1b; }
        input:focus, select:focus, textarea:focus { outline: none; box-shadow: 0 0 0 3px rgba(75,94,255,.18); }
        .table-row-hover:hover { background: rgba(75,94,255,.04); }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 99px; }
    </style>
    @stack('head')
</head>
<body class="h-full bg-slate-950 font-sans text-slate-100">

<div class="flex h-full">
    {{-- ── Sidebar ── --}}
    <aside class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 border-r border-slate-800"
           style="width:var(--sidebar-w)" id="sidebar">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center flex-shrink-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    <circle cx="12" cy="16" r="2" fill="white" stroke="none"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold text-white text-sm tracking-wide">AttendX</div>
                <div class="text-xs text-slate-500 font-mono">v1.0</div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
            @php $role = auth()->user()?->roles->first()?->name ?? 'employee'; @endphp

            <div class="px-3 pb-2 text-[10px] font-semibold text-slate-600 uppercase tracking-widest">Overview</div>

            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>

            @if(in_array($role, ['admin','hr']))
                <div class="px-3 pt-4 pb-2 text-[10px] font-semibold text-slate-600 uppercase tracking-widest">Management</div>

                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4"/><path d="M16 11l2 2 4-4"/></svg>
                    Employees
                </a>

                <a href="{{ route('admin.attendance.index') }}" class="sidebar-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Attendance
                </a>

                <a href="{{ route('admin.leave.index') }}" class="sidebar-link {{ request()->routeIs('admin.leave.*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Leave Requests
                    @php $pending = \App\Models\LeaveRequest::where('status','pending')->count(); @endphp
                    @if($pending > 0)
                        <span class="ml-auto bg-brand-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pending }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.shifts.index') }}" class="sidebar-link {{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4"/></svg>
                    Work Shifts
                </a>

                <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    Reports
                </a>

                @if($role === 'admin')
                    <a href="{{ route('admin.offices.index') }}" class="sidebar-link {{ request()->routeIs('admin.offices.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Office Locations
                    </a>
                @endif
            @endif

            <div class="px-3 pt-4 pb-2 text-[10px] font-semibold text-slate-600 uppercase tracking-widest">My Account</div>

            <a href="{{ route('employee.attendance') }}" class="sidebar-link {{ request()->routeIs('employee.attendance') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
                My Attendance
            </a>

            <a href="{{ route('employee.leave') }}" class="sidebar-link {{ request()->routeIs('employee.leave') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                My Leaves
            </a>
        </nav>

        {{-- User footer --}}
        <div class="p-3 border-t border-slate-800">
            <div class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-800 cursor-pointer group">
                <div class="w-8 h-8 rounded-full bg-brand-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-slate-200 truncate">{{ auth()->user()?->name ?? 'User' }}</div>
                    <div class="text-xs text-slate-500 capitalize">{{ $role }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-600 hover:text-red-400 transition-colors" title="Logout">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main ── --}}
    <div class="flex-1 flex flex-col min-h-full" style="margin-left:var(--sidebar-w)">

        {{-- Topbar --}}
        <header class="sticky top-0 z-40 bg-slate-950/80 backdrop-blur border-b border-slate-800 px-6 py-3 flex items-center justify-between">
            <div>
                <h1 class="text-sm font-semibold text-white">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-slate-500 mt-0.5">@yield('page-subtitle', '')</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-xs text-slate-500 font-mono">{{ now()->format('D, d M Y') }}</div>
                <div class="w-px h-4 bg-slate-700"></div>
                <div class="relative">
                    <div class="absolute top-0 right-0 w-2 h-2 bg-brand-500 rounded-full animate-pulse-dot"></div>
                    <button class="text-slate-400 hover:text-slate-200 transition-colors p-1">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </button>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mx-6 mt-4 flex items-center gap-3 bg-emerald-950 border border-emerald-800 text-emerald-300 px-4 py-3 rounded-lg text-sm animate-fade-up">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-6 mt-4 flex items-center gap-3 bg-red-950 border border-red-800 text-red-300 px-4 py-3 rounded-lg text-sm animate-fade-up">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 px-6 py-6">
            @yield('content')
        </main>

        <footer class="px-6 py-3 border-t border-slate-800 text-xs text-slate-600 text-center">
            AttendX &copy; {{ date('Y') }} — Company Attendance System
        </footer>
    </div>
</div>

@stack('scripts')
</body>
</html>
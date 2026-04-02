<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — AttendX</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:300,400,500,600|dm-mono:400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['DM Sans','sans-serif'], mono: ['DM Mono','monospace'] },
                    colors: { brand: { 500:'#4B5EFF', 600:'#3340f5', 700:'#2a31e0' } },
                    keyframes: {
                        fadeUp: { from:{ transform:'translateY(20px)', opacity:'0' }, to:{ transform:'translateY(0)', opacity:'1' } },
                        shimmer: { from:{ backgroundPosition:'200% center' }, to:{ backgroundPosition:'-200% center' } },
                    },
                    animation: { 'fade-up':'fadeUp .5s ease-out both', shimmer:'shimmer 4s linear infinite' }
                }
            }
        }
    </script>
    <style>
        * { -webkit-font-smoothing: antialiased; }
        body { background: #020617; }
        .grid-bg {
            background-image: linear-gradient(rgba(75,94,255,.06) 1px,transparent 1px),
                              linear-gradient(90deg,rgba(75,94,255,.06) 1px,transparent 1px);
            background-size: 40px 40px;
        }
        .glow { box-shadow: 0 0 80px rgba(75,94,255,.25), 0 0 200px rgba(75,94,255,.08); }
        .input-field {
            width:100%; background:#0f172a; border:1px solid #1e293b;
            border-radius:10px; padding:11px 14px; font-size:14px;
            color:#e2e8f0; font-family:'DM Sans',sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }
        .input-field:focus { outline:none; border-color:#4B5EFF; box-shadow:0 0 0 3px rgba(75,94,255,.18); }
        .input-field::placeholder { color:#475569; }
        .btn-primary {
            width:100%; background:#4B5EFF; color:white; font-weight:600;
            padding:12px; border-radius:10px; font-size:14px; cursor:pointer;
            transition: background .18s, transform .12s, box-shadow .18s;
            border:none; font-family:'DM Sans',sans-serif;
        }
        .btn-primary:hover { background:#3340f5; box-shadow:0 4px 20px rgba(75,94,255,.4); transform:translateY(-1px); }
        .btn-primary:active { transform:translateY(0); }
    </style>
</head>
<body class="h-full grid-bg flex items-center justify-center font-sans p-4">

    {{-- Background blobs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-purple-600/8 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-sm relative z-10">

        {{-- Logo --}}
        <div class="text-center mb-8 animate-fade-up" style="animation-delay:.05s">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-brand-500 mb-4 glow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    <circle cx="12" cy="16" r="2" fill="white" stroke="none"/>
                </svg>
            </div>
            <h1 class="text-2xl font-semibold text-white tracking-tight">AttendX</h1>
            <p class="text-slate-500 text-sm mt-1">Company Attendance System</p>
        </div>

        {{-- Card --}}
        <div class="bg-slate-900/80 backdrop-blur border border-slate-800 rounded-2xl p-7 animate-fade-up" style="animation-delay:.12s">
            <h2 class="text-lg font-semibold text-white mb-1">Sign in to your account</h2>
            <p class="text-slate-500 text-xs mb-6">Enter your credentials to continue</p>

            @if($errors->any())
            <div class="bg-red-950/60 border border-red-800/60 text-red-300 text-sm px-4 py-3 rounded-lg mb-5 flex gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Email address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="input-field" placeholder="you@company.com" required autofocus>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-medium text-slate-400">Password</label>
                        </div>
                        <input type="password" name="password" class="input-field" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn-primary mt-2">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-5 pt-5 border-t border-slate-800">
                <div class="text-xs text-slate-600 text-center">
                    Default credentials: <span class="font-mono text-slate-500">admin@company.com / password</span>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-700 mt-6">
            AttendX &copy; {{ date('Y') }} — Secure & Compliant
        </p>
    </div>
</body>
</html>
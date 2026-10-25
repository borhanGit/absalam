<!DOCTYPE html>
<html lang="bn" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>লগইন | {{ $company->company_name ?? 'Dhaka Thai & Aluminium Glass House' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden selection:bg-cyan-500 selection:text-slate-950">
    <!-- Glowing background elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-purple-600/5 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md space-y-6">
        <!-- Logo & Branding Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-600 shadow-xl shadow-cyan-500/20 border border-cyan-400/30 text-2xl font-black text-slate-950">
                TH
            </div>
            <h1 class="text-xl font-bold tracking-tight text-white">
                {{ $company->company_name ?? 'Dhaka Thai & Aluminium Glass House' }}
            </h1>
            <p class="text-xs text-slate-400">
                পিওএস কাউন্টার, থাই গ্লাস ডাইমেনশন বিলিং ও ওয়্যারহাউস সফটওয়্যার
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-slate-950/80">
            @if(session('error'))
                <div class="mb-5 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-xs text-rose-400 flex items-center gap-2">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-xs text-emerald-400 flex items-center gap-2">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">ইমেইল ঠিকানা (Email)</label>
                    <div class="relative">
                        <input type="email" id="email_input" name="email" value="{{ old('email', 'admin@thaiglass.com') }}" required autofocus placeholder="name@company.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition font-mono">
                    </div>
                    @error('email')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">পাসওয়ার্ড (Password)</label>
                    <div class="relative">
                        <input type="password" id="password_input" name="password" value="password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition font-mono">
                    </div>
                    @error('password')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-300">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded bg-slate-950 border-slate-800 text-cyan-500 focus:ring-0">
                        <span>মনে রাখুন (Remember me)</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold text-xs shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/30 transition flex items-center justify-center gap-2">
                    <span>🔐</span> সিস্টেমে প্রবেশ করুন
                </button>
            </form>

            <!-- Quick Demo Role Switcher for pairwise testing -->
            <div class="mt-6 pt-5 border-t border-slate-800 space-y-3">
                <div class="text-[11px] font-semibold text-slate-400 text-center">
                    দ্রুত লগইন করার জন্য রোল নির্বাচন করুন (Demo Accounts)
                </div>
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <button type="button" onclick="setCredentials('admin@thaiglass.com', 'password')" class="p-2 rounded-xl bg-slate-950 hover:bg-slate-800 border border-slate-800 hover:border-purple-500/40 text-left transition group">
                        <span class="font-bold text-purple-400 block group-hover:text-purple-300">👑 অ্যাডমিন (Admin)</span>
                        <span class="text-[10px] text-slate-500 block">পূর্ণ এক্সেস ও রিপোর্ট</span>
                    </button>
                    <button type="button" onclick="setCredentials('manager@thaiglass.com', 'password')" class="p-2 rounded-xl bg-slate-950 hover:bg-slate-800 border border-slate-800 hover:border-cyan-500/40 text-left transition group">
                        <span class="font-bold text-cyan-400 block group-hover:text-cyan-300">👔 ম্যানেজার (Manager)</span>
                        <span class="text-[10px] text-slate-500 block">পারচেজ, সেলস ও স্টক</span>
                    </button>
                    <button type="button" onclick="setCredentials('cashier@thaiglass.com', 'password')" class="p-2 rounded-xl bg-slate-950 hover:bg-slate-800 border border-slate-800 hover:border-emerald-500/40 text-left transition group">
                        <span class="font-bold text-emerald-400 block group-hover:text-emerald-300">⚡ ক্যাশিয়ার (Cashier)</span>
                        <span class="text-[10px] text-slate-500 block">পিওএস বিলিং ও রিসিট</span>
                    </button>
                    <button type="button" onclick="setCredentials('storekeeper@thaiglass.com', 'password')" class="p-2 rounded-xl bg-slate-950 hover:bg-slate-800 border border-slate-800 hover:border-amber-500/40 text-left transition group">
                        <span class="font-bold text-amber-400 block group-hover:text-amber-300">📦 স্টোরকিপার (Store)</span>
                        <span class="text-[10px] text-slate-500 block">গোডাউন ও অফ-কাট স্টক</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="text-center text-[11px] text-slate-500">
            &copy; {{ date('Y') }} {{ $company->company_name ?? 'Thai & Aluminium Glass House' }}. All rights reserved.
        </div>
    </div>

    <script>
        function setCredentials(email, password) {
            document.getElementById('email_input').value = email;
            document.getElementById('password_input').value = password;
        }
    </script>
</body>
</html>

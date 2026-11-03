<!DOCTYPE html>
<html lang="bn" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>লগইন | {{ $company->company_name ?? 'Dhaka Thai & Aluminium Glass House' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#070d18] text-[#e2e8f0] font-sans antialiased min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden selection:bg-[#533afd] selection:text-white">
    <!-- DESIGN.md Atmospheric Subtle Mesh Backdrop -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10 mesh-backdrop">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#533afd]/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-[#1c1e54]/30 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md space-y-6">
        <!-- Logo & Branding Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#533afd] shadow-lg shadow-[#533afd]/25 text-white">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3h18v18H3zM3 9h18M9 21V9"/>
                </svg>
            </div>
            <h1 class="text-xl font-semibold tracking-tight text-white">
                {{ $company->company_name ?? 'Dhaka Thai & Aluminium Glass House' }}
            </h1>
            <p class="text-xs text-[#64748d]">
                পিওএস কাউন্টার, থাই গ্লাস ডাইমেনশন বিলিং ও ওয়্যারহাউস ইআরপি
            </p>
        </div>

        <!-- Login Card -->
        <div class="card-elevated p-6 sm:p-8 backdrop-blur-xl">
            @if(session('error'))
                <div class="mb-5 p-3 rounded-lg bg-rose-500/10 border border-rose-500/25 text-xs text-rose-300 flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-rose-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/25 text-xs text-emerald-300 flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-emerald-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">ইমেইল ঠিকানা (Email)</label>
                    <input type="email" id="email_input" name="email" value="{{ old('email', 'admin@thaiglass.com') }}" required autofocus placeholder="name@company.com" class="w-full bg-[#070d18] border border-[#e3e8ee]/15 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-[#64748d] focus:outline-none focus:border-[#533afd] focus:ring-1 focus:ring-[#533afd] transition font-mono">
                    @error('email')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">পাসওয়ার্ড (Password)</label>
                    <input type="password" id="password_input" name="password" value="password" required placeholder="••••••••" class="w-full bg-[#070d18] border border-[#e3e8ee]/15 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-[#64748d] focus:outline-none focus:border-[#533afd] focus:ring-1 focus:ring-[#533afd] transition font-mono">
                    @error('password')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-[#64748d] hover:text-slate-300">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-[#070d18] border-[#e3e8ee]/20 text-[#533afd] focus:ring-0">
                        <span>মনে রাখুন (Remember me)</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-full bg-[#533afd] hover:bg-[#4434d4] text-white font-medium text-xs shadow-md shadow-[#533afd]/30 transition flex items-center justify-center gap-2 min-h-[42px]">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <span>সিস্টেমে প্রবেশ করুন</span>
                </button>
            </form>
        </div>

        <div class="text-center text-[11px] text-[#64748d]">
            &copy; {{ date('Y') }} {{ $company->company_name ?? 'Thai & Aluminium Glass House' }}. All rights reserved.
        </div>
    </div>

    <!-- Spinner script for login submit -->
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const btn = this.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
                btn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>লগইন হচ্ছে...</span>
                `;
            }
        });
    </script>
</body>
</html>

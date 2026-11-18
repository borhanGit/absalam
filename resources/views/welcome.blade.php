<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $company->company_name ?? __('app.app_name') }}</title>
    
    <!-- Instant Theme Detection Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'light' || (!savedTheme && !prefersDark)) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
            } else {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            }
        })();
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-[#f6f9fc] dark:bg-[#0a2540] text-slate-800 dark:text-slate-100 antialiased font-sans transition-colors duration-200">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 border-b border-[#e3e8ee] dark:border-[#e3e8ee]/10 bg-white/80 dark:bg-[#0a2540]/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#533afd] border border-[#533afd]/30 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        {{ mb_substr($company->company_name ?? 'T', 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white">{{ $company->company_name ?? __('app.company_name_default') }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-[#533afd]/10 text-[#533afd] dark:text-[#a1a1fe] border border-[#533afd]/20">
                                Thai POS
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $company->address ?? __('Mirpur, Dhaka') }} • {{ $company->phone ?? '01700-000000' }}</p>
                    </div>
                </div>

                <!-- Environment & Auth Links -->
                <div class="flex items-center gap-3 text-xs">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary inline-flex items-center gap-1.5">
                            {{ __('Enter Dashboard') }}
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary inline-flex items-center gap-1.5">
                            {{ __('Login') }}
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    @endauth

                    <!-- Language Switcher (বাং / EN) -->
                    <div class="flex items-center rounded-full bg-slate-100 dark:bg-[#0d253d] border border-slate-200 dark:border-[#e3e8ee]/10 p-0.5 text-[11px] font-semibold" title="{{ __('app.switch_language') }}">
                        <a href="{{ route('locale.switch', 'bn') }}" 
                           class="px-2.5 py-1 rounded-full transition {{ app()->getLocale() === 'bn' ? 'bg-[#533afd] text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                            বাং
                        </a>
                        <a href="{{ route('locale.switch', 'en') }}" 
                           class="px-2.5 py-1 rounded-full transition {{ app()->getLocale() === 'en' ? 'bg-[#533afd] text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                            EN
                        </a>
                    </div>

                    <!-- Theme Toggle Button -->
                    <button type="button" onclick="toggleTheme()" class="btn-secondary !p-2 !min-h-[36px] !rounded-full relative" title="{{ __('app.toggle_theme') }}" aria-label="Toggle Theme">
                        <svg id="themeIconSun" class="w-4 h-4 text-amber-400 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                        </svg>
                        <svg id="themeIconMoon" class="w-4 h-4 text-[#533afd] hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Welcome Banner / Hero -->
        <div class="card-shell p-6 md:p-8 relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#533afd]/10 text-[#533afd] dark:text-[#a1a1fe] border border-[#533afd]/20 mb-3">
                        {{ __('Bangladesh Aluminium & Thai Industry ERP') }}
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ __('Thai Aluminium & Glass Inventory & POS System') }}
                    </h1>
                    <p class="mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed">
                        {{ __('Integrated software for running feet/kg calculation, glass inch-sqft calculator, cutting off-cuts & scrap management, contractor commission, and cheque transactions.') }}
                    </p>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <a href="#calculator" class="btn-primary">
                        {{ __('Glass & Section Calculator') }}
                    </a>
                    <a href="#modules" class="btn-secondary">
                        {{ __('Feature Modules') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Real-Time Metrics & Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Metric 1: Total Stock Products -->
            <div class="card-shell p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider font-semibold text-slate-500 dark:text-slate-400">{{ __('Total Products') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-[#533afd]/10 border border-[#533afd]/20 text-[#533afd] dark:text-[#a1a1fe] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-slate-900 dark:text-white font-mono tnum">{{ $stats['total_products'] }}</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('Types') }}</span>
                </div>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('Profile') }}: {{ $stats['profile_count'] }} | {{ __('Glass') }}: {{ $stats['glass_count'] }}</p>
            </div>

            <!-- Metric 2: Total Stock Qty -->
            <div class="card-shell p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider font-semibold text-emerald-600 dark:text-emerald-400">{{ __('Total Stock Quantity') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-slate-900 dark:text-white font-mono tnum">{{ number_format($stats['total_stock_qty'], 0) }}</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('Bar / SFT / Pcs') }}</span>
                </div>
                <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">{{ __('Stored in Warehouses') }}</p>
            </div>

            <!-- Metric 3: Customer Due Receivables -->
            <div class="card-shell p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider font-semibold text-amber-600 dark:text-amber-400">{{ __('Customer Total Receivables') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-amber-600 dark:text-amber-400 font-mono tnum">৳{{ number_format($stats['customer_receivables'], 2) }}</span>
                </div>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $stats['total_customers'] }} {{ __('Customers / Contractors') }}</p>
            </div>

            <!-- Metric 4: Supplier Payables -->
            <div class="card-shell p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider font-semibold text-rose-600 dark:text-rose-400">{{ __('Mill & Supplier Payables') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-rose-600 dark:text-rose-400 font-mono tnum">৳{{ number_format($stats['supplier_payables'], 2) }}</span>
                </div>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $stats['total_suppliers'] }} {{ __('Mills / Companies') }}</p>
            </div>
        </div>

        <!-- Interactive Glass & Profile Calculator Widget -->
        <section id="calculator" class="card-shell p-6">
            <div class="border-b border-slate-200 dark:border-slate-800 pb-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Thai Glass & Aluminium Measurement Calculator (Live Tool)') }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Automatically calculate square feet from glass height & width (inches) and profile feet/kg.') }}</p>
                </div>
                <div class="inline-flex rounded-xl bg-slate-100 dark:bg-slate-900 p-1 border border-slate-200 dark:border-slate-800 text-xs">
                    <button id="btnGlassTab" onclick="switchCalc('glass')" class="px-3 py-1.5 rounded-lg font-semibold bg-[#533afd] text-white transition">
                        {{ __('Glass / SFT') }}
                    </button>
                    <button id="btnProfileTab" onclick="switchCalc('profile')" class="px-3 py-1.5 rounded-lg font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                        {{ __('Profile / RFT & Kg') }}
                    </button>
                </div>
            </div>

            <!-- Glass Calculator Form -->
            <div id="glassCalc" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Height (Inches)') }}</label>
                    <input type="number" id="glassHeight" value="54" step="0.25" oninput="calculateGlass()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700/80 text-slate-900 dark:text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Width (Inches)') }}</label>
                    <input type="number" id="glassWidth" value="36" step="0.25" oninput="calculateGlass()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700/80 text-slate-900 dark:text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Glass Type & Rate (per SFT)') }}</label>
                    <select id="glassType" onchange="calculateGlass()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700/80 text-slate-900 dark:text-white text-xs focus:border-[#533afd] focus:outline-none">
                        <option value="70">5mm Clear Glass (৳70/SFT)</option>
                        <option value="85">5mm Tinted Dark Black (৳85/SFT)</option>
                        <option value="120">8mm Tempered Clear (৳120/SFT)</option>
                        <option value="150">10mm Tempered Glass (৳150/SFT)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Quantity (Pcs)') }}</label>
                    <input type="number" id="glassQty" value="4" min="1" oninput="calculateGlass()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700/80 text-slate-900 dark:text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                </div>

                <!-- Live Glass Result Display -->
                <div class="md:col-span-4 mt-2 p-4 rounded-xl bg-slate-50 dark:bg-slate-950/80 border border-[#533afd]/20 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 block font-medium">{{ __('Per Piece Size:') }}</span>
                            <span id="singleSqft" class="text-lg font-bold text-[#533afd] dark:text-[#a1a1fe] font-mono tnum">13.50 SFT</span>
                        </div>
                        <div class="border-l border-slate-200 dark:border-slate-800 pl-6">
                            <span class="text-xs text-slate-500 dark:text-slate-400 block font-medium">{{ __('Total Square Feet') }} (4 {{ __('Pcs') }}):</span>
                            <span id="totalSqft" class="text-xl font-bold text-slate-900 dark:text-white font-mono tnum">54.00 SFT</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-500 dark:text-slate-400 block font-medium">{{ __('Estimated Total Price:') }}</span>
                        <span id="totalGlassPrice" class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 font-mono tnum">৳3,780.00</span>
                    </div>
                </div>
            </div>

            <!-- Profile Calculator Form -->
            <div id="profileCalc" class="hidden grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Select Profile Item') }}</label>
                    <select id="profileItem" onchange="calculateProfile()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700/80 text-slate-900 dark:text-white text-xs focus:border-[#533afd] focus:outline-none">
                        <option value="4.25|2300">4" Sliding Bottom (1.2mm SS - 4.25kg/bar)</option>
                        <option value="3.80|2100">4" Sliding Top (1.2mm SS - 3.80kg/bar)</option>
                        <option value="3.20|1800">4" Sliding Interlock (1.2mm SS - 3.20kg/bar)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Number of Bars (1 Bar = 16 Feet)') }}</label>
                    <input type="number" id="profileBars" value="10" min="1" oninput="calculateProfile()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700/80 text-slate-900 dark:text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Cutting Length (Feet)') }}</label>
                    <input type="number" id="cutLengthFeet" value="10" step="0.5" oninput="calculateProfile()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700/80 text-slate-900 dark:text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                </div>
                <div>
                    <span class="text-xs text-slate-500 dark:text-slate-400 block mb-1">{{ __('Stock Off-Cut Reuse') }}</span>
                    <span id="offCutNote" class="text-xs font-medium text-amber-600 dark:text-amber-300 block py-2.5">{{ __('Remaining off-cut per bar') }}</span>
                </div>

                <!-- Live Profile Result Display -->
                <div class="md:col-span-4 mt-2 p-4 rounded-xl bg-slate-50 dark:bg-slate-950/80 border border-[#533afd]/20 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 block font-medium">{{ __('Total Running Feet (RFT):') }}</span>
                            <span id="totalRft" class="text-lg font-bold text-[#533afd] dark:text-[#a1a1fe] font-mono tnum">160 RFT</span>
                        </div>
                        <div class="border-l border-slate-200 dark:border-slate-800 pl-6">
                            <span class="text-xs text-slate-500 dark:text-slate-400 block font-medium">{{ __('Total Est. Weight (Kg):') }}</span>
                            <span id="totalWeight" class="text-xl font-bold text-slate-900 dark:text-white font-mono tnum">42.50 Kg</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-500 dark:text-slate-400 block font-medium">{{ __('Estimated Sale Price:') }}</span>
                        <span id="totalProfilePrice" class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 font-mono tnum">৳23,000.00</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- System Modules Overview -->
        <section id="modules" class="space-y-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Modules & Architecture Overview') }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Features built for aluminium and glass business workflows') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Module 1: POS & Invoicing -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-[#533afd]/10 border border-[#533afd]/20 text-[#533afd] dark:text-[#a1a1fe] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Sales / POS') }}</h3>
                    </div>
                    <ul class="text-xs text-slate-600 dark:text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Barcode & quick product click sales interface') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Glass SFT and section RFT/Kg measurement inputs') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Carrying & labour charges included in invoices') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Contractor commission tracking and ledgers') }}</span></li>
                    </ul>
                </div>

                <!-- Module 2: Purchase & Carrying Cost -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Purchase & Carrying Cost') }}</h3>
                    </div>
                    <ul class="text-xs text-slate-600 dark:text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Mill challan entry with weight and bar specifications') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Truck freight and unloading labour cost addition') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Supplier payables and payment history statement') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Purchase return management') }}</span></li>
                    </ul>
                </div>

                <!-- Module 3: Cheque Management -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-[#533afd]/10 border border-[#533afd]/20 text-[#533afd] dark:text-[#a1a1fe] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Cheque Management') }}</h3>
                    </div>
                    <ul class="text-xs text-slate-600 dark:text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Cheques received from customers and issued to suppliers') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('In-Hand, Deposited, and Honored lifecycle tracking') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Cheque bounce and dishonored treatment') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Post-dated cheque clearance notifications') }}</span></li>
                    </ul>
                </div>

                <!-- Module 4: Job Quotation / Window Estimator -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-[#533afd]/10 border border-[#533afd]/20 text-[#533afd] dark:text-[#a1a1fe] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Job Quotation / Window Estimator') }}</h3>
                    </div>
                    <ul class="text-xs text-slate-600 dark:text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('2-Track & 3-Track sliding window estimator') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Section, glass, lock, wheels and labour calculation') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Official quotation for building and flat projects') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('One-click conversion from quotation to sales invoice') }}</span></li>
                    </ul>
                </div>

                <!-- Module 5: Off-cut & Scrap Stock -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-[#533afd]/10 border border-[#533afd]/20 text-[#533afd] dark:text-[#a1a1fe] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L10.757 9.88m0 0l2.828-2.829a3 3 0 114.243 4.243L14.12 14.12" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Cutting Off-cut & Scrap Stock') }}</h3>
                    </div>
                    <ul class="text-xs text-slate-600 dark:text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Leftover cutting off-cuts tracked in dedicated inventory') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Reuse off-cuts in subsequent customer jobs') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Scrap / bhangari sales by weight') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('3 Warehouses (Showroom, Godown, Workshop) balance') }}</span></li>
                    </ul>
                </div>

                <!-- Module 6: Accounts & Reports -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-[#533afd]/10 border border-[#533afd]/20 text-[#533afd] dark:text-[#a1a1fe] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Accounts & Financial Reports') }}</h3>
                    </div>
                    <ul class="text-xs text-slate-600 dark:text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Daily cash book & receipt-payment statements') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Customer & supplier running ledgers') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Categorized store expenses (Rent, Electricity, Salary)') }}</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>{{ __('Trial Balance, Profit & Loss statement & Balance Sheet') }}</span></li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Master Data Tables (Live Seeded Preview) -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Table 1: Seeded Products -->
            <div class="card-shell p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Sample Products Master') }}</h3>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $products->count() }} {{ __('Items') }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100 dark:bg-slate-900/60 text-slate-600 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="p-3">{{ __('Product Name') }}</th>
                                <th class="p-3">{{ __('Category') }}</th>
                                <th class="p-3">{{ __('Unit') }}</th>
                                <th class="p-3">{{ __('Retail Price') }}</th>
                                <th class="p-3">{{ __('Mistri Price') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                            @foreach ($products->take(6) as $p)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                    <td class="p-3 font-semibold text-slate-900 dark:text-white">{{ $p->name }}</td>
                                    <td class="p-3 text-slate-500 dark:text-slate-400">{{ $p->category->name ?? '-' }}</td>
                                    <td class="p-3">
                                        <span class="inline-flex px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono text-[11px] border border-slate-200 dark:border-slate-700">
                                            {{ $p->unit->code }}
                                        </span>
                                    </td>
                                    <td class="p-3 font-bold font-mono tnum text-emerald-600 dark:text-emerald-400">৳{{ number_format($p->selling_price_retail, 2) }}</td>
                                    <td class="p-3 font-mono tnum text-amber-600 dark:text-amber-300">৳{{ number_format($p->selling_price_contractor, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table 2: Parties & Warehouses -->
            <div class="space-y-4">
                <!-- Warehouses -->
                <div class="card-shell p-5">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">{{ __('Storage & Warehouses') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        @foreach ($warehouses as $wh)
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800">
                                <span class="text-xs font-semibold text-slate-900 dark:text-white block">{{ $wh->name }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-0.5">{{ $wh->address }}</span>
                                @if($wh->is_primary)
                                    <span class="mt-1.5 inline-block text-[10px] font-bold text-[#533afd] dark:text-[#a1a1fe]">★ {{ __('Primary Branch') }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Customer & Mistri Ledger Snapshot -->
                <div class="card-shell p-5">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">{{ __('Customer & Contractor Ledger Snapshot') }}</h3>
                    <div class="space-y-2 text-xs">
                        @foreach ($customers as $c)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800">
                                <div>
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $c->name }}</span>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px] font-mono mt-0.5">{{ $c->phone }} • {{ ucfirst($c->customer_type) }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-slate-500 dark:text-slate-400 block text-[10px] uppercase font-semibold">{{ __('Current Balance') }}</span>
                                    <span class="font-bold font-mono tnum {{ $c->current_balance > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                        ৳{{ number_format($c->current_balance, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="border-t border-[#e3e8ee] dark:border-slate-800/80 bg-white dark:bg-slate-950 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p>© 2026 {{ $company->company_name ?? __('Thai POS') }}. Powered by Laravel 13, MySQL 8.4, Docker Sail & Tailwind CSS v4.</p>
        </div>
    </footer>

    <!-- Interactive Calculator Logic -->
    <script>
        function switchCalc(type) {
            const glassCalc = document.getElementById('glassCalc');
            const profileCalc = document.getElementById('profileCalc');
            const btnGlass = document.getElementById('btnGlassTab');
            const btnProfile = document.getElementById('btnProfileTab');

            if (type === 'glass') {
                glassCalc.classList.remove('hidden');
                profileCalc.classList.add('hidden');
                btnGlass.classList.add('bg-[#533afd]', 'text-white');
                btnGlass.classList.remove('text-slate-500', 'dark:text-slate-400');
                btnProfile.classList.remove('bg-[#533afd]', 'text-white');
                btnProfile.classList.add('text-slate-500', 'dark:text-slate-400');
            } else {
                glassCalc.classList.add('hidden');
                profileCalc.classList.remove('hidden');
                btnProfile.classList.add('bg-[#533afd]', 'text-white');
                btnProfile.classList.remove('text-slate-500', 'dark:text-slate-400');
                btnGlass.classList.remove('bg-[#533afd]', 'text-white');
                btnGlass.classList.add('text-slate-500', 'dark:text-slate-400');
            }
        }

        function calculateGlass() {
            const h = parseFloat(document.getElementById('glassHeight').value) || 0;
            const w = parseFloat(document.getElementById('glassWidth').value) || 0;
            const rate = parseFloat(document.getElementById('glassType').value) || 0;
            const qty = parseInt(document.getElementById('glassQty').value) || 1;

            const singleSqft = (h * w) / 144;
            const totalSqft = singleSqft * qty;
            const totalPrice = totalSqft * rate;

            document.getElementById('singleSqft').innerText = singleSqft.toFixed(2) + ' SFT';
            document.getElementById('totalSqft').innerText = totalSqft.toFixed(2) + ' SFT';
            document.getElementById('totalGlassPrice').innerText = '৳' + totalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function calculateProfile() {
            const itemVal = document.getElementById('profileItem').value.split('|');
            const weightPerBar = parseFloat(itemVal[0]);
            const pricePerBar = parseFloat(itemVal[1]);
            const bars = parseInt(document.getElementById('profileBars').value) || 1;
            const cutFeet = parseFloat(document.getElementById('cutLengthFeet').value) || 16;

            const totalRft = bars * 16;
            const totalWeight = bars * weightPerBar;
            const totalPrice = bars * pricePerBar;
            const offCutFeet = Math.max(0, 16 - cutFeet);

            document.getElementById('totalRft').innerText = totalRft + ' RFT';
            document.getElementById('totalWeight').innerText = totalWeight.toFixed(2) + ' Kg';
            document.getElementById('totalProfilePrice').innerText = '৳' + totalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('offCutNote').innerText = offCutFeet > 0 
                ? '{{ __("Remaining off-cut per bar:") }} ' + offCutFeet.toFixed(1) + ' {{ __("Feet") }}' 
                : '{{ __("No off-cut (Full Bar)") }}';
        }

        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            const sunIcon = document.getElementById('themeIconSun');
            const moonIcon = document.getElementById('themeIconMoon');
            if (sunIcon && moonIcon) {
                if (isDark) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcons();
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateThemeIcons();
            calculateGlass();
            calculateProfile();
        });
    </script>
</body>
</html>
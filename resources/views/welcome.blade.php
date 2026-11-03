<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $company->company_name ?? 'Aluminium & Thai POS' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-slate-950 text-slate-100 antialiased font-sans">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 border border-indigo-500/30 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        {{ mb_substr($company->company_name ?? 'T', 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-bold tracking-tight text-white">{{ $company->company_name ?? 'Thai & Glass House' }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                Thai POS
                            </span>
                        </div>
                        <p class="text-xs text-slate-400">{{ $company->address ?? 'মিরপুর, ঢাকা' }} • {{ $company->phone ?? '০১৭০০-০০০০০০' }}</p>
                    </div>
                </div>

                <!-- Environment & Auth Links -->
                <div class="flex items-center gap-3 text-xs">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary inline-flex items-center gap-1.5">
                            ড্যাশবোর্ডে প্রবেশ
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary inline-flex items-center gap-1.5">
                            লগইন করুন
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    @endauth
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
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 mb-3">
                        Bangladesh Aluminium & Thai Industry ERP
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                        থাই অ্যালুমিনিয়াম ও গ্লাস ইনভেন্টরি & পিওএস সিস্টেম
                    </h1>
                    <p class="mt-2 text-sm sm:text-base text-slate-300 leading-relaxed">
                        সেকশনের রানিং ফুট/কেজি হিসাব, কাঁচের ইঞ্চি-স্কয়ারফিট ক্যালকুলেটর, কাটিং অফ-কাট ও স্ক্র্যাপ ম্যানেজমেন্ট, মিস্ত্রি কমিশন এবং চেক লেনদেনের সমন্বিত সফটওয়্যার।
                    </p>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <a href="#calculator" class="btn-primary">
                        গ্লাস & সেকশন ক্যালকুলেটর
                    </a>
                    <a href="#modules" class="btn-secondary">
                        ফিচার মডিউলসমূহ
                    </a>
                </div>
            </div>
        </div>

        <!-- Real-Time Metrics & Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Metric 1: Total Stock Products -->
            <div class="card-shell p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider font-semibold text-slate-400">মোট পণ্য (আইটেম)</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-white font-mono tnum">{{ $stats['total_products'] }}</span>
                    <span class="text-xs text-slate-400">প্রকার</span>
                </div>
                <p class="mt-1 text-xs text-slate-500">সেকশন: {{ $stats['profile_count'] }} | গ্লাস: {{ $stats['glass_count'] }}</p>
            </div>

            <!-- Metric 2: Total Stock Qty -->
            <div class="card-shell p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider font-semibold text-emerald-400">মোট মজুদ পরিমাণ</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-white font-mono tnum">{{ number_format($stats['total_stock_qty'], 0) }}</span>
                    <span class="text-xs text-slate-400">বার/SFT/Pcs</span>
                </div>
                <p class="mt-1 text-xs text-emerald-400">৩টি ওয়্যারহাউসে সংরক্ষিত</p>
            </div>

            <!-- Metric 3: Customer Due Receivables -->
            <div class="card-shell p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider font-semibold text-amber-400">কাস্টমার মোট বাকি</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-amber-400 font-mono tnum">৳{{ number_format($stats['customer_receivables'], 2) }}</span>
                </div>
                <p class="mt-1 text-xs text-slate-400">{{ $stats['total_customers'] }} জন কাস্টমার/মিস্ত্রি</p>
            </div>

            <!-- Metric 4: Supplier Payables -->
            <div class="card-shell p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider font-semibold text-rose-400">মিল ও সাপ্লায়ার দেনা</span>
                    <div class="w-8 h-8 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-rose-400 font-mono tnum">৳{{ number_format($stats['supplier_payables'], 2) }}</span>
                </div>
                <p class="mt-1 text-xs text-slate-400">{{ $stats['total_suppliers'] }}টি মিল/কোম্পানি</p>
            </div>
        </div>

        <!-- Interactive Glass & Profile Calculator Widget -->
        <section id="calculator" class="card-shell p-6">
            <div class="border-b border-slate-800 pb-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight">থাই গ্লাস ও অ্যালুমিনিয়াম পরিমাপ ক্যালকুলেটর (Live Tool)</h2>
                    <p class="text-xs text-slate-400 mt-1">কাঁচের দৈর্ঘ্য-প্রস্থ (ইঞ্চি) থেকে অটোমেটিক স্কয়ার ফিট এবং সেকশনের ফুট/কেজি হিসাব করুন</p>
                </div>
                <div class="inline-flex rounded-xl bg-slate-900 p-1 border border-slate-800 text-xs">
                    <button id="btnGlassTab" onclick="switchCalc('glass')" class="px-3 py-1.5 rounded-lg font-semibold bg-indigo-600 text-white transition">
                        কাঁচ / Glass SFT
                    </button>
                    <button id="btnProfileTab" onclick="switchCalc('profile')" class="px-3 py-1.5 rounded-lg font-semibold text-slate-400 hover:text-white transition">
                        সেকশন / Profile RFT & Kg
                    </button>
                </div>
            </div>

            <!-- Glass Calculator Form -->
            <div id="glassCalc" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">উচ্চতা / Height (ইঞ্চি)</label>
                    <input type="number" id="glassHeight" value="54" step="0.25" oninput="calculateGlass()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs font-mono tnum focus:border-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">প্রস্থ / Width (ইঞ্চি)</label>
                    <input type="number" id="glassWidth" value="36" step="0.25" oninput="calculateGlass()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs font-mono tnum focus:border-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">গ্লাসের ধরণ ও রেট (প্রতি SFT)</label>
                    <select id="glassType" onchange="calculateGlass()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:border-indigo-500 focus:outline-none">
                        <option value="70">5mm Clear Glass (৳70/SFT)</option>
                        <option value="85">5mm Tinted Dark Black (৳85/SFT)</option>
                        <option value="120">8mm Tempered Clear (৳120/SFT)</option>
                        <option value="150">10mm Tempered Glass (৳150/SFT)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">পরিমাণ (সংখ্যা / Pcs)</label>
                    <input type="number" id="glassQty" value="4" min="1" oninput="calculateGlass()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs font-mono tnum focus:border-indigo-500 focus:outline-none">
                </div>

                <!-- Live Glass Result Display -->
                <div class="md:col-span-4 mt-2 p-4 rounded-xl bg-slate-950/80 border border-indigo-500/20 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">১ পিসের মাপ:</span>
                            <span id="singleSqft" class="text-lg font-bold text-indigo-400 font-mono tnum">13.50 SFT</span>
                        </div>
                        <div class="border-l border-slate-800 pl-6">
                            <span class="text-xs text-slate-400 block font-medium">মোট স্কয়ার ফিট (৪ পিস):</span>
                            <span id="totalSqft" class="text-xl font-bold text-white font-mono tnum">54.00 SFT</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block font-medium">আনুমানিক সর্বমোট মূল্য:</span>
                        <span id="totalGlassPrice" class="text-2xl font-bold text-emerald-400 font-mono tnum">৳3,780.00</span>
                    </div>
                </div>
            </div>

            <!-- Profile Calculator Form -->
            <div id="profileCalc" class="hidden grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">সেকশন আইটেম নির্বাচন করুন</label>
                    <select id="profileItem" onchange="calculateProfile()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:border-indigo-500 focus:outline-none">
                        <option value="4.25|2300">4" Sliding Bottom (1.2mm SS - 4.25kg/bar)</option>
                        <option value="3.80|2100">4" Sliding Top (1.2mm SS - 3.80kg/bar)</option>
                        <option value="3.20|1800">4" Sliding Interlock (1.2mm SS - 3.20kg/bar)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">বারের সংখ্যা (১ বার = ১৬ ফুট)</label>
                    <input type="number" id="profileBars" value="10" min="1" oninput="calculateProfile()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs font-mono tnum focus:border-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">কাটিং মাপ (নির্দিষ্ট ফুট)</label>
                    <input type="number" id="cutLengthFeet" value="10" step="0.5" oninput="calculateProfile()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs font-mono tnum focus:border-indigo-500 focus:outline-none">
                </div>
                <div>
                    <span class="text-xs text-slate-400 block mb-1">স্টক অফ-কাট রিইউজ</span>
                    <span id="offCutNote" class="text-xs font-medium text-amber-300 block py-2.5">প্রতি বারে ৬ ফুট অফ-কাট অবশিষ্ট থাকবে</span>
                </div>

                <!-- Live Profile Result Display -->
                <div class="md:col-span-4 mt-2 p-4 rounded-xl bg-slate-950/80 border border-indigo-500/20 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">মোট রানিং ফুট (RFT):</span>
                            <span id="totalRft" class="text-lg font-bold text-indigo-400 font-mono tnum">160 RFT</span>
                        </div>
                        <div class="border-l border-slate-800 pl-6">
                            <span class="text-xs text-slate-400 block font-medium">মোট সম্ভাব্য ওজন (Kg):</span>
                            <span id="totalWeight" class="text-xl font-bold text-white font-mono tnum">42.50 Kg</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block font-medium">আনুমানিক বিক্রয় মূল্য:</span>
                        <span id="totalProfilePrice" class="text-2xl font-bold text-emerald-400 font-mono tnum">৳23,000.00</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- System Modules Overview -->
        <section id="modules" class="space-y-4">
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">মডিউল ও আর্কিটেকচার ওভারভিউ</h2>
                <p class="text-xs text-slate-400">আপনার স্পেসিফিকেশন ও অ্যালুমিনিয়াম ব্যবসার প্রয়োজনীয়তায় নির্মিত ফিচারসমূহ</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Module 1: POS & Invoicing -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-white text-sm">সেলস & পিওএস (Sales / POS)</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>বারকোড ও কুইক মাউস ক্লিক সেলস অপশন</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>গ্লাস SFT এবং সেকশন RFT/Kg পরিমাপ ইনপুট</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>ভ্যান/পিকআপ ভাড়া ও লেবার ফি চালানে যুক্ত করা</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>থাই মিস্ত্রি কমিশন অটোমেটিক ট্র্যাক ও লেজার</span></li>
                    </ul>
                </div>

                <!-- Module 2: Purchase & Carrying Cost -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-white text-sm">পারচেজ & কেরিইং কস্ট</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>কাই, ঢাকা থাই বা পিএইচপি ফ্যাক্টরি চালান এন্ট্রি</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>ট্রাক/পিকআপ ভাড়া ও আনলোডিং কুলি খরচ যুক্ত করা</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>সাপ্লায়ার বাকি ও পেমেন্ট হিস্ট্রি খাতা</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>পারচেজ রিটার্ন (আংশিক বা সম্পূর্ণ চালান)</span></li>
                    </ul>
                </div>

                <!-- Module 3: Cheque Management -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-white text-sm">চেক ম্যানেজমেন্ট (Cheques)</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>কাস্টমার থেকে প্রাপ্ত চেক ও সাপ্লায়ারকে দেওয়া চেক</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>In-Hand, Deposited, এবং Honored ট্র্যাকিং</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>চেক বাউন্স / ডিসঅনার ও রিটার্ন ট্রিটমেন্ট</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>পোস্ট-ডেটেড চেক ও ক্লিয়ারেন্স অ্যালার্ট</span></li>
                    </ul>
                </div>

                <!-- Module 4: Job Quotation / Window Estimator -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-white text-sm">জানালা/দরজা জব কোটেশন</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>২-ট্র্যাক ও ৩-ট্র্যাক স্লাইডিং জানালা এস্টিমেটর</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>সেকশন, কাঁচ, লক ও হুইল এবং লেবার একত্র হিসাব</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>বিল্ডিং বা ফ্ল্যাট প্রজেক্ট অনুযায়ী অফিশিয়াল কোটেশন</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>এক ক্লিকে কোটেশন থেকে সেলস ইনভয়েসে রূপান্তর</span></li>
                    </ul>
                </div>

                <!-- Module 5: Off-cut & Scrap Stock -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L10.757 9.88m0 0l2.828-2.829a3 3 0 114.243 4.243L14.12 14.12" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-white text-sm">কাটিং অফ-কাট & স্ক্র্যাপ স্টক</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>কাটা অবশিষ্ট টুকরা (Off-cut) আলাদা স্টকে রাখা</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>পরবর্তী অর্ডারে অফ-কাট রিইউজ করে মুনাফা বাড়ানো</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>বাতিল ছোট টুকরো 'ভাঙারি/স্ক্র্যাপ' হিসেবে ওজন ও সেলস</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>৩টি ওয়্যারহাউস (শোরুম, গোডাউন, ওয়ার্কশপ) ব্যালেন্স</span></li>
                    </ul>
                </div>

                <!-- Module 6: Accounts & Reports -->
                <div class="card-shell p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-white text-sm">একাউন্টস & ফাইন্যান্সিয়াল রিপোর্ট</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>দৈনিক ক্যাশ বুক ও রিসিট-পেমেন্ট স্টেটমেন্ট</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>কাস্টমার ও সাপ্লায়ার রানিং লেজার স্টেটমেন্ট</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>গ্রুপভিত্তিক দোকান খরচ (ভাড়া, বিদ্যুৎ, বেতন, আপ্যায়ন)</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>ট্রায়াল ব্যালেন্স, ইনকাম স্টেটমেন্ট ও ব্যালেন্স শিট</span></li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Master Data Tables (Live Seeded Preview) -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Table 1: Seeded Products -->
            <div class="card-shell p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-white">ইনভেন্টরি প্রোডাক্ট মাস্টার (Sample Seeded)</h3>
                    <span class="text-xs text-slate-400 font-mono">{{ $products->count() }} টি পণ্য</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                            <tr>
                                <th class="p-3">পণ্যের নাম</th>
                                <th class="p-3">ক্যাটাগরি</th>
                                <th class="p-3">ইউনিট</th>
                                <th class="p-3">খুচরা মূল্য</th>
                                <th class="p-3">মিস্ত্রি মূল্য</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-slate-300">
                            @foreach ($products->take(6) as $p)
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="p-3 font-semibold text-white">{{ $p->name }}</td>
                                    <td class="p-3 text-slate-400">{{ $p->category->name ?? '-' }}</td>
                                    <td class="p-3">
                                        <span class="inline-flex px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 font-mono text-[11px] border border-slate-700">
                                            {{ $p->unit->code }}
                                        </span>
                                    </td>
                                    <td class="p-3 font-bold font-mono tnum text-emerald-400">৳{{ number_format($p->selling_price_retail, 2) }}</td>
                                    <td class="p-3 font-mono tnum text-amber-300">৳{{ number_format($p->selling_price_contractor, 2) }}</td>
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
                    <h3 class="text-sm font-bold text-white mb-3">স্টোরেজ ও ওয়্যারহাউসসমূহ</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        @foreach ($warehouses as $wh)
                            <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800">
                                <span class="text-xs font-semibold text-white block">{{ $wh->name }}</span>
                                <span class="text-[11px] text-slate-400 block mt-0.5">{{ $wh->address }}</span>
                                @if($wh->is_primary)
                                    <span class="mt-1.5 inline-block text-[10px] font-bold text-indigo-400">★ প্রধান শাখা</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Customer & Mistri Ledger Snapshot -->
                <div class="card-shell p-5">
                    <h3 class="text-sm font-bold text-white mb-3">কাস্টমার ও থাই মিস্ত্রি লেজার স্ন্যাপশট</h3>
                    <div class="space-y-2 text-xs">
                        @foreach ($customers as $c)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-950/80 border border-slate-800">
                                <div>
                                    <span class="font-semibold text-white">{{ $c->name }}</span>
                                    <span class="text-slate-400 block text-[11px] font-mono mt-0.5">{{ $c->phone }} • {{ ucfirst($c->customer_type) }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">বর্তমান বকেয়া</span>
                                    <span class="font-bold font-mono tnum {{ $c->current_balance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
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
    <footer class="border-t border-slate-800/80 bg-slate-950 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p>© 2026 {{ $company->company_name ?? 'Thai POS' }}. Powered by Laravel 13, MySQL 8.4, Docker Sail & Tailwind CSS v4.</p>
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
                btnGlass.classList.add('bg-indigo-600', 'text-white');
                btnGlass.classList.remove('text-slate-400');
                btnProfile.classList.remove('bg-indigo-600', 'text-white');
                btnProfile.classList.add('text-slate-400');
            } else {
                glassCalc.classList.add('hidden');
                profileCalc.classList.remove('hidden');
                btnProfile.classList.add('bg-indigo-600', 'text-white');
                btnProfile.classList.remove('text-slate-400');
                btnGlass.classList.remove('bg-indigo-600', 'text-white');
                btnGlass.classList.add('text-slate-400');
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
                ? 'প্রতি বারে ' + offCutFeet.toFixed(1) + ' ফুট অফ-কাট অবশিষ্ট থাকবে' 
                : 'কোনো অফ-কাট নেই (ফুল বার)';
        }

        document.addEventListener('DOMContentLoaded', function() {
            calculateGlass();
            calculateProfile();
        });
    </script>
</body>
</html>

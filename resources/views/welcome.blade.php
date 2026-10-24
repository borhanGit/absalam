<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $company->company_name }} | Aluminium & Thai POS</title>
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glow-accent {
            box-shadow: 0 0 25px -5px rgba(14, 165, 233, 0.25);
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-950 text-slate-100 antialiased selection:bg-cyan-500 selection:text-slate-950">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 border-b border-slate-800/80 bg-slate-900/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-cyan-500/20">
                        T
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-bold tracking-tight text-white">{{ $company->company_name }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-cyan-400/10 text-cyan-400 border border-cyan-400/20">
                                Thai POS
                            </span>
                        </div>
                        <p class="text-xs text-slate-400">{{ $company->address }} • 📞 {{ $company->phone }}</p>
                    </div>
                </div>

                <!-- Environment & Status Badges -->
                <div class="hidden md:flex items-center gap-3 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Laravel Sail (Docker)
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/20 font-mono">
                        MySQL 8.4 LTS
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 font-mono">
                        Tailwind v4
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Welcome Banner / Hero -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-slate-900 to-slate-800 border border-slate-800 p-6 md:p-8 shadow-2xl">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-cyan-500/10 text-cyan-300 border border-cyan-500/20 mb-3">
                        ⚡ Bangladesh Aluminium & Thai Industry ERP
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                        থাই অ্যালুমিনিয়াম ও গ্লাস ইনভেন্টরি & পিওএস সিস্টেম
                    </h1>
                    <p class="mt-2 text-sm sm:text-base text-slate-300 leading-relaxed">
                        সেকশনের রানিং ফুট/কেজি হিসাব, কাঁচের ইঞ্চি-স্কয়ারফিট ক্যালকুলেটর, কাটিং অফ-কাট ও স্ক্র্যাপ ম্যানেজমেন্ট, মিস্ত্রি কমিশন এবং চেক লেনদেনের সমন্বিত সফটওয়্যার।
                    </p>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <a href="#calculator" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold bg-cyan-500 hover:bg-cyan-400 text-slate-950 transition-all shadow-lg shadow-cyan-500/20">
                        📐 গ্লাস & সেকশন ক্যালকুলেটর
                    </a>
                    <a href="#modules" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition-all">
                        📦 ফিচার মডিউলসমূহ
                    </a>
                </div>
            </div>
        </div>

        <!-- Real-Time Metrics & Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Metric 1: Total Stock Products -->
            <div class="rounded-xl bg-slate-900/60 border border-slate-800 p-4 hover:border-slate-700 transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-400">মোট পণ্য (আইটেম)</span>
                    <span class="p-1.5 rounded-lg bg-cyan-500/10 text-cyan-400 text-sm">📦</span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-white">{{ $stats['total_products'] }}</span>
                    <span class="text-xs text-slate-400">প্রকার</span>
                </div>
                <p class="mt-1 text-xs text-slate-500">সেকশন: {{ $stats['profile_count'] }} | গ্লাস: {{ $stats['glass_count'] }}</p>
            </div>

            <!-- Metric 2: Total Stock Qty -->
            <div class="rounded-xl bg-slate-900/60 border border-slate-800 p-4 hover:border-slate-700 transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-400">মোট মজুদ পরিমাণ</span>
                    <span class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-sm">📊</span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-white">{{ number_format($stats['total_stock_qty'], 0) }}</span>
                    <span class="text-xs text-slate-400">বার/SFT/Pcs</span>
                </div>
                <p class="mt-1 text-xs text-emerald-400">৩টি ওয়্যারহাউসে সংরক্ষিত</p>
            </div>

            <!-- Metric 3: Customer Due Receivables -->
            <div class="rounded-xl bg-slate-900/60 border border-slate-800 p-4 hover:border-slate-700 transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-400">কাস্টমার মোট বাকি (পাওনা)</span>
                    <span class="p-1.5 rounded-lg bg-amber-500/10 text-amber-400 text-sm">💰</span>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-amber-400">৳{{ number_format($stats['customer_receivables'], 2) }}</span>
                </div>
                <p class="mt-1 text-xs text-slate-400">{{ $stats['total_customers'] }} জন কাস্টমার/মিস্ত্রি</p>
            </div>

            <!-- Metric 4: Supplier Payables -->
            <div class="rounded-xl bg-slate-900/60 border border-slate-800 p-4 hover:border-slate-700 transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-400">মিল ও সাপ্লায়ার দেনা</span>
                    <span class="p-1.5 rounded-lg bg-rose-500/10 text-rose-400 text-sm">🏭</span>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-rose-400">৳{{ number_format($stats['supplier_payables'], 2) }}</span>
                </div>
                <p class="mt-1 text-xs text-slate-400">{{ $stats['total_suppliers'] }}টি মিল/কোম্পানি</p>
            </div>
        </div>

        <!-- Interactive Glass & Profile Calculator Widget -->
        <section id="calculator" class="rounded-2xl border border-slate-800 bg-slate-900/80 p-6 glow-accent">
            <div class="border-b border-slate-800 pb-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>📐</span> থাই গ্লাস ও অ্যালুমিনিয়াম পরিমাপ ক্যালকুলেটর (Live Tool)
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">কাঁচের দৈর্ঘ্য-প্রস্থ (ইঞ্চি) থেকে অটোমেটিক স্কয়ার ফিট এবং সেকশনের ফুট/কেজি হিসাব করুন</p>
                </div>
                <div class="inline-flex rounded-lg bg-slate-800 p-1 border border-slate-700 text-xs">
                    <button id="btnGlassTab" onclick="switchCalc('glass')" class="px-3 py-1.5 rounded-md font-semibold bg-cyan-500 text-slate-950 transition">
                        কাঁচ / Glass SFT
                    </button>
                    <button id="btnProfileTab" onclick="switchCalc('profile')" class="px-3 py-1.5 rounded-md font-semibold text-slate-300 hover:text-white transition">
                        সেকশন / Profile RFT & Kg
                    </button>
                </div>
            </div>

            <!-- Glass Calculator Form -->
            <div id="glassCalc" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">উচ্চতা / Height (ইঞ্চি)</label>
                    <input type="number" id="glassHeight" value="54" step="0.25" oninput="calculateGlass()" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:border-cyan-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">প্রস্থ / Width (ইঞ্চি)</label>
                    <input type="number" id="glassWidth" value="36" step="0.25" oninput="calculateGlass()" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:border-cyan-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">গ্লাসের ধরণ ও রেট (প্রতি SFT)</label>
                    <select id="glassType" onchange="calculateGlass()" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:border-cyan-500 focus:outline-none">
                        <option value="70">5mm Clear Glass (৳70/SFT)</option>
                        <option value="85">5mm Tinted Dark Black (৳85/SFT)</option>
                        <option value="120">8mm Tempered Clear (৳120/SFT)</option>
                        <option value="150">10mm Tempered Glass (৳150/SFT)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">পরিমাণ (সংখ্যা / Pcs)</label>
                    <input type="number" id="glassQty" value="4" min="1" oninput="calculateGlass()" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:border-cyan-500 focus:outline-none">
                </div>

                <!-- Live Glass Result Display -->
                <div class="md:col-span-4 mt-2 p-4 rounded-xl bg-slate-950/80 border border-cyan-500/30 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <div>
                            <span class="text-xs text-slate-400 block">১ পিসের মাপ:</span>
                            <span id="singleSqft" class="text-lg font-bold text-cyan-400">13.50 SFT</span>
                        </div>
                        <div class="border-l border-slate-800 pl-6">
                            <span class="text-xs text-slate-400 block">মোট স্কয়ার ফিট (৪ পিস):</span>
                            <span id="totalSqft" class="text-xl font-extrabold text-white">54.00 SFT</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block">আনুমানিক সর্বমোট মূল্য:</span>
                        <span id="totalGlassPrice" class="text-2xl font-extrabold text-emerald-400">৳3,780.00</span>
                    </div>
                </div>
            </div>

            <!-- Profile Calculator Form (Initially hidden) -->
            <div id="profileCalc" class="hidden grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">সেকশন আইটেম নির্বাচন করুন</label>
                    <select id="profileItem" onchange="calculateProfile()" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:border-cyan-500 focus:outline-none">
                        <option value="4.25|2300">4" Sliding Bottom (1.2mm SS - 4.25kg/bar)</option>
                        <option value="3.80|2100">4" Sliding Top (1.2mm SS - 3.80kg/bar)</option>
                        <option value="3.20|1800">4" Sliding Interlock (1.2mm SS - 3.20kg/bar)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">বারের সংখ্যা (১ বার = ১৬ ফুট)</label>
                    <input type="number" id="profileBars" value="10" min="1" oninput="calculateProfile()" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:border-cyan-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">কাটিং মাপ (যদি প্রতি পিস নির্দিষ্ট ফুটের হয়)</label>
                    <input type="number" id="cutLengthFeet" value="10" step="0.5" oninput="calculateProfile()" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-sm focus:border-cyan-500 focus:outline-none">
                </div>
                <div>
                    <span class="text-xs text-slate-400 block mb-1">স্টক অফ-কাট রিইউজ</span>
                    <span id="offCutNote" class="text-xs font-medium text-amber-300 block py-2.5">প্রতি বারে ৬ ফুট অফ-কাট অবশিষ্ট থাকবে</span>
                </div>

                <!-- Live Profile Result Display -->
                <div class="md:col-span-4 mt-2 p-4 rounded-xl bg-slate-950/80 border border-cyan-500/30 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <div>
                            <span class="text-xs text-slate-400 block">মোট রানিং ফুট (RFT):</span>
                            <span id="totalRft" class="text-lg font-bold text-cyan-400">160 RFT</span>
                        </div>
                        <div class="border-l border-slate-800 pl-6">
                            <span class="text-xs text-slate-400 block">মোট সম্ভাব্য ওজন (Kg):</span>
                            <span id="totalWeight" class="text-xl font-extrabold text-white">42.50 Kg</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block">আনুমানিক বিক্রয় মূল্য:</span>
                        <span id="totalProfilePrice" class="text-2xl font-extrabold text-emerald-400">৳23,000.00</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- System Modules Overview (Matching User Specifications) -->
        <section id="modules" class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">মডিউল ও আর্কিটেকচার ওভারভিউ</h2>
                    <p class="text-xs text-slate-400">আপনার স্পেসিফিকেশন ও অ্যালুমিনিয়াম ব্যবসার প্রয়োজনীয়তায় নির্মিত ফিচারসমূহ</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Module 1: POS & Invoicing -->
                <div class="rounded-xl bg-slate-900 border border-slate-800 p-5 hover:border-slate-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="p-2 rounded-lg bg-blue-500/10 text-blue-400 text-lg">🛒</span>
                        <h3 class="font-bold text-white text-base">সেলস & পিওএস (Sales / POS)</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>বারকোড ও কুইক মাউস ক্লিক সেলস অপশন</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>গ্লাস SFT এবং সেকশন RFT/Kg পরিমাপ ইনপুট</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>ভ্যান/পিকআপ ভাড়া ও লেবার ফি চালানে যুক্ত করা</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>থাই মিস্ত্রি কমিশন অটোমেটিক ট্র্যাক ও লেজার</span></li>
                    </ul>
                </div>

                <!-- Module 2: Purchase & Carrying Cost -->
                <div class="rounded-xl bg-slate-900 border border-slate-800 p-5 hover:border-slate-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400 text-lg">🏭</span>
                        <h3 class="font-bold text-white text-base">পারচেজ & কেরিইং কস্ট</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>কাই, ঢাকা থাই বা পিএইচপি ফ্যাক্টরি চালান এন্ট্রি</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>ট্রাক/পিকআপ ভাড়া ও আনলোডিং কুলি খরচ যুক্ত করা</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>সাপ্লায়ার বাকি ও পেমেন্ট হিস্ট্রি খাতা</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>পারচেজ রিটার্ন (আংশিক বা সম্পূর্ণ চালান)</span></li>
                    </ul>
                </div>

                <!-- Module 3: Cheque Management -->
                <div class="rounded-xl bg-slate-900 border border-slate-800 p-5 hover:border-slate-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="p-2 rounded-lg bg-amber-500/10 text-amber-400 text-lg">🏦</span>
                        <h3 class="font-bold text-white text-base">চেক ম্যানেজমেন্ট (Cheques)</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>কাস্টমার থেকে প্রাপ্ত চেক ও সাপ্লায়ারকে দেওয়া চেক</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>In-Hand, Deposited, এবং Honored (ক্লিয়ার) ট্র্যাকিং</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>চেক বাউন্স / ডিসঅনার ও রিটার্ন ট্রিটমেন্ট</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>পোস্ট-ডেটেড চেক ও ক্লিয়ারেন্স অ্যালার্ট</span></li>
                    </ul>
                </div>

                <!-- Module 4: Job Quotation / Window Estimator -->
                <div class="rounded-xl bg-slate-900 border border-slate-800 p-5 hover:border-slate-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="p-2 rounded-lg bg-cyan-500/10 text-cyan-400 text-lg">📐</span>
                        <h3 class="font-bold text-white text-base">জানালা/দরজা জব কোটেশন</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>২-ট্র্যাক ও ৩-ট্র্যাক স্লাইডিং জানালা এস্টিমেটর</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>সেকশন, কাঁচ, লক ও হুইল এবং লেবার একত্র হিসাব</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>বিল্ডিং বা ফ্ল্যাট প্রজেক্ট অনুযায়ী অফিশিয়াল কোটেশন</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>এক ক্লিকে কোটেশন থেকে সেলস ইনভয়েসে রূপান্তর</span></li>
                    </ul>
                </div>

                <!-- Module 5: Off-cut & Scrap Stock -->
                <div class="rounded-xl bg-slate-900 border border-slate-800 p-5 hover:border-slate-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="p-2 rounded-lg bg-purple-500/10 text-purple-400 text-lg">✂️</span>
                        <h3 class="font-bold text-white text-base">কাটিং অফ-কাট & স্ক্র্যাপ স্টক</h3>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2">
                        <li class="flex items-start gap-1.5">✓ <span>কাটা অবশিষ্ট টুকরা (Off-cut) আলাদা স্টকে রাখা</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>পরবর্তী অর্ডারে অফ-কাট রিইউজ করে মুনাফা বাড়ানো</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>বাতিল ছোট টুকরো 'ভাঙারি/স্ক্র্যাপ' হিসেবে ওজন ও সেলস</span></li>
                        <li class="flex items-start gap-1.5">✓ <span>৩টি ওয়্যারহাউস (শোরুম, গোডাউন, ওয়ার্কশপ) ব্যালেন্স</span></li>
                    </ul>
                </div>

                <!-- Module 6: Accounts & Reports -->
                <div class="rounded-xl bg-slate-900 border border-slate-800 p-5 hover:border-slate-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="p-2 rounded-lg bg-rose-500/10 text-rose-400 text-lg">📊</span>
                        <h3 class="font-bold text-white text-base">একাউন্টস & ফাইন্যান্সিয়াল রিপোর্ট</h3>
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
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span>🏷️</span> ইনভেন্টরি প্রোডাক্ট মাস্টার (Sample Seeded)
                    </h3>
                    <span class="text-xs text-slate-400">মোট {{ $products->count() }}টি পণ্য</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-800/60 text-slate-400">
                            <tr>
                                <th class="p-2.5 rounded-l-lg">পণ্যের নাম</th>
                                <th class="p-2.5">ক্যাটাগরি</th>
                                <th class="p-2.5">ইউনিট</th>
                                <th class="p-2.5">খুচরা মূল্য</th>
                                <th class="p-2.5 rounded-r-lg">মিস্ত্রি মূল্য</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 text-slate-300">
                            @foreach ($products->take(6) as $p)
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="p-2.5 font-medium text-white">{{ $p->name }}</td>
                                    <td class="p-2.5 text-slate-400">{{ $p->category->name ?? '-' }}</td>
                                    <td class="p-2.5">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-slate-800 text-slate-300 font-mono text-[11px]">
                                            {{ $p->unit->code }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 font-bold text-emerald-400">৳{{ number_format($p->selling_price_retail, 2) }}</td>
                                    <td class="p-2.5 text-amber-300">৳{{ number_format($p->selling_price_contractor, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table 2: Parties & Warehouses -->
            <div class="space-y-4">
                <!-- Warehouses -->
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 mb-3">
                        <span>🏢</span> স্টোরেজ ও ওয়্যারহাউসসমূহ
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        @foreach ($warehouses as $wh)
                            <div class="p-3 rounded-xl bg-slate-950 border border-slate-800">
                                <span class="text-xs font-semibold text-white block">{{ $wh->name }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $wh->address }}</span>
                                @if($wh->is_primary)
                                    <span class="mt-1 inline-block text-[10px] font-bold text-cyan-400">★ প্রধান শাখা</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Customer & Mistri Ledger Snapshot -->
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 mb-3">
                        <span>👥</span> কাস্টমার ও থাই মিস্ত্রি লেজার স্ন্যাপশট
                    </h3>
                    <div class="space-y-2 text-xs">
                        @foreach ($customers as $c)
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-950 border border-slate-800">
                                <div>
                                    <span class="font-semibold text-white">{{ $c->name }}</span>
                                    <span class="text-slate-400 block text-[11px]">{{ $c->phone }} • {{ ucfirst($c->customer_type) }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-slate-400 block text-[10px]">বর্তমান দেনা/বাকি</span>
                                    <span class="font-bold {{ $c->current_balance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
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
            <p>© 2026 {{ $company->company_name }}. Powered by Laravel 13, MySQL 8.4, Docker Sail & Tailwind CSS v4.</p>
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
                btnGlass.classList.add('bg-cyan-500', 'text-slate-950');
                btnGlass.classList.remove('text-slate-300');
                btnProfile.classList.remove('bg-cyan-500', 'text-slate-950');
                btnProfile.classList.add('text-slate-300');
            } else {
                glassCalc.classList.add('hidden');
                profileCalc.classList.remove('hidden');
                btnProfile.classList.add('bg-cyan-500', 'text-slate-950');
                btnProfile.classList.remove('text-slate-300');
                btnGlass.classList.remove('bg-cyan-500', 'text-slate-950');
                btnGlass.classList.add('text-slate-300');
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

        // Initialize calculations
        document.addEventListener('DOMContentLoaded', function() {
            calculateGlass();
            calculateProfile();
        });
    </script>
</body>
</html>

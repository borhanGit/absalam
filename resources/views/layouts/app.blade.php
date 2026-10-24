<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'ThaiAluminiumPOS') }}</title>
    
    <!-- Google Fonts Inter / Jakarta -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-numbers { font-family: 'JetBrains Mono', monospace; }
        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(51, 65, 85, 0.6);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-slate-950 text-slate-100 antialiased selection:bg-cyan-500 selection:text-slate-950">

    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <aside class="w-64 border-r border-slate-800 bg-slate-900/95 flex flex-col shrink-0 z-30">
            <!-- Brand Logo -->
            <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-800">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-slate-950 font-black text-lg shadow-md shadow-cyan-500/20">
                    T
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white tracking-tight leading-tight">Thai Aluminium</h1>
                    <span class="text-[11px] text-cyan-400 font-medium">POS & ERP System</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6 text-xs">
                <!-- Group 1: Core Operations -->
                <div>
                    <div class="px-3 mb-2 font-bold tracking-wider text-slate-400 uppercase text-[10px]">অপারেশনস (Operations)</div>
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🏠</span> ড্যাশবোর্ড (Dashboard)
                        </a>
                        <a href="{{ route('pos.create') }}" class="flex items-center justify-between px-3 py-2 rounded-lg font-bold bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md shadow-cyan-500/20 hover:from-cyan-500 hover:to-blue-500 transition">
                            <span class="flex items-center gap-2"><span>⚡</span> পিওএস কাউন্টার (POS)</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-slate-950/30">F2</span>
                        </a>
                        <a href="{{ route('sales.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('sales.*') && !request()->routeIs('pos.create') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🛒</span> সেলস চালান (Sales List)
                        </a>
                        <a href="{{ route('quotations.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('quotations.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📐</span> জানালা/দরজা কোটেশন
                        </a>
                        <a href="{{ route('purchases.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('purchases.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🏭</span> পারচেজ ও ফ্যাক্টরি চালান
                        </a>
                    </div>
                </div>

                <!-- Group 2: Thai & Glass Inventory -->
                <div>
                    <div class="px-3 mb-2 font-bold tracking-wider text-slate-400 uppercase text-[10px]">ইনভেন্টরি (Inventory)</div>
                    <div class="space-y-1">
                        <a href="{{ route('products.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('products.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🏷️</span> পণ্য তালিকা (Products Master)
                        </a>
                        <a href="{{ route('stocks.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('stocks.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📦</span> ওয়্যারহাউস স্টক (Stock)
                        </a>
                        <a href="{{ route('offcuts.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('offcuts.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>✂️</span> কাটিং অফ-কাট ও স্ক্র্যাপ
                        </a>
                    </div>
                </div>

                <!-- Group 3: Parties & Ledgers -->
                <div>
                    <div class="px-3 mb-2 font-bold tracking-wider text-slate-400 uppercase text-[10px]">পার্টি ও লেজার (Parties & CRM)</div>
                    <div class="space-y-1">
                        <a href="{{ route('customers.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('customers.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>👥</span> কাস্টমার ও থাই মিস্ত্রি
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('suppliers.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🏢</span> মিল ও সাপ্লায়ার্স
                        </a>
                    </div>
                </div>

                <!-- Group 4: Finance, Cheque & Accounts -->
                <div>
                    <div class="px-3 mb-2 font-bold tracking-wider text-slate-400 uppercase text-[10px]">ফাইন্যান্স ও একাউন্টস (Finance)</div>
                    <div class="space-y-1">
                        <a href="{{ route('cheques.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('cheques.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🏦</span> চেক ম্যানেজমেন্ট (Cheques)
                        </a>
                        <a href="{{ route('receipts.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('receipts.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>💵</span> কালেকশন ও মানি রিসিট
                        </a>
                        <a href="{{ route('payments.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('payments.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📤</span> সাপ্লায়ার পেমেন্ট (Payments)
                        </a>
                        <a href="{{ route('expenses.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('expenses.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📉</span> দোকান খরচ (Expenses)
                        </a>
                        <a href="{{ route('accounts.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('accounts.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📊</span> ক্যাশ বুক ও আর্থিক রিপোর্ট
                        </a>
                    </div>
                </div>
            </nav>

            <!-- User Footer in Sidebar -->
            <div class="p-3 border-t border-slate-800 bg-slate-950/60 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 font-bold">
                        A
                    </div>
                    <div>
                        <span class="font-semibold text-white block">Shop Admin</span>
                        <span class="text-[10px] text-emerald-400">● অনলাইন</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Header -->
            <header class="h-16 border-b border-slate-800 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 shrink-0">
                <div class="flex items-center gap-4">
                    <h2 class="text-base font-bold text-white">@yield('page_title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-3 text-xs">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800/80 border border-slate-700 text-slate-300">
                        <span>🏢 শাখা:</span>
                        <span class="font-semibold text-white">প্রধান শোরুম (মিরপুর)</span>
                    </div>
                    <a href="{{ route('pos.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold transition">
                        <span>⚡</span> নতুন সেলস (POS)
                    </a>
                </div>
            </header>

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="m-6 mb-0 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span>✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="m-6 mb-0 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span>⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto p-6 space-y-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

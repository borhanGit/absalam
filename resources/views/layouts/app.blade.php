<!DOCTYPE html>
<html lang="bn" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title', 'Dashboard') | Dhaka Thai & Aluminium Glass House</title>
    
    <!-- Modern Google Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            aside, header, nav, .no-print, [class*="print:hidden"], button {
                display: none !important;
            }
            html, body {
                background: #ffffff !important;
                background-color: #ffffff !important;
                color: #000000 !important;
                height: auto !important;
                min-height: auto !important;
                overflow: visible !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            body > div, main {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                overflow: visible !important;
                margin: 0 !important;
                padding: 0 !important;
                background: transparent !important;
            }
            .max-w-4xl, .max-w-3xl, .max-w-2xl, .max-w-xl {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 8mm 12mm 12mm 12mm;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen flex flex-col selection:bg-cyan-500 selection:text-slate-950 print:bg-white print:text-black">
    <div class="flex-1 flex overflow-hidden print:overflow-visible print:block print:w-full">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-slate-900/80 backdrop-blur-md border-r border-slate-800 flex flex-col shrink-0 print:hidden">
            <!-- App Logo & Branding -->
            <div class="h-16 flex items-center px-4 border-b border-slate-800 gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-black text-slate-950 text-sm shadow-md shadow-cyan-500/20">
                    TH
                </div>
                <div class="leading-tight">
                    <span class="font-bold text-sm tracking-tight text-white block">ঢাকা থাই ও গ্লাস</span>
                    <span class="text-[10px] text-cyan-400 font-medium">POS & Inventory System</span>
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

                        @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager', 'cashier']))
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
                        @endif

                        @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager', 'storekeeper']))
                        <a href="{{ route('purchases.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('purchases.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🏭</span> পারচেজ ও ফ্যাক্টরি চালান
                        </a>
                        @endif
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
                        @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager', 'storekeeper']))
                        <a href="{{ route('suppliers.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('suppliers.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🏢</span> মিল ও সাপ্লায়ার্স
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Group 4: Finance, Cheque & Accounts -->
                @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager', 'cashier']))
                <div>
                    <div class="px-3 mb-2 font-bold tracking-wider text-slate-400 uppercase text-[10px]">ফাইন্যান্স ও একাউন্টস (Finance)</div>
                    <div class="space-y-1">
                        <a href="{{ route('cheques.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('cheques.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🏦</span> চেক ম্যানেজমেন্ট (Cheques)
                        </a>
                        <a href="{{ route('receipts.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('receipts.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>💵</span> কালেকশন ও মানি রিসিট
                        </a>
                        @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager']))
                        <a href="{{ route('payments.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('payments.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📤</span> সাপ্লায়ার পেমেন্ট (Payments)
                        </a>
                        <a href="{{ route('expenses.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('expenses.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📉</span> দোকান খরচ (Expenses)
                        </a>
                        @endif
                        @if(!auth()->check() || auth()->user()->isAdmin())
                        <a href="{{ route('accounts.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('accounts.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📊</span> ক্যাশ বুক ও আর্থিক রিপোর্ট
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Group 5: Settings & Administration -->
                @if(!auth()->check() || auth()->user()->isAdmin())
                <div>
                    <div class="px-3 mb-2 font-bold tracking-wider text-purple-400 uppercase text-[10px]">অ্যাডমিনিস্ট্রেশন (Administration)</div>
                    <div class="space-y-1">
                        <a href="{{ route('users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('users.*') ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🛡️</span> ইউজার ও পারমিশন (Users)
                        </a>
                    </div>
                </div>
                @endif
            </nav>

            <!-- User Profile & Session Footer in Sidebar -->
            <div class="p-3 border-t border-slate-800 bg-slate-950/80 flex items-center justify-between text-xs">
                @auth
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-cyan-600 to-blue-600 flex items-center justify-center text-white font-bold shrink-0 shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 truncate">
                        <a href="{{ route('profile') }}" class="font-semibold text-white block hover:text-cyan-400 truncate">{{ auth()->user()->name }}</a>
                        <span class="text-[10px] text-cyan-400 block truncate">{{ auth()->user()->roleNameBengali() }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="লগআউট" class="p-1.5 rounded-lg bg-slate-800 hover:bg-rose-500/20 hover:text-rose-400 text-slate-400 border border-slate-700 transition">
                        🚪
                    </button>
                </form>
                @else
                <div class="flex items-center justify-between w-full">
                    <span class="text-slate-400">লগইন করুন</span>
                    <a href="{{ route('login') }}" class="px-2.5 py-1 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-[11px]">লগইন</a>
                </div>
                @endauth
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden print:overflow-visible print:block print:w-full print:m-0 print:p-0">
            <!-- Top Header -->
            <header class="h-16 border-b border-slate-800 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 shrink-0 print:hidden">
                <div class="flex items-center gap-4">
                    <h2 class="text-base font-bold text-white">@yield('page_title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-3 text-xs">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800/80 border border-slate-700 text-slate-300">
                        <span>🏢 শাখা:</span>
                        <span class="font-semibold text-white">
                            {{ auth()->user()?->warehouse?->name ?? 'প্রধান শোরুম (মিরপুর)' }}
                        </span>
                    </div>

                    @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager', 'cashier']))
                    <a href="{{ route('pos.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold transition shadow-sm">
                        <span>⚡</span> পিওএস কাউন্টার
                    </a>
                    @endif

                    @auth
                    <a href="{{ route('profile') }}" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 transition">
                        👤 প্রোফাইল
                    </a>
                    @endauth
                </div>
            </header>

            <!-- Flash Notifications -->
            <div class="print:hidden">
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
            </div>

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto p-6 space-y-6 print:p-0 print:m-0 print:overflow-visible print:block print:w-full print:bg-white">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title', 'Dashboard') | {{ $company->company_name ?? 'Dhaka Thai & Aluminium Glass House' }}</title>
    
    <!-- Instant Theme Detection Script (Prevents FOUC / theme flicker) -->
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
    
    <!-- Modern Typography: Inter + Hind Siliguri + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
            height: 100dvh;
            overflow: hidden;
        }

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
<body class="bg-[#f6f9fc] dark:bg-[#070d18] text-slate-800 dark:text-[#e2e8f0] font-sans antialiased h-full h-[100dvh] overflow-hidden flex flex-col print:h-auto print:overflow-visible print:bg-white print:text-black transition-colors duration-200">
    <div class="h-full w-full flex overflow-hidden relative print:h-auto print:overflow-visible print:block">

        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" 
             onclick="closeMobileSidebar()" 
             class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-40 transition-opacity duration-300 opacity-0 pointer-events-none lg:hidden"
             aria-hidden="true">
        </div>

        <!-- Sidebar Navigation (Desktop Static / Mobile Off-canvas Drawer) -->
        <aside id="mainSidebar" 
               class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] h-full h-[100dvh] bg-white dark:bg-[#0a1220] backdrop-blur-xl border-r border-slate-200 dark:border-[#e3e8ee]/10 flex flex-col shrink-0 print:hidden transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:w-64 lg:h-full lg:z-20 shadow-2xl lg:shadow-none">
            
            <!-- App Logo & Branding + Mobile Close Button -->
            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-200 dark:border-[#e3e8ee]/10 gap-3 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-[#533afd] flex items-center justify-center font-bold text-white text-xs shadow-md shadow-[#533afd]/25 shrink-0">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3h18v18H3zM3 9h18M9 21V9"/>
                        </svg>
                    </div>
                    <div class="leading-tight min-w-0">
                        <span class="font-semibold text-sm tracking-tight text-slate-900 dark:text-white block truncate">{{ __('app.app_name') }}</span>
                        <span class="text-[10px] text-slate-500 dark:text-[#64748d] font-medium tracking-wide block truncate">{{ __('app.app_subtitle') }}</span>
                    </div>
                </div>

                <!-- Close Button on Mobile Drawer -->
                <button type="button" 
                        onclick="closeMobileSidebar()" 
                        class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition focus:outline-none min-w-[36px] min-h-[36px] flex items-center justify-center" 
                        aria-label="Close Sidebar">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 min-h-0 overflow-y-auto px-3 py-4 space-y-6 text-xs scrollbar-thin touch-scroll">
                <!-- Group 1: Core Operations -->
                <div>
                    <div class="px-3 mb-2 font-semibold tracking-wider text-slate-500 dark:text-[#64748d] uppercase text-[10px]">{{ __('app.nav_operations') }}</div>
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                            </svg>
                            <span>{{ __('app.dashboard') }}</span>
                        </a>

                        @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager', 'cashier']))
                        <a href="{{ route('pos.create') }}" class="flex items-center justify-between px-3 py-2 rounded-lg font-semibold bg-[#533afd] hover:bg-[#4434d4] text-white shadow-sm shadow-[#533afd]/30 transition group">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-white group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                                </svg>
                                <span>{{ __('app.pos_counter') }}</span>
                            </span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-black/25 font-mono">F2</span>
                        </a>
                        <a href="{{ route('sales.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('sales.*') && !request()->routeIs('pos.create') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                            </svg>
                            <span>{{ __('app.sales_list') }}</span>
                        </a>
                        <a href="{{ route('quotations.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('quotations.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                            </svg>
                            <span>{{ __('app.quotations') }}</span>
                        </a>
                        @endif

                        @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager', 'storekeeper']))
                        <a href="{{ route('purchases.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('purchases.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                            </svg>
                            <span>{{ __('app.purchases') }}</span>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Group 2: Thai & Glass Inventory -->
                <div>
                    <div class="px-3 mb-2 font-semibold tracking-wider text-slate-500 dark:text-[#64748d] uppercase text-[10px]">{{ __('app.nav_inventory') }}</div>
                    <div class="space-y-1">
                        <a href="{{ route('products.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('products.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><circle cx="7" cy="7" r=".5" fill="currentColor"/>
                            </svg>
                            <span>{{ __('app.products') }}</span>
                        </a>
                        <a href="{{ route('stocks.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('stocks.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/>
                            </svg>
                            <span>{{ __('app.stocks') }}</span>
                        </a>
                        <a href="{{ route('offcuts.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('offcuts.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" x2="8.12" y1="4" y2="15.88"/><line x1="14.47" x2="20" y1="14.48" y2="20"/><line x1="8.12" x2="12" y1="8.12" y2="12"/>
                            </svg>
                            <span>{{ __('app.offcuts') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Group 3: Parties & Ledgers -->
                <div>
                    <div class="px-3 mb-2 font-semibold tracking-wider text-slate-500 dark:text-[#64748d] uppercase text-[10px]">{{ __('app.nav_parties') }}</div>
                    <div class="space-y-1">
                        <a href="{{ route('customers.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('customers.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            <span>{{ __('app.customers') }}</span>
                        </a>
                        @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager', 'storekeeper']))
                        <a href="{{ route('suppliers.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('suppliers.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/>
                            </svg>
                            <span>{{ __('app.suppliers') }}</span>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Group 4: Finance, Cheque & Accounts -->
                @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager', 'cashier']))
                <div>
                    <div class="px-3 mb-2 font-semibold tracking-wider text-slate-500 dark:text-[#64748d] uppercase text-[10px]">{{ __('app.nav_finance') }}</div>
                    <div class="space-y-1">
                        <a href="{{ route('cheques.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('cheques.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                            </svg>
                            <span>{{ __('app.cheques') }}</span>
                        </a>
                        <a href="{{ route('receipts.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('receipts.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 17.5v-11"/>
                            </svg>
                            <span>{{ __('app.receipts') }}</span>
                        </a>
                        @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager']))
                        <a href="{{ route('payments.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('payments.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m5 12 7-7 7 7"/><path d="M12 19V5"/>
                            </svg>
                            <span>{{ __('app.payments') }}</span>
                        </a>
                        <a href="{{ route('expenses.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('expenses.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"/><polyline points="16 17 22 17 22 11"/>
                            </svg>
                            <span>{{ __('app.expenses') }}</span>
                        </a>
                        @endif
                        @if(!auth()->check() || auth()->user()->isAdmin())
                        <a href="{{ route('accounts.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('accounts.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/>
                            </svg>
                            <span>{{ __('app.accounts') }}</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Group 5: Settings & Administration -->
                @if(!auth()->check() || auth()->user()->isAdmin())
                <div>
                    <div class="px-3 mb-2 font-semibold tracking-wider text-slate-500 dark:text-[#64748d] uppercase text-[10px]">{{ __('app.nav_administration') }}</div>
                    <div class="space-y-1">
                        <a href="{{ route('users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('users.*') ? 'bg-[#533afd]/15 text-[#533afd] dark:text-[#ffffff] border border-[#533afd]/30 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/>
                            </svg>
                            <span>{{ __('app.users') }}</span>
                        </a>
                    </div>
                </div>
                @endif
            </nav>

            <!-- User Profile & Session Footer in Sidebar -->
            <div class="p-3 border-t border-slate-200 dark:border-[#e3e8ee]/10 bg-slate-50 dark:bg-[#070d18]/80 flex items-center justify-between text-xs shrink-0">
                @auth
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-[#1c1e54] border border-[#533afd]/40 flex items-center justify-center text-white font-semibold shrink-0 shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 truncate">
                        <a href="{{ route('profile') }}" class="font-medium text-slate-900 dark:text-white block hover:text-[#533afd] truncate">{{ auth()->user()->name }}</a>
                        <span class="text-[10px] text-slate-500 dark:text-[#64748d] block truncate">{{ auth()->user()->roleName() }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="{{ __('app.logout') }}" class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-200 dark:bg-white/5 hover:bg-rose-500/20 hover:text-rose-500 dark:hover:text-rose-400 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-white/10 transition" aria-label="{{ __('app.logout') }}">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                    </button>
                </form>
                @else
                <div class="flex items-center justify-between w-full">
                    <span class="text-slate-500 dark:text-slate-400">{{ __('app.login') }}</span>
                    <a href="{{ route('login') }}" class="btn-primary text-xs py-1.5 px-3">{{ __('app.login') }}</a>
                </div>
                @endauth
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden print:h-auto print:overflow-visible print:block print:w-full print:m-0 print:p-0">
            <!-- Top Header with subtle DESIGN.md atmospheric gradient mesh -->
            <header class="h-16 border-b border-slate-200 dark:border-[#e3e8ee]/10 bg-white/90 dark:bg-[#0a1220]/80 mesh-backdrop backdrop-blur-xl flex items-center justify-between px-3 sm:px-6 shrink-0 print:hidden z-10 transition-colors">
                <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                    <!-- Mobile Hamburger Menu Button -->
                    <button type="button" 
                            id="mobileMenuBtn" 
                            onclick="toggleMobileSidebar()" 
                            class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition focus:outline-none min-w-[40px] min-h-[40px] flex items-center justify-center shrink-0" 
                            aria-label="Open Navigation Menu">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <h1 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-white tracking-tight truncate">
                        @yield('page_title', 'Dashboard')
                    </h1>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 text-xs shrink-0">
                    <!-- Branch info: hidden on small screens -->
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-[#0d253d] border border-slate-200 dark:border-[#e3e8ee]/10 text-slate-600 dark:text-slate-300">
                        <svg class="w-3.5 h-3.5 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/>
                        </svg>
                        <span>{{ __('app.branch') }}</span>
                        <span class="font-medium text-slate-900 dark:text-white">
                            {{ auth()->user()?->warehouse?->name ?? __('app.default_branch') }}
                        </span>
                    </div>

                    @if(!auth()->check() || auth()->user()->hasRole(['admin', 'manager', 'cashier']))
                    <a href="{{ route('pos.create') }}" class="btn-primary !px-2.5 sm:!px-4 !py-1.5 sm:!py-2 text-xs">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                        </svg>
                        <span class="hidden sm:inline">{{ __('app.pos_counter') }}</span>
                        <span class="sm:hidden font-semibold">POS</span>
                    </a>
                    @endif

                    @auth
                    <a href="{{ route('profile') }}" class="btn-secondary !p-2 sm:!px-3 sm:!py-2" title="{{ __('app.profile') }}">
                        <svg class="w-3.5 h-3.5 text-slate-600 dark:text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span class="hidden md:inline">{{ __('app.profile') }}</span>
                    </a>
                    @endauth

                    <!-- Language Switcher (বাং / EN) -->
                    <div class="flex items-center rounded-full bg-slate-100 dark:bg-[#0d253d] border border-slate-200 dark:border-[#e3e8ee]/10 p-0.5 text-[11px] font-semibold" title="{{ __('app.switch_language') }}">
                        <a href="{{ route('locale.switch', 'bn') }}" 
                           class="px-2 sm:px-2.5 py-1 rounded-full transition {{ app()->getLocale() === 'bn' ? 'bg-[#533afd] text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}"
                           title="বাংলা">
                            বাং
                        </a>
                        <a href="{{ route('locale.switch', 'en') }}" 
                           class="px-2 sm:px-2.5 py-1 rounded-full transition {{ app()->getLocale() === 'en' ? 'bg-[#533afd] text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}"
                           title="English">
                            EN
                        </a>
                    </div>

                    <!-- Dark / White (Light) Mode Toggle Button -->
                    <button type="button" onclick="toggleTheme()" class="btn-secondary !p-2 !min-h-[36px] !rounded-full relative" title="{{ __('app.toggle_theme') }}" aria-label="Toggle Theme">
                        <!-- Sun Icon (Active in Dark mode -> click to switch to White mode) -->
                        <svg id="themeIconSun" class="w-4 h-4 text-amber-400 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                        </svg>
                        <!-- Moon Icon (Active in White mode -> click to switch to Dark mode) -->
                        <svg id="themeIconMoon" class="w-4 h-4 text-[#533afd] hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Flash Notifications -->
            <div class="print:hidden">
                @if(session('success'))
                    <div class="m-4 sm:m-6 mb-0 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-600 dark:text-emerald-300 text-sm flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="m-4 sm:m-6 mb-0 p-4 rounded-xl bg-rose-500/10 border border-rose-500/25 text-rose-600 dark:text-rose-300 text-sm flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-rose-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Page Body: Scrollable on Desktop & Mobile with Native Momentum -->
            <main id="mainContent" class="flex-1 min-h-0 overflow-y-auto p-3.5 sm:p-6 space-y-4 sm:space-y-6 print:p-0 print:m-0 print:overflow-visible print:block print:w-full print:bg-white touch-scroll">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Rule 3: Async Form Submission explicit Spinner handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled && !form.hasAttribute('data-no-spinner')) {
                        const originalHtml = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
                        submitBtn.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ __('Loading...') }}</span>
                        `;
                        // Fallback release after 8s if page hasn't reloaded
                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-80', 'cursor-not-allowed');
                            submitBtn.innerHTML = originalHtml;
                        }, 8000);
                    }
                });
            });
        });
    </script>

    <!-- Mobile Sidebar Drawer Controller -->
    <script>
        function openMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100', 'pointer-events-auto');
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100', 'pointer-events-auto');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            if (sidebar) {
                if (sidebar.classList.contains('-translate-x-full')) {
                    openMobileSidebar();
                } else {
                    closeMobileSidebar();
                }
            }
        }

        // Close on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeMobileSidebar();
            }
        });

        // Close mobile drawer when clicking navigation links
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('#mainSidebar nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 1024) {
                        closeMobileSidebar();
                    }
                });
            });
        });
    </script>

    <!-- Dark / White Mode Switcher Logic -->
    <script>
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

        // Initialize icons on load
        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    </script>

    @stack('scripts')
</body>
</html>
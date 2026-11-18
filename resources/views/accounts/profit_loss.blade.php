@extends('layouts.app')

@section('title', __('Profit & Loss Report'))
@section('page_title', __('Profit & Loss Report'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Date Filters -->
    <div class="card-shell p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-lg font-bold text-white tracking-tight">{{ __('Profit & Loss Report') }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">Sales revenue, cost of goods purchased, operational costs and net margins</p>
        </div>
        <form method="GET" action="{{ route('accounts.profit-loss') }}" class="flex flex-wrap items-center gap-2 text-xs">
            <input type="date" name="from_date" value="{{ $fromDate->format('Y-m-d') }}" class="bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
            <span class="text-slate-500">to</span>
            <input type="date" name="to_date" value="{{ $toDate->format('Y-m-d') }}" class="bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
            <button type="submit" class="btn-primary py-2 text-xs">{{ __('Filter') }}</button>
        </form>
    </div>

    <!-- P&L Sheet Card -->
    <div class="card-shell p-6 sm:p-8 space-y-6">
        <div class="text-center border-b border-slate-800 pb-5">
            <h2 class="text-base font-bold text-white uppercase tracking-wider">{{ __('Profit & Loss Report') }}</h2>
            <p class="text-xs text-indigo-400 font-mono tnum mt-1">{{ $fromDate->format('d M, Y') }} — {{ $toDate->format('d M, Y') }}</p>
        </div>

        <!-- Section 1: Operating Revenue -->
        <div class="space-y-3">
            <div class="flex justify-between items-center text-xs font-bold text-indigo-400 uppercase tracking-wider border-b border-slate-800 pb-2">
                <span>1. Operating Revenue</span>
                <span>{{ __('Amount') }} (৳)</span>
            </div>
            <div class="flex justify-between items-center text-xs text-slate-300 px-2">
                <span>{{ __('Total Sales') }} ({{ $salesCount }} invoices)</span>
                <span class="font-mono tnum font-semibold text-white">৳ {{ number_format($totalRevenue, 2) }}</span>
            </div>
            <div class="flex justify-between items-center text-xs font-bold text-emerald-400 bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20">
                <span>{{ __('Total Sales') }} [A]</span>
                <span class="font-mono tnum text-sm">৳ {{ number_format($totalRevenue, 2) }}</span>
            </div>
        </div>

        <!-- Section 2: Cost of Goods / Purchases -->
        <div class="space-y-3">
            <div class="flex justify-between items-center text-xs font-bold text-amber-400 uppercase tracking-wider border-b border-slate-800 pb-2">
                <span>2. Cost of Purchases / Material Cost</span>
                <span>{{ __('Amount') }} (৳)</span>
            </div>
            <div class="flex justify-between items-center text-xs text-slate-300 px-2">
                <span>{{ __('Purchases & Factory Invoices') }}</span>
                <span class="font-mono tnum text-slate-300">৳ {{ number_format($totalPurchases, 2) }}</span>
            </div>
            <div class="flex justify-between items-center text-xs font-bold text-amber-400 bg-amber-500/10 p-3 rounded-xl border border-amber-500/20">
                <span>{{ __('Total Purchases') }} [B]</span>
                <span class="font-mono tnum text-sm">৳ {{ number_format($totalPurchases, 2) }}</span>
            </div>
        </div>

        <!-- Gross Profit Bar -->
        <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 flex justify-between items-center">
            <div>
                <span class="text-xs font-bold text-white block">Gross Profit [A - B]</span>
                <span class="text-[11px] text-slate-400">Sales revenue minus raw materials cost</span>
            </div>
            <div class="text-xl font-bold font-mono tnum {{ $grossProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                ৳ {{ number_format($grossProfit, 2) }}
            </div>
        </div>

        <!-- Section 3: Operating Expenses -->
        <div class="space-y-3">
            <div class="flex justify-between items-center text-xs font-bold text-rose-400 uppercase tracking-wider border-b border-slate-800 pb-2">
                <span>3. Operating Expenses</span>
                <span>{{ __('Amount') }} (৳)</span>
            </div>
            @forelse($expenses as $exp)
            <div class="flex justify-between items-center text-xs text-slate-300 px-2">
                <span>{{ $exp->category_name }}</span>
                <span class="font-mono tnum text-rose-300">৳ {{ number_format($exp->total_cat_amount, 2) }}</span>
            </div>
            @empty
            <div class="text-xs text-slate-500 italic px-2">No operating expenses recorded for this period.</div>
            @endforelse
            <div class="flex justify-between items-center text-xs font-bold text-rose-400 bg-rose-500/10 p-3 rounded-xl border border-rose-500/20">
                <span>{{ __('Total Expenses') }} [C]</span>
                <span class="font-mono tnum text-sm">৳ {{ number_format($totalExpenses, 2) }}</span>
            </div>
        </div>

        <!-- Final Net Profit Banner -->
        <div class="p-6 rounded-2xl {{ $netProfit >= 0 ? 'bg-emerald-950/40 border border-emerald-500/30' : 'bg-rose-950/40 border border-rose-500/30' }} flex justify-between items-center">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }} block">
                    {{ $netProfit >= 0 ? 'NET PROFIT' : 'NET LOSS' }}
                </span>
                <span class="text-xs text-slate-400">[Gross Profit - Operating Expenses]</span>
            </div>
            <div class="text-3xl font-bold font-mono tnum {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                ৳ {{ number_format($netProfit, 2) }}
            </div>
        </div>
    </div>
</div>
@endsection
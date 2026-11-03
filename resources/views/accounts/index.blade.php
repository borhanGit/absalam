@extends('layouts.app')

@section('title', 'হিসাব ও চার্ট অব একাউন্টস')
@section('page_title', 'হিসাব ও চার্ট অব একাউন্টস (Accounts & Financials)')

@section('content')
<div class="space-y-6">

    <!-- Header with Quick Reports Links -->
    <div class="card-shell p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-lg font-bold text-white tracking-tight">চার্ট অব একাউন্টস ও আর্থিক ব্যবস্থাপনা</h1>
            <p class="text-xs text-slate-400 mt-0.5">চার্ট অব একাউন্টস, সম্পদ, দায়, মোট দেনা-পাওনা ও ব্যবসায়িক মূলধন সারসংক্ষেপ</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('accounts.cashbook') }}" class="btn-secondary inline-flex items-center gap-2 text-xs">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                দৈনিক ক্যাশ বুক
            </a>
            <a href="{{ route('accounts.profit-loss') }}" class="btn-primary inline-flex items-center gap-2 text-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                লাভ-লোকসান হিসাব (P&L)
            </a>
        </div>
    </div>

    <!-- Financial Health Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-indigo-400">কাস্টমার বকেয়া (Receivable)</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['customer_receivables'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">গ্রাহক ও মিস্ত্রিদের কাছে পাওনা</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-amber-400">সাপ্লায়ার দেনা (Payable)</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['supplier_payables'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">মিল ও ফ্যাক্টরিদের পরিশোধযোগ্য</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-emerald-400">দোকান স্টক মূল্য (Stock Asset)</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['stock_valuation'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">মজুদ পণ্যের ক্রয়মূল্য</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-rose-400">মোট দোকান ব্যয় (Total Expenses)</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['total_expenses'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">সকল অপারেটিং খরচ</div>
        </div>
    </div>

    <!-- Chart of Accounts Structure -->
    <div class="card-shell overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="text-sm font-bold text-white">চার্ট অব একাউন্টস তালিকা (Chart of Accounts)</h2>
            </div>
            <span class="text-xs text-slate-500">স্ট্যান্ডার্ড হিসাব কোড অনুসারে সাজানো</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">হিসাব কোড (Code)</th>
                        <th class="p-3.5">হিসাবের নাম (Account Name)</th>
                        <th class="p-3.5">হিসাবের ধরন (Type)</th>
                        <th class="p-3.5 text-center">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($accounts as $acc)
                    <tr class="hover:bg-slate-800/30 transition {{ $acc->parent_id ? 'bg-slate-950/20' : 'bg-slate-900/40 font-semibold' }}">
                        <td class="p-3.5 font-mono text-indigo-400 font-semibold">
                            {{ $acc->code }}
                        </td>
                        <td class="p-3.5 font-sans">
                            <span class="{{ $acc->parent_id ? 'pl-4 text-slate-300' : 'text-white font-semibold' }}">
                                {{ $acc->parent_id ? '↳ ' : '' }}{{ $acc->name }}
                            </span>
                        </td>
                        <td class="p-3.5 font-sans uppercase text-[11px]">
                            @if($acc->account_type === 'asset')
                                <span class="px-2.5 py-0.5 rounded-full text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 font-semibold">সম্পদ (Asset)</span>
                            @elseif($acc->account_type === 'liability')
                                <span class="px-2.5 py-0.5 rounded-full text-amber-400 bg-amber-500/10 border border-amber-500/20 font-semibold">দায় (Liability)</span>
                            @elseif($acc->account_type === 'equity')
                                <span class="px-2.5 py-0.5 rounded-full text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 font-semibold">মালিকানা (Equity)</span>
                            @elseif($acc->account_type === 'revenue')
                                <span class="px-2.5 py-0.5 rounded-full text-teal-400 bg-teal-500/10 border border-teal-500/20 font-semibold">আয় (Revenue)</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-rose-400 bg-rose-500/10 border border-rose-500/20 font-semibold">ব্যয় (Expense)</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-center font-sans">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">সক্রিয়</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-500 font-sans">কোনো হিসাবের তথ্য পাওয়া যায়নি।</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

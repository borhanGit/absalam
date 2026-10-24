@extends('layouts.app')

@section('page_title', 'হিসাব ও চার্ট অব একাউন্টস (Accounts & Financials)')

@section('content')
<div class="space-y-6">
    <!-- Header with Quick Reports Links -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>📊</span> একাউন্টস ও আর্থিক ব্যবস্থাপনা
            </h1>
            <p class="text-xs text-slate-400 mt-1">চার্ট অব একাউন্টস, সম্পদ, দায়, মোট দেনা-পাওনা ও ব্যবসায়িক মূলধন সারসংক্ষেপ</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('accounts.cashbook') }}" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-500/20 transition flex items-center gap-2">
                <span>📖</span> দৈনিক ক্যাশ বুক (Cash Book)
            </a>
            <a href="{{ route('accounts.profit-loss') }}" class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-cyan-500/20 transition flex items-center gap-2">
                <span>📈</span> লাভ-লোকসান হিসাব (P&L)
            </a>
        </div>
    </div>

    <!-- Financial Health Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-slate-900/80 border border-cyan-500/20">
            <div class="text-xs font-medium text-cyan-400">কাস্টমার বকেয়া (Receivable)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['customer_receivables'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">গ্রাহক ও মিস্ত্রিদের কাছে পাওনা</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-amber-500/20">
            <div class="text-xs font-medium text-amber-400">সাপ্লায়ার দেনা (Payable)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['supplier_payables'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">মিল ও ফ্যাক্টরিদের পরিশোধযোগ্য</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-emerald-500/20">
            <div class="text-xs font-medium text-emerald-400">দোকান স্টক মূল্য (Stock Asset)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['stock_valuation'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">মজুদ পণ্যের ক্রয়মূল্য</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-rose-500/20">
            <div class="text-xs font-medium text-rose-400">মোট দোকান ব্যয় (Total Expenses)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['total_expenses'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">সকল অপারেটিং খরচ</div>
        </div>
    </div>

    <!-- Chart of Accounts Structure -->
    <div class="bg-slate-900/60 rounded-2xl border border-slate-800 p-5 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-white flex items-center gap-2">
                <span>📑</span> চার্ট অব একাউন্টস তালিকা (Chart of Accounts)
            </h2>
            <span class="text-xs text-slate-500">স্ট্যান্ডার্ড হিসাব কোড অনুসারে সাজানো</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 font-semibold uppercase text-[11px] border-b border-slate-800">
                    <tr>
                        <th class="p-3">হিসাব কোড (Code)</th>
                        <th class="p-3">হিসাবের নাম (Account Name)</th>
                        <th class="p-3">হিসাবের ধরন (Type)</th>
                        <th class="p-3 text-center">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($accounts as $acc)
                    <tr class="hover:bg-slate-800/40 transition {{ $acc->parent_id ? 'bg-slate-950/20' : 'bg-slate-900/40 font-bold' }}">
                        <td class="p-3 text-cyan-400">
                            {{ $acc->code }}
                        </td>
                        <td class="p-3 font-sans">
                            <span class="{{ $acc->parent_id ? 'pl-4 text-slate-300' : 'text-white font-bold' }}">
                                {{ $acc->parent_id ? '↳ ' : '' }}{{ $acc->name }}
                            </span>
                        </td>
                        <td class="p-3 font-sans uppercase text-[11px]">
                            @if($acc->account_type === 'asset')
                                <span class="px-2 py-0.5 rounded text-emerald-400 bg-emerald-500/10 border border-emerald-500/20">সম্পদ (Asset)</span>
                            @elseif($acc->account_type === 'liability')
                                <span class="px-2 py-0.5 rounded text-amber-400 bg-amber-500/10 border border-amber-500/20">দায় (Liability)</span>
                            @elseif($acc->account_type === 'equity')
                                <span class="px-2 py-0.5 rounded text-blue-400 bg-blue-500/10 border border-blue-500/20">মালিকানা (Equity)</span>
                            @elseif($acc->account_type === 'revenue')
                                <span class="px-2 py-0.5 rounded text-teal-400 bg-teal-500/10 border border-teal-500/20">আয় (Revenue)</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-rose-400 bg-rose-500/10 border border-rose-500/20">ব্যয় (Expense)</span>
                            @endif
                        </td>
                        <td class="p-3 text-center font-sans">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400">সক্রিয়</span>
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

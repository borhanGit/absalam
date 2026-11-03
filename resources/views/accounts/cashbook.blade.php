@extends('layouts.app')

@section('title', 'দৈনিক ক্যাশ বুক')
@section('page_title', 'দৈনিক ক্যাশ বুক (Daily Cash Book)')

@section('content')
<div class="space-y-6">

    <!-- Header with Date Picker & Actions -->
    <div class="card-shell p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 print:hidden no-print">
        <div>
            <h1 class="text-lg font-bold text-white tracking-tight">দৈনিক ক্যাশ বুক ও নগদ জমার রেজিস্টার</h1>
            <p class="text-xs text-slate-400 mt-0.5">কাউন্টারের দৈনিক নগদ জমা (Cash In) ও নগদ খরচ/পরিশোধ (Cash Out) রেজিস্টার</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('accounts.cashbook') }}" class="flex items-center gap-2 text-xs">
                <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
                <button type="submit" class="btn-primary py-2 text-xs">তারিখ দেখুন</button>
            </form>
            <button onclick="window.print()" class="btn-secondary py-2 px-3 text-xs inline-flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                প্রিন্ট
            </button>
        </div>
    </div>

    <!-- Daily Cash Summary Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-emerald-400">আজকের মোট নগদ জমা (Cash In)</div>
            <div class="text-2xl font-bold text-emerald-400 font-mono tnum mt-1">৳ {{ number_format($totalInflow, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">নগদ সেলস ও বকেয়া কালেকশন</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-rose-400">আজকের মোট নগদ খরচ (Cash Out)</div>
            <div class="text-2xl font-bold text-rose-400 font-mono tnum mt-1">৳ {{ number_format($totalOutflow, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">দোকান খরচ, ক্রয় ও মিল পেমেন্ট</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold {{ $netCashToday >= 0 ? 'text-indigo-400' : 'text-amber-400' }}">আজকের দিনের নেট নগদ স্থিতি (Net Cash)</div>
            <div class="text-2xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($netCashToday, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">জমা ও খরচের পার্থক্য</div>
        </div>
    </div>

    <!-- Chronological Cash Ledger Table -->
    <div class="card-shell overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-800 flex justify-between items-center text-xs">
            <span class="font-semibold text-white">তারিখের হিসাব বিবরণী: <span class="text-indigo-400 font-mono">{{ $date->format('d F, Y (l)') }}</span></span>
            <span class="text-slate-400 font-mono">{{ $transactions->count() }} টি লেনদেন</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">সময়</th>
                        <th class="p-3.5">লেনদেনের খাত / প্রকার</th>
                        <th class="p-3.5">রেফারেন্স / ভাউচার নং</th>
                        <th class="p-3.5">গ্রাহক / প্রাপক</th>
                        <th class="p-3.5 text-right text-emerald-400">নগদ জমা (Cash In)</th>
                        <th class="p-3.5 text-right text-rose-400">নগদ খরচ (Cash Out)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-3.5 font-mono text-slate-400">
                            {{ $t['time'] }}
                        </td>
                        <td class="p-3.5 font-sans font-semibold text-white">
                            {{ $t['type'] }}
                        </td>
                        <td class="p-3.5 font-mono text-indigo-400 font-semibold">
                            {{ $t['ref'] }}
                        </td>
                        <td class="p-3.5 font-sans text-slate-300">
                            {{ $t['party'] }}
                        </td>
                        <td class="p-3.5 text-right font-bold font-mono tnum text-emerald-400 text-sm">
                            @if($t['inflow'] > 0)
                                + ৳ {{ number_format($t['inflow'], 2) }}
                            @else
                                <span class="text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-bold font-mono tnum text-rose-400 text-sm">
                            @if($t['outflow'] > 0)
                                - ৳ {{ number_format($t['outflow'], 2) }}
                            @else
                                <span class="text-slate-600">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-500 font-sans">এই তারিখে কোনো নগদ লেনদেনের তথ্য নেই।</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($transactions->isNotEmpty())
                <tfoot class="bg-slate-900/80 font-mono tnum font-bold text-sm border-t-2 border-slate-700">
                    <tr>
                        <td colspan="4" class="p-3.5 text-right text-slate-300 font-sans">মোট যোগফল (Total):</td>
                        <td class="p-3.5 text-right text-emerald-400">৳ {{ number_format($totalInflow, 2) }}</td>
                        <td class="p-3.5 text-right text-rose-400">৳ {{ number_format($totalOutflow, 2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

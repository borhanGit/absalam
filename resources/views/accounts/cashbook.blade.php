@extends('layouts.app')

@section('page_title', 'দৈনিক ক্যাশ বুক (Daily Cash Book)')

@section('content')
<div class="space-y-6">
    <!-- Header with Date Picker & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800 print:hidden no-print">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>📖</span> দৈনিক ক্যাশ বুক ও নগদ জমার হিসাব
            </h1>
            <p class="text-xs text-slate-400 mt-1">কাউন্টারের দৈনিক নগদ জমা (Cash In) ও নগদ খরচ/পরিশোধ (Cash Out) রেজিস্টার</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('accounts.cashbook') }}" class="flex items-center gap-2 text-xs">
                <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg transition">তারিখ দেখুন</button>
            </form>
            <button onclick="window.print()" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-xs inline-flex items-center gap-1 transition">
                <span>🖨️</span> প্রিন্ট
            </button>
        </div>
    </div>

    <!-- Daily Cash Summary Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl bg-slate-900/80 border border-emerald-500/20">
            <div class="text-xs font-medium text-emerald-400">আজকের মোট নগদ জমা (Cash In)</div>
            <div class="text-2xl font-bold text-emerald-400 mt-1">৳ {{ number_format($totalInflow, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">নগদ সেলস ও বকেয়া কালেকশন</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-rose-500/20">
            <div class="text-xs font-medium text-rose-400">আজকের মোট নগদ খরচ (Cash Out)</div>
            <div class="text-2xl font-bold text-rose-400 mt-1">৳ {{ number_format($totalOutflow, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">দোকান খরচ, ক্রয় ও মিল পেমেন্ট</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border {{ $netCashToday >= 0 ? 'border-cyan-500/20' : 'border-amber-500/20' }}">
            <div class="text-xs font-medium {{ $netCashToday >= 0 ? 'text-cyan-400' : 'text-amber-400' }}">আজকের দিনের নেট নগদ স্থিতি (Net Cash)</div>
            <div class="text-2xl font-bold text-white mt-1">৳ {{ number_format($netCashToday, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">জমা ও খরচের পার্থক্য</div>
        </div>
    </div>

    <!-- Chronological Cash Ledger Table -->
    <div class="bg-slate-900/60 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="p-4 bg-slate-950/40 border-b border-slate-800 flex justify-between items-center text-xs">
            <span class="font-bold text-white">তারিখের হিসাব বিবরণী: <span class="text-cyan-400 font-mono">{{ $date->format('d F, Y (l)') }}</span></span>
            <span class="text-slate-400">{{ $transactions->count() }} টি লেনদেন</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 font-semibold uppercase text-[11px] border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">সময়</th>
                        <th class="p-3.5">লেনদেনের খাত / প্রকার</th>
                        <th class="p-3.5">রেফারেন্স / ভাউচার নং</th>
                        <th class="p-3.5">গ্রাহক / প্রাপক</th>
                        <th class="p-3.5 text-right text-emerald-400">নগদ জমা (Cash In)</th>
                        <th class="p-3.5 text-right text-rose-400">নগদ খরচ (Cash Out)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="p-3.5 text-slate-400">
                            {{ $t['time'] }}
                        </td>
                        <td class="p-3.5 font-sans font-semibold text-white">
                            {{ $t['type'] }}
                        </td>
                        <td class="p-3.5 text-cyan-400 font-bold">
                            {{ $t['ref'] }}
                        </td>
                        <td class="p-3.5 font-sans text-slate-300">
                            {{ $t['party'] }}
                        </td>
                        <td class="p-3.5 text-right font-bold text-emerald-400 text-sm">
                            @if($t['inflow'] > 0)
                                + ৳ {{ number_format($t['inflow'], 2) }}
                            @else
                                <span class="text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-bold text-rose-400 text-sm">
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
                <tfoot class="bg-slate-950/80 font-mono font-bold text-sm border-t-2 border-slate-700">
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

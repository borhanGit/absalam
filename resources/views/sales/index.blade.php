@extends('layouts.app')

@section('title', 'সেলস চালান তালিকা')
@section('page_title', 'সেলস চালান ও ইনভয়েস তালিকা (Sales Invoices)')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">সর্বমোট বিক্রয়</span>
            <div class="text-xl font-black text-white font-mono-numbers mt-1">৳{{ number_format($totalSalesAmount, 2) }}</div>
        </div>
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">মোট ক্যাশ সংগ্রহ</span>
            <div class="text-xl font-black text-emerald-400 font-mono-numbers mt-1">৳{{ number_format($totalPaidAmount, 2) }}</div>
        </div>
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">মোট বাকি টাকা</span>
            <div class="text-xl font-black text-amber-400 font-mono-numbers mt-1">৳{{ number_format($totalDueAmount, 2) }}</div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('sales.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="চালান নং বা কাস্টমার খুঁজুন..." class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-cyan-500 w-64">
            
            <select name="payment_status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                <option value="">সকল স্ট্যাটাস</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>পরিশোধ (Paid)</option>
                <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>আংশিক (Partial)</option>
                <option value="due" {{ request('payment_status') === 'due' ? 'selected' : '' }}>বাকি (Due)</option>
            </select>

            <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition">
                ফিল্টার
            </button>
            @if(request()->hasAny(['search', 'payment_status', 'date']))
                <a href="{{ route('sales.index') }}" class="px-3 py-2 text-xs text-rose-400 hover:underline">রিসেট</a>
            @endif
        </form>

        <a href="{{ route('pos.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/20 transition">
            <span>⚡</span> নতুন পিওএস সেলস
        </a>
    </div>

    <!-- Sales Table -->
    <div class="rounded-2xl border border-slate-800 glass-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="p-3">চালান নং</th>
                        <th class="p-3">তারিখ</th>
                        <th class="p-3">কাস্টমার / মিস্ত্রি</th>
                        <th class="p-3">শাখা / ওয়্যারহাউস</th>
                        <th class="p-3 text-right">আইটেম মোট</th>
                        <th class="p-3 text-right">ভ্যান/লেবার</th>
                        <th class="p-3 text-right">সর্বমোট বিল</th>
                        <th class="p-3 text-right">জমা</th>
                        <th class="p-3 text-right">বাকি</th>
                        <th class="p-3 text-center">স্ট্যাটাস</th>
                        <th class="p-3 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="p-3 font-mono font-bold text-cyan-400">
                                <a href="{{ route('sales.show', $sale->id) }}" class="hover:underline">{{ $sale->invoice_no }}</a>
                            </td>
                            <td class="p-3 text-slate-400">{{ $sale->sale_date }}</td>
                            <td class="p-3">
                                <span class="font-bold text-white block">{{ $sale->customer->name ?? '-' }}</span>
                                <span class="text-[11px] text-slate-500">{{ $sale->customer->phone ?? '' }}</span>
                            </td>
                            <td class="p-3 text-slate-400">{{ $sale->warehouse->name ?? '-' }}</td>
                            <td class="p-3 text-right font-mono-numbers">৳{{ number_format($sale->subtotal, 2) }}</td>
                            <td class="p-3 text-right font-mono-numbers text-slate-400">৳{{ number_format($sale->carrying_cost + $sale->labor_fitting_cost, 2) }}</td>
                            <td class="p-3 text-right font-mono-numbers font-bold text-white">৳{{ number_format($sale->grand_total, 2) }}</td>
                            <td class="p-3 text-right font-mono-numbers text-emerald-400">৳{{ number_format($sale->paid_amount, 2) }}</td>
                            <td class="p-3 text-right font-mono-numbers font-bold text-amber-400">৳{{ number_format($sale->due_amount, 2) }}</td>
                            <td class="p-3 text-center">
                                @if($sale->payment_status === 'paid')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">পরিশোধ</span>
                                @elseif($sale->payment_status === 'partial')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">আংশিক</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">বাকি</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('sales.show', $sale->id) }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 transition font-semibold text-[11px]">
                                    চালান দেখুন ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="p-8 text-center text-slate-500">
                                কোনো বিক্রয় চালান পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/60">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

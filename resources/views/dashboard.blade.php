@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'ব্যবসা ওভারভিউ ও কন্ট্রোল প্যানেল (Dashboard)')

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <!-- Card 1: Today Sales -->
        <div class="rounded-xl glass-panel p-4 border border-slate-800">
            <div class="flex items-center justify-between text-slate-400 text-xs">
                <span>আজকের মোট বিক্রয়</span>
                <span class="p-1.5 rounded-lg bg-cyan-500/10 text-cyan-400">🛒</span>
            </div>
            <div class="mt-2">
                <span class="text-2xl font-black text-white font-mono-numbers">৳{{ number_format($todaySales, 2) }}</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">ক্যাশ ও ক্রেডিট সর্বমোট</p>
        </div>

        <!-- Card 2: Today Collection -->
        <div class="rounded-xl glass-panel p-4 border border-slate-800">
            <div class="flex items-center justify-between text-slate-400 text-xs">
                <span>আজকের ক্যাশ আদায়</span>
                <span class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400">💵</span>
            </div>
            <div class="mt-2">
                <span class="text-2xl font-black text-emerald-400 font-mono-numbers">৳{{ number_format($todayCollections, 2) }}</span>
            </div>
            <p class="text-[11px] text-emerald-500 mt-1">কাউন্টার ও বাকি কালেকশন</p>
        </div>

        <!-- Card 3: Customer Receivables -->
        <div class="rounded-xl glass-panel p-4 border border-slate-800">
            <div class="flex items-center justify-between text-slate-400 text-xs">
                <span>মোট কাস্টমার বাকি</span>
                <span class="p-1.5 rounded-lg bg-amber-500/10 text-amber-400">👥</span>
            </div>
            <div class="mt-2">
                <span class="text-2xl font-black text-amber-400 font-mono-numbers">৳{{ number_format($totalCustomerDue, 2) }}</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">মিস্ত্রি ও পার্টির মোট বাকি</p>
        </div>

        <!-- Card 4: Supplier Payables -->
        <div class="rounded-xl glass-panel p-4 border border-slate-800">
            <div class="flex items-center justify-between text-slate-400 text-xs">
                <span>মিল ও সাপ্লায়ার দেনা</span>
                <span class="p-1.5 rounded-lg bg-rose-500/10 text-rose-400">🏭</span>
            </div>
            <div class="mt-2">
                <span class="text-2xl font-black text-rose-400 font-mono-numbers">৳{{ number_format($totalSupplierPayable, 2) }}</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">মিল চালানের বকেয়া বিল</p>
        </div>

        <!-- Card 5: Cheques in Hand -->
        <div class="rounded-xl glass-panel p-4 border border-slate-800 col-span-2 md:col-span-1">
            <div class="flex items-center justify-between text-slate-400 text-xs">
                <span>হাতে থাকা চেক</span>
                <span class="p-1.5 rounded-lg bg-purple-500/10 text-purple-400">🏦</span>
            </div>
            <div class="mt-2">
                <span class="text-2xl font-black text-purple-300 font-mono-numbers">৳{{ number_format($chequesInHand, 2) }}</span>
            </div>
            <p class="text-[11px] text-purple-400 mt-1">{{ $chequesInHandCount }} টি জমা দেওয়ার অপেক্ষায়</p>
        </div>
    </div>

    <!-- Quick Action Launchpad -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('pos.create') }}" class="p-4 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold flex items-center justify-between shadow-lg shadow-cyan-500/20 transition group">
            <div>
                <span class="text-xs uppercase tracking-wider block text-cyan-100">কাউন্টার সেলস</span>
                <span class="text-base text-white">⚡ নতুন পিওএস (POS)</span>
            </div>
            <span class="text-xl group-hover:scale-110 transition">🛒</span>
        </a>

        <a href="{{ route('quotations.create') }}" class="p-4 rounded-xl glass-panel border border-slate-800 hover:border-cyan-500/50 text-white font-bold flex items-center justify-between transition group">
            <div>
                <span class="text-xs text-slate-400 uppercase tracking-wider block">প্রজেক্ট এস্টিমেটর</span>
                <span class="text-base text-white">📐 জানালা/দরজা কোটেশন</span>
            </div>
            <span class="text-xl group-hover:scale-110 transition">📝</span>
        </a>

        <a href="{{ route('purchases.create') }}" class="p-4 rounded-xl glass-panel border border-slate-800 hover:border-emerald-500/50 text-white font-bold flex items-center justify-between transition group">
            <div>
                <span class="text-xs text-slate-400 uppercase tracking-wider block">মিল থেকে মাল গ্রহণ</span>
                <span class="text-base text-white">🏭 নতুন পারচেজ এন্ট্রি</span>
            </div>
            <span class="text-xl group-hover:scale-110 transition">📦</span>
        </a>

        <a href="{{ route('receipts.create') }}" class="p-4 rounded-xl glass-panel border border-slate-800 hover:border-amber-500/50 text-white font-bold flex items-center justify-between transition group">
            <div>
                <span class="text-xs text-slate-400 uppercase tracking-wider block">বাকি আদায়</span>
                <span class="text-base text-white">💵 মানি রিসিট গ্রহণ</span>
            </div>
            <span class="text-xl group-hover:scale-110 transition">🧾</span>
        </a>
    </div>

    <!-- Two Columns: Recent Invoices & Available Off-Cuts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1 & 2: Recent Sales Invoices -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-800 glass-panel p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <span>🛒</span> সাম্প্রতিক সেলস চালানসমূহ (Recent Invoices)
                    </h3>
                </div>
                <a href="{{ route('sales.index') }}" class="text-xs text-cyan-400 hover:underline">সকল চালান দেখুন →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-800/60 text-slate-400">
                        <tr>
                            <th class="p-2.5 rounded-l-lg">চালান নং</th>
                            <th class="p-2.5">কাস্টমার / মিস্ত্রি</th>
                            <th class="p-2.5">তারিখ</th>
                            <th class="p-2.5">মোট মূল্য</th>
                            <th class="p-2.5">পরিশোধ</th>
                            <th class="p-2.5">বাকি</th>
                            <th class="p-2.5 rounded-r-lg">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($recentSales as $sale)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="p-2.5 font-mono text-cyan-400 font-bold">
                                    <a href="{{ route('sales.show', $sale->id) }}" class="hover:underline">{{ $sale->invoice_no }}</a>
                                </td>
                                <td class="p-2.5 font-medium text-white">{{ $sale->customer->name ?? '-' }}</td>
                                <td class="p-2.5 text-slate-400">{{ $sale->sale_date }}</td>
                                <td class="p-2.5 font-bold font-mono-numbers text-white">৳{{ number_format($sale->grand_total, 2) }}</td>
                                <td class="p-2.5 text-emerald-400 font-mono-numbers">৳{{ number_format($sale->paid_amount, 2) }}</td>
                                <td class="p-2.5 text-amber-400 font-mono-numbers">৳{{ number_format($sale->due_amount, 2) }}</td>
                                <td class="p-2.5">
                                    @if($sale->payment_status === 'paid')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">পরিশোধ</span>
                                    @elseif($sale->payment_status === 'partial')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">আংশিক</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">বাকি</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-slate-500">
                                    এখনও কোনো সেলস চালান তৈরি হয়নি। <a href="{{ route('pos.create') }}" class="text-cyan-400 font-bold hover:underline">প্রথম সেলস তৈরি করুন</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Col 3: Available Cut Leftovers (Off-cuts) -->
        <div class="rounded-2xl border border-slate-800 glass-panel p-5 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <span>✂️</span> স্টকে থাকা কাটিং অফ-কাট (Off-Cuts)
                    </h3>
                    <p class="text-[11px] text-slate-400">ছোট অর্ডারে রিইউজ করার জন্য মজুত টুকরো</p>
                </div>
                <a href="{{ route('offcuts.index') }}" class="text-xs text-cyan-400 hover:underline">সব →</a>
            </div>

            <div class="space-y-2">
                @forelse($recentOffCuts as $cut)
                    <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-white block">{{ $cut->product->name ?? '-' }}</span>
                            <span class="text-slate-400 text-[11px]">
                                সাইজ: <strong class="text-cyan-400">{{ $cut->length_feet }} ফুট</strong> 
                                @if($cut->width_inch) × {{ $cut->width_inch }}" @endif
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 rounded bg-cyan-500/10 text-cyan-300 font-bold font-mono">{{ $cut->quantity }} পিস</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-500 text-xs">
                        বর্তমানে কোনো অবিক্রিত অফ-কাট টুকরো নেই।
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

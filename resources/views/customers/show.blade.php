@extends('layouts.app')

@section('title', 'লেজার: ' . $customer->name)
@section('page_title', '👥 খতিয়ান ও লেজার স্টেটমেন্ট: ' . $customer->name)

@section('content')
<div class="space-y-6">

    <!-- Customer Overview Card -->
    <div class="p-6 rounded-2xl glass-panel border border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-bold text-white">{{ $customer->name }}</h2>
                <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                    {{ ucfirst(str_replace('_', ' ', $customer->customer_type)) }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">📞 {{ $customer->phone }} • 📍 {{ $customer->address ?? 'ঠিকানা নেই' }}</p>
        </div>

        <div class="flex items-center gap-6">
            <div class="text-right">
                <span class="text-xs text-slate-400 block">বর্তমান বকেয়া (Due)</span>
                <span class="text-2xl font-black font-mono-numbers {{ $customer->current_balance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                    ৳{{ number_format($customer->current_balance, 2) }}
                </span>
            </div>
            <a href="{{ route('receipts.create') }}?customer_id={{ $customer->id }}" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs shadow-lg shadow-emerald-500/20 transition">
                💵 টাকা গ্রহণ (Money Receipt)
            </a>
        </div>
    </div>

    <!-- Two Tabs: Sales History & Money Receipts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Challan History -->
        <div class="rounded-2xl border border-slate-800 glass-panel p-5">
            <h3 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                <span>🛒</span> চালানে মাল গ্রহণের ইতিহাস
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-900 text-slate-400">
                        <tr>
                            <th class="p-2.5">চালান নং</th>
                            <th class="p-2.5">তারিখ</th>
                            <th class="p-2.5 text-right">মোট বিল</th>
                            <th class="p-2.5 text-right">নগদ</th>
                            <th class="p-2.5 text-right">বাকি</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-slate-300">
                        @forelse($customer->sales as $sale)
                            <tr class="hover:bg-slate-800/30">
                                <td class="p-2.5 font-mono text-cyan-400 font-bold">
                                    <a href="{{ route('sales.show', $sale->id) }}" class="hover:underline">{{ $sale->invoice_no }}</a>
                                </td>
                                <td class="p-2.5 text-slate-400">{{ $sale->sale_date }}</td>
                                <td class="p-2.5 text-right font-mono-numbers font-bold text-white">৳{{ number_format($sale->grand_total, 2) }}</td>
                                <td class="p-2.5 text-right font-mono-numbers text-emerald-400">৳{{ number_format($sale->paid_amount, 2) }}</td>
                                <td class="p-2.5 text-right font-mono-numbers text-amber-400">৳{{ number_format($sale->due_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-500">এখনও কোনো বিক্রয় রেকর্ড নেই।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payments / Money Receipt History -->
        <div class="rounded-2xl border border-slate-800 glass-panel p-5">
            <h3 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                <span>🧾</span> বাকি পরিশোধ ও রিসিটের ইতিহাস
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-900 text-slate-400">
                        <tr>
                            <th class="p-2.5">রিসিট নং</th>
                            <th class="p-2.5">তারিখ</th>
                            <th class="p-2.5">মাধ্যম</th>
                            <th class="p-2.5 text-right">জমা টাকা (৳)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-slate-300">
                        @forelse($customer->receipts as $r)
                            <tr class="hover:bg-slate-800/30">
                                <td class="p-2.5 font-mono text-cyan-400 font-bold">{{ $r->receipt_no }}</td>
                                <td class="p-2.5 text-slate-400">{{ $r->receipt_date }}</td>
                                <td class="p-2.5">
                                    <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 uppercase font-mono text-[10px]">{{ $r->payment_method }}</span>
                                </td>
                                <td class="p-2.5 text-right font-bold font-mono-numbers text-emerald-400">৳{{ number_format($r->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-slate-500">এখনও কোনো মানি রিসিট গ্রহণ করা হয়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'পারচেজ তালিকা')
@section('page_title', '🏭 ফ্যাক্টরি পারচেজ ও ক্রয় চালান (Mill Purchases)')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">সর্বমোট ক্রয়কৃত মাল</span>
            <div class="text-xl font-black text-white font-mono-numbers mt-1">৳{{ number_format($totalPurchases, 2) }}</div>
        </div>
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">মোট পরিশোধ</span>
            <div class="text-xl font-black text-emerald-400 font-mono-numbers mt-1">৳{{ number_format($totalPaid, 2) }}</div>
        </div>
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">সাপ্লায়ার মোট বাকি দেনা</span>
            <div class="text-xl font-black text-rose-400 font-mono-numbers mt-1">৳{{ number_format($totalDue, 2) }}</div>
        </div>
    </div>

    <!-- Actions & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('purchases.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="পারচেজ নং বা সাপ্লায়ার খুঁজুন..." class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-cyan-500 w-72">
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition">
                ফিল্টার
            </button>
        </form>

        <a href="{{ route('purchases.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/20 transition">
            <span>➕</span> নতুন পারচেজ এন্ট্রি
        </a>
    </div>

    <!-- Purchases Table -->
    <div class="rounded-2xl border border-slate-800 glass-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="p-3">পারচেজ নং</th>
                        <th class="p-3">তারিখ</th>
                        <th class="p-3">সাপ্লায়ার / মিলের নাম</th>
                        <th class="p-3">গোডাউন</th>
                        <th class="p-3 text-right">আইটেম মোট</th>
                        <th class="p-3 text-right">ট্রাক/কুলি খরচ</th>
                        <th class="p-3 text-right">সর্বমোট চালান</th>
                        <th class="p-3 text-right">পরিশোধ</th>
                        <th class="p-3 text-right">বাকি</th>
                        <th class="p-3 text-center">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($purchases as $p)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="p-3 font-mono font-bold text-cyan-400">
                                {{ $p->purchase_no }}
                                @if($p->supplier_invoice_no)
                                    <span class="block text-[10px] text-slate-500">চালান: {{ $p->supplier_invoice_no }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-slate-400">{{ $p->purchase_date }}</td>
                            <td class="p-3 font-bold text-white">{{ $p->supplier->name ?? '-' }}</td>
                            <td class="p-3 text-slate-400">{{ $p->warehouse->name ?? '-' }}</td>
                            <td class="p-3 text-right font-mono-numbers">৳{{ number_format($p->subtotal, 2) }}</td>
                            <td class="p-3 text-right font-mono-numbers text-slate-400">৳{{ number_format($p->carrying_cost + $p->labor_cost, 2) }}</td>
                            <td class="p-3 text-right font-mono-numbers font-bold text-white">৳{{ number_format($p->total_amount, 2) }}</td>
                            <td class="p-3 text-right font-mono-numbers text-emerald-400">৳{{ number_format($p->paid_amount, 2) }}</td>
                            <td class="p-3 text-right font-mono-numbers font-bold text-rose-400">৳{{ number_format($p->due_amount, 2) }}</td>
                            <td class="p-3 text-center">
                                @if($p->payment_status === 'paid')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">পরিশোধ</span>
                                @elseif($p->payment_status === 'partial')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">আংশিক</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">বাকি</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-8 text-center text-slate-500">
                                কোনো পারচেজ চালান পাওয়া যায়নি। <a href="{{ route('purchases.create') }}" class="text-cyan-400 font-bold hover:underline">নতুন ক্রয় এন্ট্রি করুন</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/60">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'পারচেজ তালিকা')
@section('page_title', 'ফ্যাক্টরি পারচেজ ও ক্রয় চালান (Mill Purchases)')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl card-shell">
            <span class="text-xs text-[#64748d] font-medium">সর্বমোট ক্রয়কৃত মাল</span>
            <div class="text-xl font-bold text-white tnum mt-1">৳{{ number_format($totalPurchases, 2) }}</div>
        </div>
        <div class="p-4 rounded-xl card-shell">
            <span class="text-xs text-[#64748d] font-medium">মোট পরিশোধ</span>
            <div class="text-xl font-bold text-emerald-400 tnum mt-1">৳{{ number_format($totalPaid, 2) }}</div>
        </div>
        <div class="p-4 rounded-xl card-shell">
            <span class="text-xs text-[#64748d] font-medium">সাপ্লায়ার মোট বাকি দেনা</span>
            <div class="text-xl font-bold text-rose-400 tnum mt-1">৳{{ number_format($totalDue, 2) }}</div>
        </div>
    </div>

    <!-- Actions & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('purchases.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="পারচেজ নং বা সাপ্লায়ার খুঁজুন..." class="px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs placeholder-[#64748d] focus:outline-none focus:border-[#533afd] w-72">
            <button type="submit" class="btn-secondary text-xs py-2 px-4">
                ফিল্টার
            </button>
            @if(request('search'))
                <a href="{{ route('purchases.index') }}" class="px-3 py-2 text-xs text-rose-400 hover:underline">রিসেট</a>
            @endif
        </form>

        <a href="{{ route('purchases.create') }}" class="btn-primary">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>
            </svg>
            <span>নতুন পারচেজ এন্ট্রি</span>
        </a>
    </div>

    <!-- Purchases Table -->
    <div class="rounded-xl card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#070d18]/80 text-[#64748d] border-b border-[#e3e8ee]/5">
                    <tr>
                        <th class="p-3 font-semibold">পারচেজ নং</th>
                        <th class="p-3 font-semibold">তারিখ</th>
                        <th class="p-3 font-semibold">সাপ্লায়ার / মিলের নাম</th>
                        <th class="p-3 font-semibold">গোডাউন</th>
                        <th class="p-3 font-semibold text-right">আইটেম মোট</th>
                        <th class="p-3 font-semibold text-right">ট্রাক/কুলি খরচ</th>
                        <th class="p-3 font-semibold text-right">সর্বমোট চালান</th>
                        <th class="p-3 font-semibold text-right">পরিশোধ</th>
                        <th class="p-3 font-semibold text-right">বাকি</th>
                        <th class="p-3 font-semibold text-center">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e8ee]/5 text-slate-300">
                    @forelse($purchases as $p)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="p-3 font-mono font-semibold">
                                <span class="text-[#533afd]">{{ $p->purchase_no }}</span>
                                @if($p->supplier_invoice_no)
                                    <span class="block text-[10px] text-[#64748d]">চালান: {{ $p->supplier_invoice_no }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-[#64748d] tnum">{{ $p->purchase_date }}</td>
                            <td class="p-3 font-medium text-white">{{ $p->supplier->name ?? '-' }}</td>
                            <td class="p-3 text-[#64748d]">{{ $p->warehouse->name ?? '-' }}</td>
                            <td class="p-3 text-right tnum">৳{{ number_format($p->subtotal, 2) }}</td>
                            <td class="p-3 text-right tnum text-[#64748d]">৳{{ number_format($p->carrying_cost + $p->labor_cost, 2) }}</td>
                            <td class="p-3 text-right tnum font-semibold text-white">৳{{ number_format($p->total_amount, 2) }}</td>
                            <td class="p-3 text-right tnum text-emerald-400">৳{{ number_format($p->paid_amount, 2) }}</td>
                            <td class="p-3 text-right tnum font-semibold text-rose-400">৳{{ number_format($p->due_amount, 2) }}</td>
                            <td class="p-3 text-center">
                                @if($p->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">পরিশোধ</span>
                                @elseif($p->payment_status === 'partial')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">আংশিক</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">বাকি</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-8 text-center text-[#64748d]">
                                কোনো পারচেজ চালান পাওয়া যায়নি। <a href="{{ route('purchases.create') }}" class="text-[#533afd] font-semibold hover:underline">নতুন ক্রয় এন্ট্রি করুন</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
            <div class="p-4 border-t border-[#e3e8ee]/10 bg-[#070d18]/60">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

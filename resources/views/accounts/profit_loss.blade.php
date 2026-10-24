@extends('layouts.app')

@section('page_title', 'লাভ-লোকসান বিবরণী (Profit & Loss Statement)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Date Filters -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>📈</span> লাভ-লোকসান বিবরণী (Income Statement)
            </h1>
            <p class="text-xs text-slate-400 mt-1">নির্দিষ্ট মেয়াদের মোট বিক্রয় আয়, মাল ক্রয় খরচ, দোকান পরিচালনা ব্যয় ও নিট লাভ</p>
        </div>
        <form method="GET" action="{{ route('accounts.profit-loss') }}" class="flex flex-wrap items-center gap-2 text-xs">
            <input type="date" name="from_date" value="{{ $fromDate->format('Y-m-d') }}" class="bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
            <span class="text-slate-500">হতে</span>
            <input type="date" name="to_date" value="{{ $toDate->format('Y-m-d') }}" class="bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
            <button type="submit" class="px-3 py-1.5 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg transition">ফিল্টার</button>
        </form>
    </div>

    <!-- P&L Sheet Card -->
    <div class="bg-slate-900/80 rounded-2xl border border-slate-800 p-6 space-y-6">
        <div class="text-center border-b border-slate-800 pb-4">
            <h2 class="text-base font-bold text-white uppercase tracking-wider">লাভ ও লোকসান বিবরণী</h2>
            <p class="text-xs text-cyan-400 font-mono mt-0.5">{{ $fromDate->format('d M, Y') }} হতে {{ $toDate->format('d M, Y') }} পর্যন্ত</p>
        </div>

        <!-- Section 1: Operating Revenue -->
        <div class="space-y-3">
            <div class="flex justify-between items-center text-xs font-bold text-cyan-400 uppercase tracking-wider border-b border-slate-800 pb-2">
                <span>১. ব্যবসায়িক মোট বিক্রয় রাজস্ব (Operating Revenue)</span>
                <span>পরিমাণ (৳)</span>
            </div>
            <div class="flex justify-between items-center text-xs text-slate-300 px-2">
                <span>মোট সেলস ও ইনভয়েস বিল ({{ $salesCount }} টি ইনভয়েস)</span>
                <span class="font-mono font-bold text-white">৳ {{ number_format($totalRevenue, 2) }}</span>
            </div>
            <div class="flex justify-between items-center text-xs font-bold text-emerald-400 bg-emerald-500/10 p-2.5 rounded-xl border border-emerald-500/20">
                <span>মোট বিক্রয় আয় (Total Sales) [A]</span>
                <span class="font-mono text-sm">৳ {{ number_format($totalRevenue, 2) }}</span>
            </div>
        </div>

        <!-- Section 2: Cost of Goods / Purchases -->
        <div class="space-y-3">
            <div class="flex justify-between items-center text-xs font-bold text-amber-400 uppercase tracking-wider border-b border-slate-800 pb-2">
                <span>২. পণ্য ক্রয় ও উপাদান ব্যয় (Cost of Purchases / COGS)</span>
                <span>পরিমাণ (৳)</span>
            </div>
            <div class="flex justify-between items-center text-xs text-slate-300 px-2">
                <span>অ্যালুমিনিয়াম বার, গ্লাস ও এক্সেসরিজ ফ্যাক্টরি চালান ক্রয়</span>
                <span class="font-mono text-slate-300">৳ {{ number_format($totalPurchases, 2) }}</span>
            </div>
            <div class="flex justify-between items-center text-xs font-bold text-amber-400 bg-amber-500/10 p-2.5 rounded-xl border border-amber-500/20">
                <span>মোট ক্রয় ব্যয় (Total Material Cost) [B]</span>
                <span class="font-mono text-sm">৳ {{ number_format($totalPurchases, 2) }}</span>
            </div>
        </div>

        <!-- Gross Profit Bar -->
        <div class="p-4 rounded-xl bg-slate-950 border border-slate-700 flex justify-between items-center">
            <div>
                <span class="text-xs font-bold text-white block">মোট ব্যবসায়িক লাভ (Gross Profit) [A - B]</span>
                <span class="text-[11px] text-slate-400">বিক্রয় মূল্য হতে ক্রয়ের পার্থক্যের মার্জিন</span>
            </div>
            <div class="text-xl font-bold font-mono {{ $grossProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                ৳ {{ number_format($grossProfit, 2) }}
            </div>
        </div>

        <!-- Section 3: Operating Expenses -->
        <div class="space-y-3">
            <div class="flex justify-between items-center text-xs font-bold text-rose-400 uppercase tracking-wider border-b border-slate-800 pb-2">
                <span>৩. দোকান পরিচালনা ও অপারেটিং খরচ (Operating Expenses)</span>
                <span>পরিমাণ (৳)</span>
            </div>
            @forelse($expenses as $exp)
            <div class="flex justify-between items-center text-xs text-slate-300 px-2">
                <span>{{ $exp->category_name }}</span>
                <span class="font-mono text-rose-300">৳ {{ number_format($exp->total_cat_amount, 2) }}</span>
            </div>
            @empty
            <div class="text-xs text-slate-500 italic px-2">এই মেয়াদে কোনো দোকান খরচ এন্ট্রি নেই।</div>
            @endforelse
            <div class="flex justify-between items-center text-xs font-bold text-rose-400 bg-rose-500/10 p-2.5 rounded-xl border border-rose-500/20">
                <span>মোট পরিচালনা ব্যয় (Total Operating Cost) [C]</span>
                <span class="font-mono text-sm">৳ {{ number_format($totalExpenses, 2) }}</span>
            </div>
        </div>

        <!-- Final Net Profit Banner -->
        <div class="p-6 rounded-2xl bg-gradient-to-r {{ $netProfit >= 0 ? 'from-emerald-950 via-teal-900 to-slate-900 border-2 border-emerald-500/40' : 'from-rose-950 via-red-900 to-slate-900 border-2 border-rose-500/40' }} flex justify-between items-center">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }} block">
                    {{ $netProfit >= 0 ? '🎉 নিট অর্জিত লাভ (NET PROFIT)' : '⚠️ নিট লোকসান (NET LOSS)' }}
                </span>
                <span class="text-xs text-slate-300">[মোট লাভ - মোট পরিচালনা ব্যয়]</span>
            </div>
            <div class="text-3xl font-black font-mono {{ $netProfit >= 0 ? 'text-emerald-300' : 'text-rose-300' }}">
                ৳ {{ number_format($netProfit, 2) }}
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'কালেকশন ও মানি রিসিট')
@section('page_title', 'বকেয়া কালেকশন ও মানি রিসিট (Money Receipts)')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="card-shell p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-lg font-bold text-white tracking-tight">বকেয়া কালেকশন ও মানি রিসিট রেজিস্টার</h1>
            <p class="text-xs text-slate-400 mt-0.5">কাস্টমার ও থাই মিস্ত্রিদের নগদ বা চেকের মাধ্যমে পরিশোধকৃত টাকা গ্রহণ ও রিসিট ভাউচার</p>
        </div>
        <a href="{{ route('receipts.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            নতুন মানি রিসিট কাটুন
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-emerald-400">সর্বমোট কালেকশন</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['total_collected'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">সব রিসিটের মোট পরিমাণ</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-indigo-400">আজকের কালেকশন</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['today_collected'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">{{ date('d M, Y') }}</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-teal-400">নগদ টাকা (Cash)</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['cash_collected'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">ক্যাশ কাউন্টারে জমা</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-sky-400">ব্যাংক চেক মারফত</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['cheque_collected'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">ক্লিয়ারেন্স চেক</div>
        </div>
    </div>

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('receipts.index') }}" class="card-shell p-4 grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
        <div>
            <label class="block text-slate-400 font-semibold mb-1">কাস্টমার দিয়ে খুঁজুন</label>
            <select name="customer_id" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
                <option value="">-- সকল কাস্টমার --</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-slate-400 font-semibold mb-1">পেমেন্ট মেথড</label>
            <select name="payment_method" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
                <option value="">-- সব মাধ্যম --</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>ক্যাশ (Cash)</option>
                <option value="cheque" {{ request('payment_method') === 'cheque' ? 'selected' : '' }}>ব্যাংক চেক (Cheque)</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>ব্যাংক ট্রান্সফার</option>
                <option value="bkash" {{ request('payment_method') === 'bkash' ? 'selected' : '' }}>বিকাশ / নগদ</option>
            </select>
        </div>
        <div>
            <label class="block text-slate-400 font-semibold mb-1">তারিখ থেকে</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1 justify-center py-2 text-xs">ফিল্টার করুন</button>
            <a href="{{ route('receipts.index') }}" class="btn-secondary py-2 text-xs">রিসেট</a>
        </div>
    </form>

    <!-- Receipts Table -->
    <div class="card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">রিসিট নং</th>
                        <th class="p-3.5">কাস্টমার / মিস্ত্রি</th>
                        <th class="p-3.5">তারিখ</th>
                        <th class="p-3.5">পেমেন্ট মাধ্যম</th>
                        <th class="p-3.5">চেক / রেফারেন্স</th>
                        <th class="p-3.5 text-right">আদায়ের পরিমাণ</th>
                        <th class="p-3.5 text-right">ভাউচার</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($receipts as $r)
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-3.5 font-mono font-semibold text-indigo-400">
                            <a href="{{ route('receipts.show', $r->id) }}" class="hover:underline">{{ $r->receipt_no }}</a>
                        </td>
                        <td class="p-3.5 font-sans">
                            <a href="{{ route('customers.show', $r->customer_id) }}" class="font-semibold text-white hover:text-indigo-400 block">{{ $r->customer->name ?? '-' }}</a>
                            <span class="text-[11px] text-slate-500 font-mono">{{ $r->customer->phone ?? '' }}</span>
                        </td>
                        <td class="p-3.5 text-slate-400">
                            {{ \Carbon\Carbon::parse($r->receipt_date)->format('d M, Y') }}
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($r->payment_method === 'cash')
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">নগদ ক্যাশ</span>
                            @elseif($r->payment_method === 'cheque')
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">ব্যাংক চেক</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-sky-500/10 text-sky-400 border border-sky-500/20">{{ strtoupper($r->payment_method) }}</span>
                            @endif
                        </td>
                        <td class="p-3.5 font-sans text-slate-400">
                            @if($r->cheque)
                                <span class="text-white font-mono block">#{{ $r->cheque->cheque_no }}</span>
                                <span class="text-[10px] text-slate-500">{{ $r->cheque->bank_name }}</span>
                            @elseif($r->transaction_reference)
                                <span class="font-mono text-slate-300">{{ $r->transaction_reference }}</span>
                            @else
                                <span class="text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-bold font-mono tnum text-emerald-400 text-sm">
                            ৳ {{ number_format($r->amount, 2) }}
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <a href="{{ route('receipts.show', $r->id) }}" class="btn-secondary py-1 px-2.5 text-[11px] inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                ভাউচার
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 font-sans">এখনো কোনো মানি রিসিট কাটা হয়নি।</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($receipts->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-900/60">
            {{ $receipts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

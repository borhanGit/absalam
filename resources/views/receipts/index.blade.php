@extends('layouts.app')

@section('page_title', 'কালেকশন ও মানি রিসিট (Money Receipts)')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>💵</span> বকেয়া কালেকশন ও মানি রিসিট
            </h1>
            <p class="text-xs text-slate-400 mt-1">কাস্টমার ও থাই মিস্ত্রিদের নগদ বা চেকের মাধ্যমে পরিশোধকৃত টাকা গ্রহণ ও রিসিট ভাউচার</p>
        </div>
        <a href="{{ route('receipts.create') }}" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-500/20 transition flex items-center gap-2">
            <span>➕</span> নতুন মানি রিসিট কাটুন
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-slate-900/80 border border-emerald-500/20">
            <div class="text-xs font-medium text-emerald-400">সর্বমোট কালেকশন</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['total_collected'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">সব রিসিটের মোট পরিমাণ</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-teal-500/20">
            <div class="text-xs font-medium text-teal-400">আজকের কালেকশন (Today)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['today_collected'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">{{ date('d M, Y') }}</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-cyan-500/20">
            <div class="text-xs font-medium text-cyan-400">নগদ টাকা (Cash)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['cash_collected'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">ক্যাশ কাউন্টারে জমা</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-indigo-500/20">
            <div class="text-xs font-medium text-indigo-400">ব্যাংক চেক মারফত</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['cheque_collected'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">ক্লিয়ারেন্স চেক</div>
        </div>
    </div>

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('receipts.index') }}" class="p-4 rounded-xl bg-slate-900/50 border border-slate-800 grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
        <div>
            <label class="block text-slate-400 mb-1">কাস্টমার দিয়ে খুঁজুন</label>
            <select name="customer_id" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
                <option value="">-- সকল কাস্টমার --</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-slate-400 mb-1">পেমেন্ট মেথড</label>
            <select name="payment_method" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
                <option value="">-- সব মাধ্যম --</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>ক্যাশ (Cash)</option>
                <option value="cheque" {{ request('payment_method') === 'cheque' ? 'selected' : '' }}>ব্যাংক চেক (Cheque)</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>ব্যাংক ট্রান্সফার</option>
                <option value="bkash" {{ request('payment_method') === 'bkash' ? 'selected' : '' }}>বিকাশ / নগদ</option>
            </select>
        </div>
        <div>
            <label class="block text-slate-400 mb-1">তারিখ থেকে</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 py-1.5 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg transition">ফিল্টার করুন</button>
            <a href="{{ route('receipts.index') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition">রিসেট</a>
        </div>
    </form>

    <!-- Receipts Table -->
    <div class="bg-slate-900/60 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 font-semibold uppercase text-[11px] border-b border-slate-800">
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
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($receipts as $r)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="p-3.5">
                            <a href="{{ route('receipts.show', $r->id) }}" class="font-bold text-cyan-400 hover:underline">{{ $r->receipt_no }}</a>
                        </td>
                        <td class="p-3.5 font-sans">
                            <a href="{{ route('customers.show', $r->customer_id) }}" class="font-semibold text-white hover:text-cyan-400 block">{{ $r->customer->name ?? '-' }}</a>
                            <span class="text-[10px] text-slate-500">{{ $r->customer->phone ?? '' }}</span>
                        </td>
                        <td class="p-3.5">
                            {{ \Carbon\Carbon::parse($r->receipt_date)->format('d M, Y') }}
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($r->payment_method === 'cash')
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">নগদ ক্যাশ</span>
                            @elseif($r->payment_method === 'cheque')
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">ব্যাংক চেক</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">{{ strtoupper($r->payment_method) }}</span>
                            @endif
                        </td>
                        <td class="p-3.5 font-sans text-slate-400">
                            @if($r->cheque)
                                <span class="text-white font-mono block">#{{ $r->cheque->cheque_no }}</span>
                                <span class="text-[10px]">{{ $r->cheque->bank_name }}</span>
                            @elseif($r->transaction_reference)
                                <span>{{ $r->transaction_reference }}</span>
                            @else
                                <span class="text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-bold text-emerald-400 text-sm">
                            ৳ {{ number_format($r->amount, 2) }}
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <a href="{{ route('receipts.show', $r->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-[11px] inline-flex items-center gap-1 transition">
                                <span>🖨️</span> ভাউচার
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
        <div class="p-3 border-t border-slate-800">
            {{ $receipts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

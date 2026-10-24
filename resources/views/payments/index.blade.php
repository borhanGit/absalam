@extends('layouts.app')

@section('page_title', 'সাপ্লায়ার পেমেন্ট (Supplier Payments)')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>🏭</span> সাপ্লায়ার ও অ্যালুমিনিয়াম মিল পেমেন্ট
            </h1>
            <p class="text-xs text-slate-400 mt-1">কাই, চুংহুয়া বা গ্লাস ফ্যাক্টরির বাকির টাকা পরিশোধ ভাউচার ও খতিয়ান সমন্বয়</p>
        </div>
        <a href="{{ route('payments.create') }}" class="px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-amber-500/20 transition flex items-center gap-2">
            <span>➕</span> নতুন পেমেন্ট করুন
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-slate-900/80 border border-amber-500/20">
            <div class="text-xs font-medium text-amber-400">সর্বমোট পরিশোধকৃত</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['total_paid'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">মোট পরিশোধিত বিল</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-orange-500/20">
            <div class="text-xs font-medium text-orange-400">আজকের পেমেন্ট (Today)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['today_paid'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">{{ date('d M, Y') }}</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-emerald-500/20">
            <div class="text-xs font-medium text-emerald-400">নগদে পরিশোধ (Cash)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['cash_paid'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">ক্যাশ ক্যাশিয়ার পেমেন্ট</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-blue-500/20">
            <div class="text-xs font-medium text-blue-400">চেক / ব্যাংক ট্রান্সফার</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['cheque_paid'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">ব্যাংক ও চেকের মাধ্যমে</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('payments.index') }}" class="p-4 rounded-xl bg-slate-900/50 border border-slate-800 grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
        <div>
            <label class="block text-slate-400 mb-1">সাপ্লায়ার দিয়ে ফিল্টার</label>
            <select name="supplier_id" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
                <option value="">-- সকল সাপ্লায়ার --</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-slate-400 mb-1">পেমেন্ট মেথড</label>
            <select name="payment_method" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
                <option value="">-- সব মাধ্যম --</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>নগদ ক্যাশ</option>
                <option value="cheque" {{ request('payment_method') === 'cheque' ? 'selected' : '' }}>ব্যাংক চেক</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>ব্যাংক ট্রান্সফার / TT / PO</option>
                <option value="bkash" {{ request('payment_method') === 'bkash' ? 'selected' : '' }}>বিকাশ</option>
            </select>
        </div>
        <div>
            <label class="block text-slate-400 mb-1">তারিখ থেকে</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 py-1.5 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-lg transition">ফিল্টার করুন</button>
            <a href="{{ route('payments.index') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition">রিসেট</a>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-slate-900/60 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 font-semibold uppercase text-[11px] border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">ভাউচার নং</th>
                        <th class="p-3.5">সাপ্লায়ার / মিল</th>
                        <th class="p-3.5">তারিখ</th>
                        <th class="p-3.5">পরিশোধের মাধ্যম</th>
                        <th class="p-3.5">চেক / রেফারেন্স</th>
                        <th class="p-3.5 text-right">পরিশোধকৃত টাকা</th>
                        <th class="p-3.5 text-right">ভাউচার</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($payments as $p)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="p-3.5">
                            <a href="{{ route('payments.show', $p->id) }}" class="font-bold text-amber-400 hover:underline">{{ $p->payment_no }}</a>
                        </td>
                        <td class="p-3.5 font-sans">
                            <span class="font-semibold text-white block">{{ $p->supplier->name ?? '-' }}</span>
                            <span class="text-[10px] text-slate-500">{{ $p->supplier->phone ?? '' }}</span>
                        </td>
                        <td class="p-3.5">
                            {{ \Carbon\Carbon::parse($p->payment_date)->format('d M, Y') }}
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($p->payment_method === 'cash')
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">নগদ ক্যাশ</span>
                            @elseif($p->payment_method === 'cheque')
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">ব্যাংক চেক</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">{{ strtoupper($p->payment_method) }}</span>
                            @endif
                        </td>
                        <td class="p-3.5 font-sans text-slate-400">
                            @if($p->cheque)
                                <span class="text-white font-mono block">চেক #{{ $p->cheque->cheque_no }}</span>
                                <span class="text-[10px]">{{ $p->cheque->bank_name }}</span>
                            @elseif($p->transaction_reference)
                                <span>{{ $p->transaction_reference }}</span>
                            @else
                                <span class="text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-bold text-amber-400 text-sm">
                            ৳ {{ number_format($p->amount, 2) }}
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <a href="{{ route('payments.show', $p->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-[11px] inline-flex items-center gap-1 transition">
                                <span>🖨️</span> প্রিন্ট
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 font-sans">এখনো কোনো সাপ্লায়ার পেমেন্ট এন্ট্রি হয়নি।</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
        <div class="p-3 border-t border-slate-800">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

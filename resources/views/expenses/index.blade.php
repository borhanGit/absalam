@extends('layouts.app')

@section('page_title', 'দোকান খরচ (Daily Shop Expenses)')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>📉</span> দোকান পরিচালনা ও দৈনিক খরচের হিসাব
            </h1>
            <p class="text-xs text-slate-400 mt-1">দোকান ভাড়া, বিদ্যুৎ বিল, কারিগর মজুরি, চা-নাস্তা ও কাটিং সরঞ্জাম ক্রয় খরচ</p>
        </div>
        <a href="{{ route('expenses.create') }}" class="px-4 py-2 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-500 hover:to-pink-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-rose-500/20 transition flex items-center gap-2">
            <span>➕</span> নতুন খরচ এন্ট্রি
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl bg-slate-900/80 border border-rose-500/20">
            <div class="text-xs font-medium text-rose-400">আজকের খরচ (Today)</div>
            <div class="text-2xl font-bold text-white mt-1">৳ {{ number_format($stats['today'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">{{ date('d M, Y') }}</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-pink-500/20">
            <div class="text-xs font-medium text-pink-400">চলতি মাসের খরচ (This Month)</div>
            <div class="text-2xl font-bold text-white mt-1">৳ {{ number_format($stats['this_month'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">{{ date('F, Y') }}</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-700">
            <div class="text-xs font-medium text-slate-400">সর্বমোট খরচ (Total Expenses)</div>
            <div class="text-2xl font-bold text-white mt-1">৳ {{ number_format($stats['total_expense'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">সব রেকর্ডকৃত খরচ</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('expenses.index') }}" class="p-4 rounded-xl bg-slate-900/50 border border-slate-800 grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
        <div>
            <label class="block text-slate-400 mb-1">খরচের খাত (Category)</label>
            <select name="category_name" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
                <option value="">-- সকল খাত --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category_name') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-slate-400 mb-1">তারিখ থেকে</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
        </div>
        <div>
            <label class="block text-slate-400 mb-1">তারিখ পর্যন্ত</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 py-1.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-lg transition">ফিল্টার</button>
            <a href="{{ route('expenses.index') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition">রিসেট</a>
        </div>
    </form>

    <!-- Expenses Table -->
    <div class="bg-slate-900/60 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 font-semibold uppercase text-[11px] border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">ভাউচার নং</th>
                        <th class="p-3.5">তারিখ</th>
                        <th class="p-3.5">খরচের খাত</th>
                        <th class="p-3.5">বিবরণ / নোট</th>
                        <th class="p-3.5">পেমেন্ট মাধ্যম</th>
                        <th class="p-3.5 text-right">টাকার পরিমাণ</th>
                        <th class="p-3.5 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($expenses as $e)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="p-3.5 font-bold text-rose-400">
                            {{ $e->expense_no }}
                        </td>
                        <td class="p-3.5">
                            {{ \Carbon\Carbon::parse($e->expense_date)->format('d M, Y') }}
                        </td>
                        <td class="p-3.5 font-sans font-semibold text-white">
                            {{ $e->category_name }}
                        </td>
                        <td class="p-3.5 font-sans text-slate-400 max-w-xs truncate">
                            {{ $e->description ?? '-' }}
                        </td>
                        <td class="p-3.5 font-sans">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-slate-700 uppercase">
                                {{ $e->payment_method }}
                            </span>
                        </td>
                        <td class="p-3.5 text-right font-bold text-rose-400 text-sm">
                            ৳ {{ number_format($e->amount, 2) }}
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <form action="{{ route('expenses.destroy', $e->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই খরচ এন্ট্রিটি মুছে ফেলতে চান?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition">
                                    🗑️ মুছে ফেলুন
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 font-sans">কোনো খরচের রেকর্ড পাওয়া যায়নি।</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
        <div class="p-3 border-t border-slate-800">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

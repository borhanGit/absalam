@extends('layouts.app')

@section('page_title', 'ওয়্যারহাউস স্টক (Warehouse Stocks)')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>📦</span> ওয়্যারহাউস ও গোডাউন স্টক মনিটরিং
            </h1>
            <p class="text-xs text-slate-400 mt-1">শোরুম, কেন্দ্রীয় গোডাউন ও ওয়ার্কশপের অ্যালুমিনিয়াম বার, গ্লাস ও হার্ডওয়্যারের রিয়েলটাইম মজুদ</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('stocks.transfer') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-500/20 transition flex items-center gap-2">
                <span>🔄</span> স্টক ট্রান্সফার (মাল স্থানান্তর)
            </a>
            <a href="{{ route('products.create') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-xs font-semibold transition">
                <span>➕</span> নতুন পণ্য যোগ
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl bg-slate-900/80 border border-cyan-500/20">
            <div class="text-xs font-medium text-cyan-400">সর্বমোট স্টক আইটেম</div>
            <div class="text-2xl font-bold text-white mt-1">{{ number_format($stats['total_items_in_stock'] ?? 0) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">সব ওয়্যারহাউসের যৌথ মজুদ</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-emerald-500/20">
            <div class="text-xs font-medium text-emerald-400">মজুদ পণ্যের ক্রয়মূল্য (Stock Value)</div>
            <div class="text-2xl font-bold text-white mt-1">৳ {{ number_format($stats['total_stock_value'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">ক্রয়মূল্যের ভিত্তিতে গণনা</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-amber-500/20">
            <div class="text-xs font-medium text-amber-400">কম স্টক সতর্কতা (Low Stock Alert)</div>
            <div class="text-2xl font-bold text-white mt-1">{{ $stats['low_stock_count'] ?? 0 }} টি পণ্য</div>
            <div class="text-[11px] text-amber-400/80 mt-0.5">রি-অর্ডার লেভেলের নিচে রয়েছে</div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('stocks.index') }}" class="p-4 rounded-xl bg-slate-900/50 border border-slate-800 grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
        <div>
            <label class="block text-slate-400 mb-1">ওয়্যারহাউস / গোডাউন</label>
            <select name="warehouse_id" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
                <option value="">-- সকল ওয়্যারহাউস --</option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}" {{ request('warehouse_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-slate-400 mb-1">ক্যাটাগরি</label>
            <select name="category_id" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
                <option value="">-- সকল ক্যাটাগরি --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-slate-400 mb-1">পণ্য খুঁজুন</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম, কোড বা সেকশন নং..." class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 py-1.5 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg transition">ফিল্টার</button>
            <a href="{{ route('stocks.index') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition">রিসেট</a>
        </div>
    </form>

    <!-- Stock Table -->
    <div class="bg-slate-900/60 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 font-semibold uppercase text-[11px] border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">পণ্য ও সেকশন নং</th>
                        <th class="p-3.5">ক্যাটাগরি / ব্র্যান্ড</th>
                        <th class="p-3.5">ওয়্যারহাউস অবস্থান</th>
                        <th class="p-3.5 text-right">মজুদ পরিমাণ</th>
                        <th class="p-3.5 text-right">ক্রয় মূল্য</th>
                        <th class="p-3.5 text-right">বিক্রয় মূল্য</th>
                        <th class="p-3.5 text-center">স্টক স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($stocks as $stock)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="p-3.5 font-sans">
                            <span class="font-bold text-white block">{{ $stock->product->name ?? '-' }}</span>
                            <div class="flex items-center gap-2 text-[10px] text-slate-400 font-mono">
                                <span>কোড: {{ $stock->product->code ?? '-' }}</span>
                                @if($stock->product && $stock->product->section_number)
                                    <span class="px-1.5 py-0.2 rounded bg-cyan-500/20 text-cyan-300 font-bold">সেকশন: {{ $stock->product->section_number }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-3.5 font-sans">
                            <span class="text-slate-200 block">{{ $stock->product->category->name ?? '-' }}</span>
                            <span class="text-[10px] text-slate-500">{{ $stock->product->brand->name ?? 'জেনেরিক' }}</span>
                        </td>
                        <td class="p-3.5 font-sans">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                <span>📍</span> {{ $stock->warehouse->name ?? '-' }}
                            </span>
                        </td>
                        <td class="p-3.5 text-right font-bold text-white text-sm">
                            {{ number_format($stock->quantity, 2) }}
                            <span class="text-[10px] text-slate-400 font-sans font-normal">{{ $stock->product->unit->name ?? 'Unit' }}</span>
                        </td>
                        <td class="p-3.5 text-right text-slate-400">
                            ৳ {{ number_format($stock->product->purchase_price ?? 0, 2) }}
                        </td>
                        <td class="p-3.5 text-right text-cyan-400 font-bold">
                            ৳ {{ number_format($stock->product->selling_price_retail ?? 0, 2) }}
                        </td>
                        <td class="p-3.5 text-center font-sans">
                            @if($stock->quantity <= 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">স্টক নেই (Out)</span>
                            @elseif($stock->quantity <= ($stock->product->min_stock_alert ?? 5))
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">কম স্টক (Low)</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">পর্যাপ্ত মজুদ ✓</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 font-sans">কোনো স্টকের তথ্য পাওয়া যায়নি।</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stocks->hasPages())
        <div class="p-3 border-t border-slate-800">
            {{ $stocks->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

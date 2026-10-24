@extends('layouts.app')

@section('page_title', 'ওয়্যারহাউস স্টক স্থানান্তর (Stock Transfer)')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>🔄</span> স্টক স্থানান্তর (Warehouse Transfer)
            </h1>
            <p class="text-xs text-slate-400 mt-1">কেন্দ্রীয় গোডাউন হতে শোরুম বা ওয়ার্কশপে মাল স্থানান্তর ও ট্রানজেকশন ট্র্যাকিং</p>
        </div>
        <a href="{{ route('stocks.index') }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-white text-xs border border-slate-700 transition">
            ← স্টক তালিকায় ফিরুন
        </a>
    </div>

    <form action="{{ route('stocks.process-transfer') }}" method="POST" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-5">
        @csrf

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">প্রেরক ওয়্যারহাউস (From Warehouse) <span class="text-rose-500">*</span></label>
                    <select name="from_warehouse_id" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                        <option value="">-- উৎস ওয়্যারহাউস নির্বাচন --</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $w->name }} ({{ $w->location ?? 'ঢাকা' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">প্রাপক ওয়্যারহাউস (To Warehouse) <span class="text-rose-500">*</span></label>
                    <select name="to_warehouse_id" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                        <option value="">-- গন্তব্য ওয়্যারহাউস নির্বাচন --</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ $loop->iteration == 2 ? 'selected' : '' }}>{{ $w->name }} ({{ $w->location ?? 'ঢাকা' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">পণ্য নির্বাচন করুন (Product) <span class="text-rose-500">*</span></label>
                <select name="product_id" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                    <option value="">-- পণ্য নির্বাচন করুন --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->name }} [{{ $p->code }}] (একক: {{ $p->unit->name ?? 'Pcs' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">স্থানান্তরের পরিমাণ (Quantity) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" name="quantity" required placeholder="0.00" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-sm text-cyan-400 font-bold focus:outline-none focus:border-cyan-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">নোট / রেফারেন্স বা চালক</label>
                <textarea name="notes" rows="2" placeholder="ভ্যান চালক রহিম বা চালানের মেমো নং..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500"></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
            <a href="{{ route('stocks.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition">বাতিল</a>
            <button type="submit" class="px-6 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs shadow-lg shadow-blue-500/20 transition">
                🚀 স্টক স্থানান্তর সম্পন্ন করুন
            </button>
        </div>
    </form>
</div>
@endsection

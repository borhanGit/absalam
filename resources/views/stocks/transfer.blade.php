@extends('layouts.app')

@section('page_title', 'ওয়্যারহাউস স্টক স্থানান্তর (Stock Transfer)')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-semibold text-white tracking-tight flex items-center gap-2">
                <svg class="w-4 h-4 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m16 3 4 4-4 4"/><path d="M20 7H4"/><path d="m8 21-4-4 4-4"/><path d="M4 17h16"/>
                </svg>
                <span>স্টক স্থানান্তর (Warehouse Transfer)</span>
            </h1>
            <p class="text-xs text-[#64748d] mt-0.5">কেন্দ্রীয় গোডাউন হতে শোরুম বা ওয়ার্কশপে মাল স্থানান্তর ও ট্রানজেকশন ট্র্যাকিং</p>
        </div>
        <a href="{{ route('stocks.index') }}" class="btn-secondary text-xs py-1.5 px-3">
            ← স্টক তালিকায় ফিরুন
        </a>
    </div>

    <form action="{{ route('stocks.process-transfer') }}" method="POST" class="card-shell p-6 space-y-5">
        @csrf

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">প্রেরক ওয়্যারহাউস (From Warehouse) <span class="text-rose-400">*</span></label>
                    <select name="from_warehouse_id" required class="w-full bg-[#070d18] border border-[#e3e8ee]/15 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-[#533afd]">
                        <option value="">-- উৎস ওয়্যারহাউস নির্বাচন --</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $w->name }} ({{ $w->location ?? 'ঢাকা' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">প্রাপক ওয়্যারহাউস (To Warehouse) <span class="text-rose-400">*</span></label>
                    <select name="to_warehouse_id" required class="w-full bg-[#070d18] border border-[#e3e8ee]/15 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-[#533afd]">
                        <option value="">-- গন্তব্য ওয়্যারহাউস নির্বাচন --</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ $loop->iteration == 2 ? 'selected' : '' }}>{{ $w->name }} ({{ $w->location ?? 'ঢাকা' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">পণ্য নির্বাচন করুন (Product) <span class="text-rose-400">*</span></label>
                <select name="product_id" required class="w-full bg-[#070d18] border border-[#e3e8ee]/15 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-[#533afd]">
                    <option value="">-- পণ্য নির্বাচন করুন --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->name }} [{{ $p->code }}] (একক: {{ $p->unit->name ?? 'Pcs' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">স্থানান্তরের পরিমাণ (Quantity) <span class="text-rose-400">*</span></label>
                <input type="number" step="0.01" name="quantity" required placeholder="0.00" class="w-full bg-[#070d18] border border-[#e3e8ee]/15 rounded-xl px-3 py-2 text-sm text-white font-semibold tnum focus:outline-none focus:border-[#533afd]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">নোট / রেফারেন্স বা চালক</label>
                <textarea name="notes" rows="2" placeholder="ভ্যান চালক রহিম বা চালানের মেমো নং..." class="w-full bg-[#070d18] border border-[#e3e8ee]/15 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-[#533afd]"></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-[#e3e8ee]/10 flex justify-end gap-3">
            <a href="{{ route('stocks.index') }}" class="btn-secondary text-xs py-2 px-4">বাতিল</a>
            <button type="submit" class="btn-primary min-h-[42px] px-6">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <span>স্টক স্থানান্তর সম্পন্ন করুন</span>
            </button>
        </div>
    </form>
</div>
@endsection

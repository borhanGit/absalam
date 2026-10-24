@extends('layouts.app')

@section('page_title', 'নতুন চেক এন্ট্রি (New Cheque Entry)')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>🏦</span> নতুন চেক এন্ট্রি
            </h1>
            <p class="text-xs text-slate-400 mt-1">কাস্টমার থেকে প্রাপ্ত চেক অথবা মিল/সাপ্লায়ারকে দেওয়া চেক লিপিবদ্ধ করুন</p>
        </div>
        <a href="{{ route('cheques.index') }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-white text-xs border border-slate-700 transition">
            ← চেক তালিকায় ফিরুন
        </a>
    </div>

    <form action="{{ route('cheques.store') }}" method="POST" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-5">
        @csrf

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">চেকের ধরন <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-700 bg-slate-800/60 cursor-pointer hover:border-cyan-500 transition">
                        <input type="radio" name="type" value="received_from_customer" checked class="text-cyan-500 focus:ring-0">
                        <div>
                            <span class="text-xs font-bold text-white block">📥 কাস্টমার থেকে প্রাপ্ত চেক</span>
                            <span class="text-[10px] text-slate-400">বিল কালেকশন হিসেবে চেক জমা</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-700 bg-slate-800/60 cursor-pointer hover:border-amber-500 transition">
                        <input type="radio" name="type" value="issued_to_supplier" class="text-amber-500 focus:ring-0">
                        <div>
                            <span class="text-xs font-bold text-white block">📤 মিল/সাপ্লায়ারকে প্রদান</span>
                            <span class="text-[10px] text-slate-400">পারচেজ বিল পরিশোধে চেক প্রদান</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">কাস্টমার (Customer)</label>
                    <select name="customer_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                        <option value="">-- কাস্টমার নির্বাচন করুন --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }}) - বকেয়া: ৳{{ number_format($c->current_balance) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">সাপ্লায়ার / মিল (Supplier)</label>
                    <select name="supplier_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                        <option value="">-- সাপ্লায়ার নির্বাচন করুন --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} (পাওনা: ৳{{ number_format($s->current_balance) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">চেক নং <span class="text-rose-500">*</span></label>
                    <input type="text" name="cheque_no" required placeholder="উদাঃ CHQ-8829102" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">টাকার পরিমাণ (৳) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white font-bold focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">ব্যাংকের নাম <span class="text-rose-500">*</span></label>
                    <input type="text" name="bank_name" required placeholder="উদাঃ Islami Bank / City Bank / DBBL" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">শাখা (Branch Name)</label>
                    <input type="text" name="branch_name" placeholder="উদাঃ Mirpur Branch" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">চেকের তারিখ <span class="text-rose-500">*</span></label>
                <input type="date" name="cheque_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">মন্তব্য / নোট</label>
                <textarea name="notes" rows="2" placeholder="কোন ইনভয়েস বা কাজের বিপরীতে..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500"></textarea>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-800 flex justify-end gap-3">
            <a href="{{ route('cheques.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition">বাতিল</a>
            <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold text-xs shadow-lg shadow-cyan-500/20 transition">
                💾 চেক সংরক্ষণ করুন
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'নতুন মিল / সাপ্লায়ার যুক্ত করুন')
@section('page_title', '🏢 নতুন মিল / ফ্যাক্টরি সাপ্লায়ার এন্ট্রি')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="rounded-2xl border border-slate-800 glass-panel p-6 sm:p-8">
        <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-6">
            @csrf

            <div class="border-b border-slate-800 pb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-white">মিল বা ইম্পোর্টার তথ্য</h2>
                    <p class="text-xs text-slate-400 mt-0.5">অ্যালুমিনিয়াম সেকশন মিল বা গ্লাস ফ্যাক্টরির সঠিক বিবরণ ইনপুট দিন</p>
                </div>
                <a href="{{ route('suppliers.index') }}" class="text-xs text-slate-400 hover:text-white">← ফিরে যান</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="sm:col-span-2">
                    <label class="block text-slate-300 font-semibold mb-1">মিলের / প্রতিষ্ঠানের নাম <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="যেমন: কাই অ্যালুমিনিয়াম ইন্ডাস্ট্রিজ লিঃ" required class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">কোম্পানি / গ্রুপ</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="যেমন: কাই গ্রুপ" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">মোবাইল নম্বর <span class="text-rose-400">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="01700-000000" required class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono focus:border-cyan-500 focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-slate-300 font-semibold mb-1">ঠিকানা / ফ্যাক্টরি লোকেশন</label>
                    <textarea name="address" rows="2" placeholder="টঙ্গী শিল্প এলাকা, গাজীপুর..." class="w-full p-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">প্রারম্ভিক বকেয়া দেনা (Opening Due)</label>
                    <input type="number" name="opening_balance" value="{{ old('opening_balance', '0') }}" step="10" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono focus:border-cyan-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">নোট বা পণ্যের ধরন</label>
                    <input type="text" name="notes" placeholder="যেমন: ১.২মিমি ও ১.৫মিমি সেকশন সাপ্লাই" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                </div>
            </div>

            <div class="border-t border-slate-800 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('suppliers.index') }}" class="px-5 py-2.5 text-xs text-slate-400 hover:text-white">বাতিল</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/20 transition">
                    ✓ মিল / সাপ্লায়ার সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'নতুন কাস্টমার / মিস্ত্রি যুক্ত করুন')
@section('page_title', 'নতুন কাস্টমার / থাই মিস্ত্রি রেজিস্ট্রেশন')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="rounded-xl card-shell p-6 sm:p-8">
        <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">
            @csrf

            <div class="border-b border-[#e3e8ee]/10 pb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-white">কাস্টমার ও কন্ট্রাক্টরের বিস্তারিত তথ্য</h2>
                    <p class="text-xs text-[#64748d] mt-0.5">বাকি লিমিট ও কমিশনের পারসেন্টেজ সেট করুন</p>
                </div>
                <a href="{{ route('customers.index') }}" class="btn-secondary text-xs py-1.5 px-3">← ফিরে যান</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">কাস্টমার / মিস্ত্রির নাম <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="যেমন: কাওসার আহমেদ (থাই মিস্ত্রি)" required class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                    @error('name') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">পার্টি টাইপ <span class="text-rose-400">*</span></label>
                    <select name="customer_type" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        <option value="contractor_mistri" {{ old('customer_type') == 'contractor_mistri' ? 'selected' : '' }}>থাই মিস্ত্রি / ফ্যাব্রিকেটর (Mistri)</option>
                        <option value="builder" {{ old('customer_type') == 'builder' ? 'selected' : '' }}>রিয়েল এস্টেট / ডেভেলপার বিল্ডার</option>
                        <option value="retail" {{ old('customer_type') == 'retail' ? 'selected' : '' }}>খুচরা ক্রেতা (Retail Homeowner)</option>
                        <option value="wholesale" {{ old('customer_type') == 'wholesale' ? 'selected' : '' }}>পাইকারি দোকানদার (Wholesale)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">মোবাইল নম্বর <span class="text-rose-400">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="01700-000000" required class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono focus:border-[#533afd] focus:outline-none">
                    @error('phone') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">বিকল্প মোবাইল নম্বর</label>
                    <input type="text" name="phone_alternate" value="{{ old('phone_alternate') }}" placeholder="01800-000000" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono focus:border-[#533afd] focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-slate-300 font-semibold mb-1">ঠিকানা ও কাজের এলাকা</label>
                    <textarea name="address" rows="2" placeholder="দোকান বা বাড়ির ঠিকানা..." class="w-full p-2.5 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">প্রারম্ভিক বাকি (Opening Due)</label>
                    <input type="number" name="opening_balance" value="{{ old('opening_balance', '0') }}" step="10" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">বাকি লিমিট (Credit Limit)</label>
                    <input type="number" name="credit_limit" value="{{ old('credit_limit', '50000') }}" step="500" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">মিস্ত্রি ডিফল্ট কমিশন (৳/SFT বা %)</label>
                    <input type="number" name="default_commission_rate" value="{{ old('default_commission_rate', '5.00') }}" step="0.5" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">নোট বা রেফারেন্স</label>
                    <input type="text" name="notes" placeholder="রেফারেন্স বা পরিচয়..." class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                </div>
            </div>

            <div class="border-t border-[#e3e8ee]/10 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('customers.index') }}" class="btn-secondary text-xs py-2 px-4">বাতিল</a>
                <button type="submit" class="btn-primary min-h-[42px] px-6">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>কাস্টমার সংরক্ষণ করুন</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

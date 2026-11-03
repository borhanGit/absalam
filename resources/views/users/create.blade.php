@extends('layouts.app')

@section('title', 'নতুন ব্যবহারকারী যোগ করুন')
@section('page_title', 'নতুন ব্যবহারকারী নিবন্ধন (Create User)')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        ব্যবহারকারী তালিকায় ফিরুন
    </a>

    <div class="card-shell p-6 sm:p-8">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white tracking-tight">ব্যবহারকারী তথ্য</h2>
                <p class="text-xs text-slate-400 mt-0.5">দোকানের স্টাফদের জন্য নতুন অ্যাকাউন্ট তৈরি ও দায়িত্ব বণ্টন করুন</p>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">পুরো নাম <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="উদাঃ মোঃ শফিকুল ইসলাম" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                        @error('name')
                            <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">ইমেইল ঠিকানা <span class="text-rose-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="staff@thaiglass.com" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                        @error('email')
                            <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">মোবাইল নম্বর</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="017xxxxxxxx" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">শাখা / ওয়্যারহাউস নিয়োগ</label>
                        <select name="warehouse_id" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                            <option value="">-- সার্বজনীন (সব ওয়্যারহাউস) --</option>
                            @foreach($warehouses as $w)
                                <option value="{{ $w->id }}" {{ old('warehouse_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">ব্যবহারকারীর ভূমিকা / রোল (Role) <span class="text-rose-400">*</span></label>
                    <div class="space-y-2">
                        @foreach($roles as $key => $title)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-700/80 bg-slate-900/60 cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="role" value="{{ $key }}" {{ (old('role', 'cashier') === $key) ? 'checked' : '' }} class="text-indigo-600 focus:ring-0">
                            <span class="text-xs text-slate-200 font-medium">{{ $title }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('role')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">লগইন পাসওয়ার্ড <span class="text-rose-400">*</span></label>
                        <input type="password" name="password" required placeholder="ন্যূনতম ৬ অক্ষর" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                        @error('password')
                            <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">পাসওয়ার্ড নিশ্চিতকরণ <span class="text-rose-400">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="পাসওয়ার্ড পুনরায় লিখুন" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-0">
                        <span class="text-xs font-semibold text-slate-300">অ্যাকাউন্টটি সক্রিয় রাখুন (Active)</span>
                    </label>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('users.index') }}" class="btn-secondary text-xs">বাতিল</a>
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    অ্যাকাউন্ট তৈরি করুন
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

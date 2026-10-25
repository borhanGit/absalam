@extends('layouts.app')

@section('page_title', 'নতুন ব্যবহারকারী যোগ করুন')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>➕</span> নতুন ব্যবহারকারী নিবন্ধন (Create User)
            </h1>
            <p class="text-xs text-slate-400 mt-1">দোকানের স্টাফদের জন্য নতুন অ্যাকাউন্ট তৈরি ও দায়িত্ব বণ্টন করুন</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-white text-xs border border-slate-700 transition">
            ← ব্যবহারকারী তালিকায় ফিরুন
        </a>
    </div>

    <form action="{{ route('users.store') }}" method="POST" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-5">
        @csrf

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">পুরো নাম <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="উদাঃ মোঃ শফিকুল ইসলাম" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-purple-500">
                    @error('name')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">ইমেইল ঠিকানা <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="staff@thaiglass.com" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-purple-500 font-mono">
                    @error('email')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">মোবাইল নম্বর</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="017xxxxxxxx" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-purple-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">শাখা / ওয়্যারহাউস নিয়োগ</label>
                    <select name="warehouse_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-purple-500">
                        <option value="">-- সার্বজনীন (সব ওয়্যারহাউস) --</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ old('warehouse_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">ব্যবহারকারীর ভূমিকা / রোল (Role) <span class="text-rose-500">*</span></label>
                <div class="space-y-2">
                    @foreach($roles as $key => $title)
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-800 bg-slate-950/60 cursor-pointer hover:border-purple-500/50 transition">
                        <input type="radio" name="role" value="{{ $key }}" {{ (old('role', 'cashier') === $key) ? 'checked' : '' }} class="text-purple-500 focus:ring-0">
                        <span class="text-xs text-slate-200">{{ $title }}</span>
                    </label>
                    @endforeach
                </div>
                @error('role')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">লগইন পাসওয়ার্ড <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required placeholder="ন্যূনতম ৬ অক্ষর" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-purple-500 font-mono">
                    @error('password')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">পাসওয়ার্ড নিশ্চিতকরণ <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation" required placeholder="পাসওয়ার্ড পুনরায় লিখুন" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-purple-500 font-mono">
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-purple-600 focus:ring-0">
                    <span class="text-xs font-semibold text-slate-300">অ্যাকাউন্টটি সক্রিয় রাখুন (Active)</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
            <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition">বাতিল</a>
            <button type="submit" class="px-6 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs shadow-lg shadow-purple-500/20 transition">
                💾 অ্যাকাউন্ট তৈরি করুন
            </button>
        </div>
    </form>
</div>
@endsection

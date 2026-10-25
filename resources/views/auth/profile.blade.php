@extends('layouts.app')

@section('page_title', 'প্রোফাইল ও নিরাপত্তা সেটিংস')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>👤</span> আমার প্রোফাইল ও নিরাপত্তা সেটিংস
            </h1>
            <p class="text-xs text-slate-400 mt-1">আপনার নাম, মোবাইল নম্বর এবং অ্যাকাউন্ট পাসওয়ার্ড পরিবর্তন করুন</p>
        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-5">
        @csrf
        @method('PUT')

        <!-- User Role & Basic Info Badge -->
        <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-cyan-600 to-blue-600 flex items-center justify-center text-white text-lg font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-sm font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-400 font-mono">{{ $user->email }}</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold border {{ $user->roleBadgeClass() }}">
                    {{ $user->roleNameBengali() }}
                </span>
                @if($user->warehouse)
                    <span class="text-[10px] text-slate-400 block mt-1">শাখা: {{ $user->warehouse->name }}</span>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">ব্যবহারকারীর পুরো নাম <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                    @error('name')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">মোবাইল নম্বর</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="017xxxxxxxx" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500 font-mono">
                    @error('phone')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800">
                <h3 class="text-xs font-bold text-white mb-3 flex items-center gap-1.5">
                    <span>🔑</span> পাসওয়ার্ড পরিবর্তন করুন (ঐচ্ছিক)
                </h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">বর্তমান পাসওয়ার্ড (পাসওয়ার্ড বদলাতে হলে দিন)</label>
                        <input type="password" name="current_password" placeholder="••••••••" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500 font-mono">
                        @error('current_password')
                            <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">নতুন পাসওয়ার্ড</label>
                            <input type="password" name="new_password" placeholder="ন্যূনতম ৬ অক্ষর" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500 font-mono">
                            @error('new_password')
                                <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
                            <input type="password" name="new_password_confirmation" placeholder="পুনরায় নতুন পাসওয়ার্ড লিখুন" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-cyan-500 font-mono">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
            <button type="submit" class="px-6 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold text-xs shadow-lg shadow-cyan-500/20 transition">
                💾 তথ্য সংরক্ষণ করুন
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', __('My Profile'))
@section('page_title', __('Manage Account Information'))

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="card-shell p-6">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- User Role & Basic Info Badge -->
            <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 text-lg font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-white">{{ $user->name }}</h2>
                        <p class="text-xs text-slate-400 font-mono">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold border {{ $user->roleBadgeClass() }}">
                        {{ $user->roleName() }}
                    </span>
                    @if($user->warehouse)
                        <span class="text-[11px] text-slate-400 block mt-1">{{ __('Branch') }} {{ $user->warehouse->name }}</span>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Full Name') }} <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                        @error('name')
                            <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="017xxxxxxxx" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                        @error('phone')
                            <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800">
                    <h3 class="text-xs font-bold text-white mb-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        {{ __('Change Password') }}
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">{{ __('Current Password') }}</label>
                            <input type="password" name="current_password" placeholder="••••••••" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                            @error('current_password')
                                <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">{{ __('New Password') }}</label>
                                <input type="password" name="new_password" placeholder="Min 6 characters" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                                @error('new_password')
                                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">{{ __('Confirm New Password') }}</label>
                                <input type="password" name="new_password_confirmation" placeholder="Re-type new password" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-800 flex justify-end gap-3">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
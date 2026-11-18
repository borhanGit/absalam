@extends('layouts.app')

@section('title', __('Edit User'))
@section('page_title', __('Edit User') . ': ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        {{ __('Back') }}
    </a>

    <div class="card-shell p-6 sm:p-8">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white tracking-tight">{{ __('Edit User') }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">Update user roles, branch assignment, and account status</p>
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
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Email / Username') }} <span class="text-rose-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                        @error('email')
                            <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="017xxxxxxxx" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Branch Access') }}</label>
                        <select name="warehouse_id" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                            <option value="">-- All Branches --</option>
                            @foreach($warehouses as $w)
                                <option value="{{ $w->id }}" {{ old('warehouse_id', $user->warehouse_id) == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">{{ __('Role') }} <span class="text-rose-400">*</span></label>
                    <div class="space-y-2">
                        @foreach($roles as $key => $title)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-700/80 bg-slate-900/60 cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="role" value="{{ $key }}" {{ (old('role', $user->role) === $key) ? 'checked' : '' }} class="text-indigo-600 focus:ring-0">
                            <span class="text-xs text-slate-200 font-medium">{{ __('roles.' . $key, [], null) ?: $title }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('role')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-3 border-t border-slate-800">
                    <h4 class="text-xs font-bold text-slate-300 mb-2">{{ __('Change Password') }} (optional)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">{{ __('New Password') }}</label>
                            <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                            @error('password')
                                <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">{{ __('Confirm New Password') }}</label>
                            <input type="password" name="password_confirmation" placeholder="Re-type new password" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-0">
                        <span class="text-xs font-semibold text-slate-300">Active</span>
                    </label>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('users.index') }}" class="btn-secondary text-xs">{{ __('Cancel') }}</a>
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
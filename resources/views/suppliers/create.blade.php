@extends('layouts.app')

@section('title', __('Add New Mill / Supplier'))
@section('page_title', __('Create Supplier'))

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <a href="{{ route('suppliers.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        {{ __('Back') }}
    </a>

    <div class="card-shell p-6 sm:p-8">
        <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-6">
            @csrf

            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white tracking-tight">{{ __('Mill / Supplier Name') }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ __('Enter accurate manufacturer or factory profile information') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="sm:col-span-2">
                    <label class="block text-slate-300 font-semibold mb-1">{{ __('Mill / Supplier Name') }} <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. KAI Aluminium Industries Ltd." required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">{{ __('Contact Person') }}</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="e.g. KAI Group" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">{{ __('Phone Number') }} <span class="text-rose-400">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="01700-000000" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs font-mono focus:border-indigo-500 focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-slate-300 font-semibold mb-1">{{ __('Address & Area') }}</label>
                    <textarea name="address" rows="2" placeholder="" class="w-full p-3 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:border-indigo-500 focus:outline-none">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Opening Payable (৳)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-slate-500 font-bold">৳</span>
                        <input type="number" name="opening_balance" value="{{ old('opening_balance', '0') }}" step="1" class="w-full pl-7 pr-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs font-mono tnum focus:border-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">{{ __('Notes') }}</label>
                    <input type="text" name="notes" placeholder="" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs focus:border-indigo-500 focus:outline-none">
                </div>
            </div>

            <div class="border-t border-slate-800 pt-5 flex items-center justify-end gap-3">
                <a href="{{ route('suppliers.index') }}" class="btn-secondary text-xs">{{ __('Cancel') }}</a>
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
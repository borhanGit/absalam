@extends('layouts.app')

@section('title', __('Add Cheque'))
@section('page_title', __('Add Cheque'))

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <a href="{{ route('cheques.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        {{ __('Back') }}
    </a>

    <div class="card-shell p-6 sm:p-8">
        <form action="{{ route('cheques.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white tracking-tight">{{ __('Cheques Management') }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ __('Customer & Supplier Cheque Tracking') }}</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">{{ __('Type') }} <span class="text-rose-400">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-700/80 bg-slate-900/60 cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="type" value="received_from_customer" checked class="text-indigo-600 focus:ring-0">
                            <div>
                                <span class="text-xs font-bold text-white block">Received from Customer</span>
                                <span class="text-[11px] text-slate-400">Customer payment collection</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-700/80 bg-slate-900/60 cursor-pointer hover:border-amber-500 transition">
                            <input type="radio" name="type" value="issued_to_supplier" class="text-amber-500 focus:ring-0">
                            <div>
                                <span class="text-xs font-bold text-white block">Issued to Supplier / Mill</span>
                                <span class="text-[11px] text-slate-400">Purchase bill payment cheque</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Customer') }}</label>
                        <select name="customer_id" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                            <option value="">-- {{ __('Select customer...') }} --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }}) - {{ __('Current Balance') }}: ৳{{ number_format($c->current_balance) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Supplier / Mill') }}</label>
                        <select name="supplier_id" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                            <option value="">-- {{ __('Select Supplier') }} --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} ({{ __('Total Payable') }}: ৳{{ number_format($s->current_balance) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Cheque #') }} <span class="text-rose-400">*</span></label>
                        <input type="text" name="cheque_no" required placeholder="CHQ-8829102" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Amount') }} (৳) <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-500 font-bold">৳</span>
                            <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full pl-8 pr-3.5 py-2.5 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-white font-mono tnum font-bold focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Bank Name') }} <span class="text-rose-400">*</span></label>
                        <input type="text" name="bank_name" required placeholder="Islami Bank / City Bank / DBBL" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Branch') }}</label>
                        <input type="text" name="branch_name" placeholder="Mirpur Branch" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Cheque Date') }} <span class="text-rose-400">*</span></label>
                    <input type="date" name="cheque_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Notes') }}</label>
                    <textarea name="notes" rows="2" placeholder="" class="w-full p-3 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-indigo-500"></textarea>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('cheques.index') }}" class="btn-secondary text-xs">{{ __('Cancel') }}</a>
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
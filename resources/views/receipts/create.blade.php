@extends('layouts.app')

@section('title', __('New Money Receipt'))
@section('page_title', __('Money Receipt Entry'))

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <a href="{{ route('receipts.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        {{ __('Back') }}
    </a>

    <div class="card-shell p-6 sm:p-8">
        <form action="{{ route('receipts.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white tracking-tight">{{ __('Money Receipt Voucher') }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ __('Receipt Register Subtitle') }}</p>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Receipt #') }} <span class="text-rose-400">*</span></label>
                        <input type="text" name="receipt_no" value="{{ old('receipt_no', $receiptNo) }}" required class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs font-mono tnum text-indigo-400 font-bold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Receipt Date') }} <span class="text-rose-400">*</span></label>
                        <input type="date" name="receipt_date" value="{{ old('receipt_date', date('Y-m-d')) }}" required class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Customer / Contractor') }} <span class="text-rose-400">*</span></label>
                    <select name="customer_id" id="customer_select" required class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                        <option value="">-- {{ __('Select customer...') }} --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-balance="{{ $c->current_balance }}" {{ (old('customer_id') == $c->id || ($selectedCustomer && $selectedCustomer->id == $c->id)) ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->phone }}) — {{ __('Total Customer Due') }}: ৳{{ number_format($c->current_balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <div id="customer_due_banner" class="hidden mt-2 p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-xs text-amber-300 flex items-center justify-between">
                        <span class="font-medium">{{ __('Current Balance') }}:</span>
                        <span id="customer_due_val" class="font-bold font-mono tnum text-amber-400">৳ 0.00</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Collected Amount') }} (৳) <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-500 font-bold">৳</span>
                            <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full pl-8 pr-3.5 py-2.5 bg-slate-900 border border-slate-700/80 rounded-xl text-sm text-emerald-400 font-mono tnum font-bold focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Payment Method') }} <span class="text-rose-400">*</span></label>
                        <select name="payment_method" id="payment_method_select" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="cheque">{{ __('Bank Cheque') }}</option>
                            <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                        </select>
                    </div>
                </div>

                <!-- Conditional Cheque Inputs -->
                <div id="cheque_fields" class="hidden p-4 rounded-xl bg-slate-950/80 border border-indigo-500/30 space-y-3">
                    <div class="text-xs font-bold text-indigo-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        {{ __('Cheques Management') }}
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] text-slate-400 font-semibold mb-1">{{ __('Cheque #') }}</label>
                            <input type="text" name="cheque_no" placeholder="CHQ-..." class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-slate-200 font-mono focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-400 font-semibold mb-1">{{ __('Bank & Branch') }}</label>
                            <input type="text" name="bank_name" placeholder="Islami Bank" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-400 font-semibold mb-1">{{ __('Cheque Date') }}</label>
                            <input type="date" name="cheque_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Transaction Ref / Note</label>
                    <input type="text" name="transaction_reference" placeholder="TrxID / Ref" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Notes') }}</label>
                    <textarea name="notes" rows="2" placeholder="" class="w-full p-3 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-indigo-500"></textarea>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('receipts.index') }}" class="btn-secondary text-xs">{{ __('Cancel') }}</a>
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

<script>
    const customerSelect = document.getElementById('customer_select');
    const dueBanner = document.getElementById('customer_due_banner');
    const dueVal = document.getElementById('customer_due_val');
    const methodSelect = document.getElementById('payment_method_select');
    const chequeFields = document.getElementById('cheque_fields');

    function updateDue() {
        const opt = customerSelect.options[customerSelect.selectedIndex];
        if (opt && opt.dataset.balance !== undefined) {
            dueVal.innerText = '৳ ' + parseFloat(opt.dataset.balance).toLocaleString('en-US', {minimumFractionDigits: 2});
            dueBanner.classList.remove('hidden');
        } else {
            dueBanner.classList.add('hidden');
        }
    }

    customerSelect.addEventListener('change', updateDue);
    if (customerSelect.value) {
        updateDue();
    }

    methodSelect.addEventListener('change', function() {
        if (this.value === 'cheque') {
            chequeFields.classList.remove('hidden');
        } else {
            chequeFields.classList.add('hidden');
        }
    });
</script>
@endsection
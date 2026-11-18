@extends('layouts.app')

@section('title', __('Supplier Payments') . ' #' . $payment->payment_no)
@section('page_title', __('View Voucher') . ' #' . $payment->payment_no)

@section('content')
<div class="max-w-2xl mx-auto space-y-6 print:max-w-none print:w-full print:m-0 print:p-0 print:space-y-0">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between no-print print:hidden">
        <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('Back') }}
        </a>
        <button onclick="window.print()" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            {{ __('Print') }}
        </button>
    </div>

    <!-- Printable Card -->
    <div class="bg-white text-slate-900 rounded-2xl p-8 shadow-2xl border border-slate-200 print:shadow-none print:border-none print:m-0 print:p-0 print:w-full print:rounded-none">
        <div class="text-center border-b-2 border-slate-900 pb-4 mb-6">
            <h1 class="text-2xl font-black tracking-tight text-slate-950 uppercase">{{ $company->company_name ?? __('Thai Aluminium & Glass Management System') }}</h1>
            <p class="text-xs text-slate-600 font-medium mt-1">{{ $company->address ?? 'মিরপুর-১০, ঢাকা, বাংলাদেশ' }} | {{ __('Phone') }}: {{ $company->phone ?? '০১৭১২-৩৪৫৬৭৮' }}</p>
            <div class="mt-3 inline-block px-4 py-1 rounded-full bg-slate-900 text-white text-xs font-bold tracking-widest uppercase">
                {{ __('Supplier Payments Register') }}
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs mb-6">
            <div>
                <p class="text-slate-500 font-medium">{{ __('Payment #') }}:</p>
                <p class="font-bold text-sm font-mono tnum text-slate-900">{{ $payment->payment_no }}</p>
            </div>
            <div class="text-right">
                <p class="text-slate-500 font-medium">{{ __('Payment Date') }}:</p>
                <p class="font-bold text-sm text-slate-900">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</p>
            </div>
        </div>

        <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 mb-6 space-y-3 text-xs">
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <span class="text-slate-500 font-medium">{{ __('Supplier / Mill') }}:</span>
                <span class="font-bold text-slate-900 text-sm">{{ $payment->supplier->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <span class="text-slate-500 font-medium">{{ __('Payment Method') }}:</span>
                <span class="font-semibold text-slate-800 uppercase">{{ $payment->payment_method }}</span>
            </div>

            @if($payment->cheque)
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-900 text-xs">
                <span class="font-bold block">{{ __('Cheques Management') }}:</span>
                <span>{{ __('Cheque #') }}: <b class="font-mono">{{ $payment->cheque->cheque_no }}</b> | {{ __('Bank & Branch') }}: {{ $payment->cheque->bank_name }}</span>
            </div>
            @endif

            @if($payment->transaction_reference)
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <span class="text-slate-500 font-medium">Ref / Slip:</span>
                <span class="font-mono text-slate-800">{{ $payment->transaction_reference }}</span>
            </div>
            @endif

            @if($payment->notes)
            <div class="text-slate-600 italic pt-1">
                {{ __('Notes') }}: {{ $payment->notes }}
            </div>
            @endif
        </div>

        <div class="p-5 rounded-xl bg-slate-950 text-white flex items-center justify-between mb-6">
            <div>
                <span class="text-[11px] text-slate-400 block uppercase tracking-wider font-semibold">{{ __('Paid Amount') }}</span>
                <span class="text-xs text-rose-400 font-medium">Deducted from supplier ledger</span>
            </div>
            <div class="text-2xl font-bold font-mono tnum text-rose-400">
                ৳ {{ number_format($payment->amount, 2) }}
            </div>
        </div>

        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs flex justify-between items-center mb-10">
            <span class="text-slate-600 font-medium">{{ __('Current Balance (Payable)') }}:</span>
            <span class="font-bold font-mono tnum text-slate-900">৳ {{ number_format($payment->supplier->current_balance ?? 0, 2) }}</span>
        </div>

        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-dashed border-slate-300 text-center text-xs text-slate-600">
            <div>
                <div class="border-t border-slate-400 w-36 mx-auto mb-1.5"></div>
                <span>Receiver's Signature</span>
            </div>
            <div>
                <div class="border-t border-slate-400 w-36 mx-auto mb-1.5"></div>
                <span>{{ __('Authorized Signature') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
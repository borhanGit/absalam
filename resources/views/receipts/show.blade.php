@extends('layouts.app')

@section('title', __('Money Receipt Voucher') . ' #' . $receipt->receipt_no)
@section('page_title', __('Money Receipt Voucher') . ' #' . $receipt->receipt_no)

@section('content')
<div class="max-w-2xl mx-auto space-y-6 print:max-w-none print:w-full print:m-0 print:p-0 print:space-y-0">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between no-print print:hidden">
        <a href="{{ route('receipts.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('Back') }}
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                {{ __('Print Receipt') }}
            </button>
        </div>
    </div>

    <!-- Printable Receipt Card -->
    <div class="bg-white text-slate-900 rounded-2xl p-8 shadow-2xl border border-slate-200 print:shadow-none print:border-none print:m-0 print:p-0 print:w-full print:rounded-none">
        <!-- Receipt Header -->
        <div class="text-center border-b-2 border-slate-900 pb-4 mb-6">
            <h1 class="text-2xl font-black tracking-tight text-slate-950 uppercase">{{ $company->company_name ?? __('Thai Aluminium & Glass Management System') }}</h1>
            <p class="text-xs text-slate-600 font-medium mt-1">{{ $company->address ?? 'মিরপুর-১০, ঢাকা, বাংলাদেশ' }} | {{ __('Phone') }}: {{ $company->phone ?? '০১৭১২-৩৪৫৬৭৮' }}</p>
            <div class="mt-3 inline-block px-4 py-1 rounded-full bg-slate-900 text-white text-xs font-bold tracking-widest uppercase">
                {{ __('Money Receipt Voucher') }}
            </div>
        </div>

        <!-- Receipt Meta Details -->
        <div class="grid grid-cols-2 gap-4 text-xs mb-6">
            <div>
                <p class="text-slate-500 font-medium">{{ __('Receipt #') }}:</p>
                <p class="font-bold text-sm font-mono tnum text-slate-900">{{ $receipt->receipt_no }}</p>
            </div>
            <div class="text-right">
                <p class="text-slate-500 font-medium">{{ __('Receipt Date') }}:</p>
                <p class="font-bold text-sm text-slate-900">{{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d M, Y') }}</p>
            </div>
        </div>

        <!-- Payer Info & Amount Box -->
        <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 mb-6 space-y-3 text-xs">
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <span class="text-slate-500 font-medium">{{ __('Customer / Contractor') }}:</span>
                <span class="font-bold text-slate-900 text-sm">{{ $receipt->customer->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <span class="text-slate-500 font-medium">{{ __('Phone Number') }}:</span>
                <span class="font-mono text-slate-800">{{ $receipt->customer->phone ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <span class="text-slate-500 font-medium">{{ __('Payment Method') }}:</span>
                <span class="font-semibold text-slate-800 uppercase">{{ $receipt->payment_method }}</span>
            </div>

            @if($receipt->cheque)
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-900 text-xs">
                <span class="font-bold block">{{ __('Cheques Management') }}:</span>
                <span>{{ __('Cheque #') }}: <b class="font-mono">{{ $receipt->cheque->cheque_no }}</b> | {{ __('Bank & Branch') }}: {{ $receipt->cheque->bank_name }}</span>
            </div>
            @endif

            @if($receipt->transaction_reference)
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <span class="text-slate-500 font-medium">Ref / TrxID:</span>
                <span class="font-mono text-slate-800">{{ $receipt->transaction_reference }}</span>
            </div>
            @endif

            @if($receipt->notes)
            <div class="text-slate-600 italic pt-1">
                {{ __('Notes') }}: {{ $receipt->notes }}
            </div>
            @endif
        </div>

        <!-- Big Amount Box -->
        <div class="p-5 rounded-xl bg-slate-950 text-white flex items-center justify-between mb-6">
            <div>
                <span class="text-[11px] text-slate-400 block uppercase tracking-wider font-semibold">{{ __('Collected Amount') }}</span>
                <span class="text-xs text-emerald-400 font-medium">Settled to ledger</span>
            </div>
            <div class="text-2xl font-bold font-mono tnum text-emerald-400">
                ৳ {{ number_format($receipt->amount, 2) }}
            </div>
        </div>

        <!-- Customer Current Due Status -->
        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs flex justify-between items-center mb-10">
            <span class="text-slate-600 font-medium">{{ __('Current Balance') }}:</span>
            <span class="font-bold font-mono tnum text-slate-900">৳ {{ number_format($receipt->customer->current_balance ?? 0, 2) }}</span>
        </div>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-dashed border-slate-300 text-center text-xs text-slate-600">
            <div>
                <div class="border-t border-slate-400 w-36 mx-auto mb-1.5"></div>
                <span>{{ __('Customer Signature') }}</span>
            </div>
            <div>
                <div class="border-t border-slate-400 w-36 mx-auto mb-1.5"></div>
                <span>{{ __('Authorized Signature') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
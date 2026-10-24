@extends('layouts.app')

@section('page_title', 'পেমেন্ট ভাউচার #' . $payment->payment_no)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('payments.index') }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-white text-xs border border-slate-700 transition">
            ← পেমেন্ট তালিকায় ফিরুন
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-amber-500/20 transition flex items-center gap-2">
            <span>🖨️</span> ভাউচার প্রিন্ট করুন
        </button>
    </div>

    <!-- Printable Card -->
    <div class="bg-white text-slate-900 rounded-2xl p-8 shadow-2xl border border-slate-300 print:shadow-none print:border-none print:m-0 print:p-6 print:rounded-none">
        <div class="text-center border-b-2 border-slate-900 pb-4 mb-6">
            <h1 class="text-2xl font-black tracking-tight text-slate-950 uppercase">{{ $company->company_name ?? 'Dhaka Thai & Aluminium Glass House' }}</h1>
            <p class="text-xs text-slate-600 font-medium mt-1">{{ $company->address ?? 'মিরপুর-১০, ঢাকা, বাংলাদেশ' }} | ফোন: {{ $company->phone ?? '০১৭১২-৩৪৫৬৭৮' }}</p>
            <div class="mt-3 inline-block px-4 py-1 rounded bg-slate-900 text-white text-xs font-bold tracking-widest uppercase">
                সাপ্লায়ার পেমেন্ট ভাউচার (SUPPLIER PAYMENT VOUCHER)
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs mb-6">
            <div>
                <p class="text-slate-500">ভাউচার নং:</p>
                <p class="font-bold text-sm font-mono text-slate-900">{{ $payment->payment_no }}</p>
            </div>
            <div class="text-right">
                <p class="text-slate-500">তারিখ:</p>
                <p class="font-bold text-sm text-slate-900">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</p>
            </div>
        </div>

        <div class="border border-slate-300 rounded-xl p-4 bg-slate-50 mb-6 space-y-3 text-xs">
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <span class="text-slate-500">প্রাপক (সাপ্লায়ার / মিল):</span>
                <span class="font-bold text-slate-900 text-sm">{{ $payment->supplier->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <span class="text-slate-500">পরিশোধের মাধ্যম:</span>
                <span class="font-semibold text-slate-800 uppercase">{{ $payment->payment_method }}</span>
            </div>

            @if($payment->cheque)
            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-blue-900 text-xs">
                <span class="font-bold block">ইস্যুকৃত চেকের তথ্য:</span>
                <span>চেক নং: <b>{{ $payment->cheque->cheque_no }}</b> | ব্যাংক: {{ $payment->cheque->bank_name }}</span>
            </div>
            @endif

            @if($payment->transaction_reference)
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <span class="text-slate-500">রেফারেন্স নং:</span>
                <span class="font-mono text-slate-800">{{ $payment->transaction_reference }}</span>
            </div>
            @endif

            @if($payment->notes)
            <div class="text-slate-600 italic pt-1">
                মন্তব্য: {{ $payment->notes }}
            </div>
            @endif
        </div>

        <div class="p-4 rounded-xl bg-slate-950 text-white flex items-center justify-between mb-8">
            <div>
                <span class="text-[11px] text-slate-400 block uppercase tracking-wider font-semibold">মোট পরিশোধিত টাকা (Amount Paid)</span>
                <span class="text-xs text-amber-400">সাপ্লায়ার লেজার হতে কর্তন</span>
            </div>
            <div class="text-2xl font-black font-mono">
                ৳ {{ number_format($payment->amount, 2) }}
            </div>
        </div>

        <div class="p-3 rounded-lg border border-slate-200 text-xs flex justify-between items-center mb-12">
            <span class="text-slate-600">পেমেন্টের পর সাপ্লায়ারের অবশিষ্ট পাওনা:</span>
            <span class="font-bold font-mono text-slate-900">৳ {{ number_format($payment->supplier->current_balance ?? 0, 2) }}</span>
        </div>

        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-dashed border-slate-300 text-center text-xs text-slate-600">
            <div>
                <div class="border-t border-slate-400 w-36 mx-auto mb-1"></div>
                <span>প্রাপকের স্বাক্ষর</span>
            </div>
            <div>
                <div class="border-t border-slate-400 w-36 mx-auto mb-1"></div>
                <span>হিসাবরক্ষক / ম্যানেজারের স্বাক্ষর</span>
            </div>
        </div>
    </div>
</div>
@endsection

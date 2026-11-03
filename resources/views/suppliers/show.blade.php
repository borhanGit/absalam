@extends('layouts.app')

@section('title', 'সাপ্লায়ার লেজার: ' . $supplier->name)
@section('page_title', 'সাপ্লায়ার লেজার ও খতিয়ান: ' . $supplier->name)

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            সাপ্লায়ার তালিকায় ফিরুন
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('payments.create') }}?supplier_id={{ $supplier->id }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                পেমেন্ট প্রদান করুন (Make Payment)
            </a>
        </div>
    </div>

    <!-- Supplier Overview Card -->
    <div class="card-shell p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-lg">
                {{ mb_substr($supplier->name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h2 class="text-xl font-bold text-white tracking-tight">{{ $supplier->name }}</h2>
                    @if($supplier->company_name)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                            {{ $supplier->company_name }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-400 mt-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ $supplier->phone }}
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $supplier->address ?? 'ঠিকানা নেই' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-6 sm:border-l sm:border-slate-800 sm:pl-6">
            <div class="text-right">
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block">বর্তমান দেনা (Payable)</span>
                <span class="text-2xl font-bold font-mono tnum {{ $supplier->current_balance > 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                    ৳{{ number_format($supplier->current_balance, 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Two Tabs: Purchase Invoices & Supplier Payments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Purchases History -->
        <div class="card-shell overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-sm font-bold text-white">মাল ক্রয়ের ইতিহাস (Purchases)</h3>
                </div>
                <span class="text-xs font-mono text-slate-400">{{ count($supplier->purchases) }} টি চালান</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                        <tr>
                            <th class="p-3">চালান নং</th>
                            <th class="p-3">তারিখ</th>
                            <th class="p-3 text-right">মোট বিল</th>
                            <th class="p-3 text-right">নগদ পরিশোধ</th>
                            <th class="p-3 text-right">বাকি</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($supplier->purchases as $p)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="p-3 font-mono text-indigo-400 font-semibold">{{ $p->purchase_no }}</td>
                                <td class="p-3 text-slate-400">{{ $p->purchase_date }}</td>
                                <td class="p-3 text-right font-mono tnum font-semibold text-white">৳{{ number_format($p->grand_total, 2) }}</td>
                                <td class="p-3 text-right font-mono tnum text-emerald-400">৳{{ number_format($p->paid_amount, 2) }}</td>
                                <td class="p-3 text-right font-mono tnum text-rose-400">৳{{ number_format($p->due_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500">এখনও কোনো ক্রয়ের রেকর্ড নেই।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card-shell overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-sm font-bold text-white">পেমেন্ট প্রদানের ইতিহাস</h3>
                </div>
                <span class="text-xs font-mono text-slate-400">{{ count($supplier->payments) }} টি পেমেন্ট</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                        <tr>
                            <th class="p-3">ভাউচার নং</th>
                            <th class="p-3">তারিখ</th>
                            <th class="p-3">মাধ্যম</th>
                            <th class="p-3 text-right">পরিশোধিত টাকা (৳)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($supplier->payments as $pay)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="p-3 font-mono text-indigo-400 font-semibold">{{ $pay->payment_no }}</td>
                                <td class="p-3 text-slate-400">{{ $pay->payment_date }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 font-mono text-[11px] border border-slate-700">
                                        {{ strtoupper($pay->payment_method) }}
                                    </span>
                                </td>
                                <td class="p-3 text-right font-bold font-mono tnum text-emerald-400">৳{{ number_format($pay->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-500">এখনও কোনো পেমেন্ট প্রদান করা হয়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

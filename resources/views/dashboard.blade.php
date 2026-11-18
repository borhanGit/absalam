@extends('layouts.app')

@section('title', __('dashboard.title'))
@section('page_title', __('dashboard.page_title'))

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Cards Grid (DESIGN.md Depth & Tabular Figures) -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <!-- Card 1: Today Sales -->
        <div class="card-shell p-4 hover:border-[#533afd]/40 transition-colors">
            <div class="flex items-center justify-between text-[#64748d] text-xs">
                <span class="font-medium">{{ __('dashboard.today_sales') }}</span>
                <span class="p-2 rounded-lg bg-[#533afd]/10 text-[#533afd]">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-bold text-white tnum tracking-tight">৳{{ number_format($todaySales, 2) }}</span>
            </div>
            <p class="text-[11px] text-[#64748d] mt-1.5">{{ __('dashboard.sales_sub') }}</p>
        </div>

        <!-- Card 2: Today Collection -->
        <div class="card-shell p-4 hover:border-emerald-500/40 transition-colors">
            <div class="flex items-center justify-between text-[#64748d] text-xs">
                <span class="font-medium">{{ __('dashboard.today_collections') }}</span>
                <span class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-bold text-white tnum tracking-tight">৳{{ number_format($todayCollections, 2) }}</span>
            </div>
            <p class="text-[11px] text-emerald-400/80 mt-1.5">{{ __('dashboard.collections_sub') }}</p>
        </div>

        <!-- Card 3: Customer Receivables -->
        <div class="card-shell p-4 hover:border-amber-500/40 transition-colors">
            <div class="flex items-center justify-between text-[#64748d] text-xs">
                <span class="font-medium">{{ __('dashboard.customer_due') }}</span>
                <span class="p-2 rounded-lg bg-amber-500/10 text-amber-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-bold text-white tnum tracking-tight">৳{{ number_format($totalCustomerDue, 2) }}</span>
            </div>
            <p class="text-[11px] text-amber-400/80 mt-1.5">{{ __('dashboard.customer_due_sub') }}</p>
        </div>

        <!-- Card 4: Supplier Payables -->
        <div class="card-shell p-4 hover:border-rose-500/40 transition-colors">
            <div class="flex items-center justify-between text-[#64748d] text-xs">
                <span class="font-medium">{{ __('dashboard.supplier_payable') }}</span>
                <span class="p-2 rounded-lg bg-rose-500/10 text-rose-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-bold text-white tnum tracking-tight">৳{{ number_format($totalSupplierPayable, 2) }}</span>
            </div>
            <p class="text-[11px] text-rose-400/80 mt-1.5">{{ __('dashboard.supplier_payable_sub') }}</p>
        </div>

        <!-- Card 5: Cheques in Hand -->
        <div class="card-shell p-4 hover:border-[#533afd]/40 transition-colors col-span-2 md:col-span-1">
            <div class="flex items-center justify-between text-[#64748d] text-xs">
                <span class="font-medium">{{ __('dashboard.cheques_in_hand') }}</span>
                <span class="p-2 rounded-lg bg-[#533afd]/10 text-[#533afd]">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-bold text-white tnum tracking-tight">৳{{ number_format($chequesInHand, 2) }}</span>
            </div>
            <p class="text-[11px] text-[#64748d] mt-1.5"><strong class="text-white tnum">{{ $chequesInHandCount }}</strong> {{ __('dashboard.cheques_sub') }}</p>
        </div>
    </div>

    <!-- Quick Action Launchpad (Clean Anti-Slop Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
        <a href="{{ route('pos.create') }}" class="p-4 rounded-xl bg-[#533afd] hover:bg-[#4434d4] text-white flex items-center justify-between shadow-md shadow-[#533afd]/25 transition group">
            <div>
                <span class="text-[11px] uppercase tracking-wider block text-white/75 font-semibold">{{ __('dashboard.quick_pos_badge') }}</span>
                <span class="text-sm font-semibold text-white mt-0.5 block">{{ __('dashboard.quick_pos') }}</span>
            </div>
            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('quotations.create') }}" class="p-4 rounded-xl card-shell hover:border-[#533afd]/50 text-white flex items-center justify-between transition group">
            <div>
                <span class="text-[11px] text-[#64748d] uppercase tracking-wider block font-semibold">{{ __('dashboard.quick_quotation_badge') }}</span>
                <span class="text-sm font-semibold text-white mt-0.5 block">{{ __('dashboard.quick_quotation') }}</span>
            </div>
            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-300 group-hover:text-white group-hover:bg-[#533afd]/20 transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('purchases.create') }}" class="p-4 rounded-xl card-shell hover:border-emerald-500/50 text-white flex items-center justify-between transition group">
            <div>
                <span class="text-[11px] text-[#64748d] uppercase tracking-wider block font-semibold">{{ __('dashboard.quick_purchase_badge') }}</span>
                <span class="text-sm font-semibold text-white mt-0.5 block">{{ __('dashboard.quick_purchase') }}</span>
            </div>
            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-300 group-hover:text-emerald-400 group-hover:bg-emerald-500/10 transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('receipts.create') }}" class="p-4 rounded-xl card-shell hover:border-amber-500/50 text-white flex items-center justify-between transition group">
            <div>
                <span class="text-[11px] text-[#64748d] uppercase tracking-wider block font-semibold">{{ __('dashboard.quick_receipt_badge') }}</span>
                <span class="text-sm font-semibold text-white mt-0.5 block">{{ __('dashboard.quick_receipt') }}</span>
            </div>
            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-300 group-hover:text-amber-400 group-hover:bg-amber-500/10 transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 17.5v-11"/>
                </svg>
            </div>
        </a>
    </div>

    <!-- Two Columns: Recent Invoices & Available Off-Cuts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1 & 2: Recent Sales Invoices -->
        <div class="lg:col-span-2 rounded-xl card-shell p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-[#533afd]/10 flex items-center justify-center text-[#533afd]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-white">{{ __('dashboard.recent_sales_title') }}</h2>
                </div>
                <a href="{{ route('sales.index') }}" class="text-xs text-[#533afd] hover:text-[#b9b9f9] font-medium transition flex items-center gap-1">
                    <span>{{ __('dashboard.view_all_sales') }}</span>
                    <span>→</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#070d18]/60 text-[#64748d] border-b border-[#e3e8ee]/5">
                        <tr>
                            <th class="p-3 font-semibold">{{ __('dashboard.invoice_no') }}</th>
                            <th class="p-3 font-semibold">{{ __('dashboard.customer_name') }}</th>
                            <th class="p-3 font-semibold">{{ __('app.date') }}</th>
                            <th class="p-3 font-semibold text-right">{{ __('app.total') }}</th>
                            <th class="p-3 font-semibold text-right">{{ __('app.paid') }}</th>
                            <th class="p-3 font-semibold text-right">{{ __('app.due') }}</th>
                            <th class="p-3 font-semibold text-center">{{ __('app.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e3e8ee]/5 text-slate-300">
                        @forelse($recentSales as $sale)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="p-3 font-mono font-medium">
                                    <a href="{{ route('sales.show', $sale->id) }}" class="text-[#533afd] hover:underline font-semibold">{{ $sale->invoice_no }}</a>
                                </td>
                                <td class="p-3 font-medium text-white">{{ $sale->customer->name ?? '-' }}</td>
                                <td class="p-3 text-[#64748d] tnum">{{ $sale->sale_date }}</td>
                                <td class="p-3 text-right font-medium text-white tnum">৳{{ number_format($sale->grand_total, 2) }}</td>
                                <td class="p-3 text-right font-medium text-emerald-400 tnum">৳{{ number_format($sale->paid_amount, 2) }}</td>
                                <td class="p-3 text-right font-medium text-amber-400 tnum">৳{{ number_format($sale->due_amount, 2) }}</td>
                                <td class="p-3 text-center">
                                    @if($sale->payment_status === 'paid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">{{ __('app.paid') }}</span>
                                    @elseif($sale->payment_status === 'partial')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">{{ __('app.partial') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">{{ __('app.due') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-[#64748d]">
                                    {{ __('dashboard.no_recent_sales') }} <a href="{{ route('pos.create') }}" class="text-[#533afd] font-semibold hover:underline">{{ __('app.create') }}</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Col 3: Available Cut Leftovers (Off-cuts) -->
        <div class="rounded-xl card-shell p-5 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-[#533afd]/10 flex items-center justify-center text-[#533afd]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" x2="8.12" y1="4" y2="15.88"/><line x1="14.47" x2="20" y1="14.48" y2="20"/><line x1="8.12" x2="12" y1="8.12" y2="12"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">{{ __('dashboard.offcuts_title') }}</h2>
                    </div>
                </div>
                <a href="{{ route('offcuts.index') }}" class="text-xs text-[#533afd] hover:text-[#b9b9f9] font-medium transition">{{ __('dashboard.view_all_offcuts') }} →</a>
            </div>

            <div class="space-y-2.5">
                @forelse($recentOffCuts as $cut)
                    <div class="p-3 rounded-lg bg-[#070d18]/60 border border-[#e3e8ee]/5 flex items-center justify-between text-xs hover:border-[#533afd]/30 transition">
                        <div>
                            <span class="font-semibold text-white block">{{ $cut->product->name ?? '-' }}</span>
                            <span class="text-[#64748d] text-[11px] tnum">
                                {{ __('pos.size_measurement') }}: <strong class="text-slate-200">{{ $cut->length_feet }}'</strong> 
                                @if($cut->width_inch) × {{ $cut->width_inch }}" @endif
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 rounded-full bg-[#533afd]/15 text-white font-semibold font-mono text-[11px] border border-[#533afd]/20 tnum">{{ $cut->quantity }} pcs</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-[#64748d] text-xs">
                        {{ __('dashboard.no_offcuts') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

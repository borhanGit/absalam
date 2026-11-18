@extends('layouts.app')

@section('title', __('Sales Invoice List'))
@section('page_title', __('Sales Invoices'))

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl card-shell">
            <span class="text-xs text-[#64748d] font-medium">{{ __('Total Sales') }}</span>
            <div class="text-xl font-bold text-white tnum mt-1">৳{{ number_format($totalSalesAmount, 2) }}</div>
        </div>
        <div class="p-4 rounded-xl card-shell">
            <span class="text-xs text-[#64748d] font-medium">{{ __('Total Cash Collection') }}</span>
            <div class="text-xl font-bold text-emerald-400 tnum mt-1">৳{{ number_format($totalPaidAmount, 2) }}</div>
        </div>
        <div class="p-4 rounded-xl card-shell">
            <span class="text-xs text-[#64748d] font-medium">{{ __('Total Due Amount') }}</span>
            <div class="text-xl font-bold text-amber-400 tnum mt-1">৳{{ number_format($totalDueAmount, 2) }}</div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <form method="GET" action="{{ route('sales.index') }}" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search invoice or customer...') }}" class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-[#070d18] border border-slate-300 dark:border-[#e3e8ee]/15 text-slate-900 dark:text-white text-xs placeholder-slate-400 dark:placeholder-[#64748d] focus:outline-none focus:border-[#533afd] w-full sm:w-64">
            
            <select name="payment_status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-[#070d18] border border-slate-300 dark:border-[#e3e8ee]/15 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#533afd] flex-1 sm:flex-initial">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>{{ __('Partial') }}</option>
                <option value="due" {{ request('payment_status') === 'due' ? 'selected' : '' }}>{{ __('Due') }}</option>
            </select>

            <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-[#070d18] border border-slate-300 dark:border-[#e3e8ee]/15 text-slate-900 dark:text-white text-xs tnum focus:outline-none focus:border-[#533afd]">

            <button type="submit" class="btn-secondary text-xs py-2 px-4">
                {{ __('Filter') }}
            </button>
            @if(request()->hasAny(['search', 'payment_status', 'date']))
                <a href="{{ route('sales.index') }}" class="px-3 py-2 text-xs text-rose-400 hover:underline">{{ __('Reset') }}</a>
            @endif
        </form>

        <a href="{{ route('pos.create') }}" class="btn-primary w-full sm:w-auto justify-center">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
            </svg>
            <span>{{ __('New POS Sales') }}</span>
        </a>
    </div>

    <!-- Sales Table -->
    <div class="rounded-xl card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#070d18]/80 text-[#64748d] border-b border-[#e3e8ee]/5">
                    <tr>
                        <th class="p-3 font-semibold">{{ __('Invoice #') }}</th>
                        <th class="p-3 font-semibold">{{ __('Date') }}</th>
                        <th class="p-3 font-semibold">{{ __('Customer / Contractor') }}</th>
                        <th class="p-3 font-semibold">{{ __('Branch / Warehouse') }}</th>
                        <th class="p-3 font-semibold text-right">{{ __('Item Subtotal') }}</th>
                        <th class="p-3 font-semibold text-right">{{ __('Van / Labor') }}</th>
                        <th class="p-3 font-semibold text-right">{{ __('Grand Total') }}</th>
                        <th class="p-3 font-semibold text-right">{{ __('Cash Paid') }}</th>
                        <th class="p-3 font-semibold text-right">{{ __('Due') }}</th>
                        <th class="p-3 font-semibold text-center">{{ __('Status') }}</th>
                        <th class="p-3 font-semibold text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e8ee]/5 text-slate-300">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="p-3 font-mono font-semibold">
                                <a href="{{ route('sales.show', $sale->id) }}" class="text-[#533afd] hover:underline">{{ $sale->invoice_no }}</a>
                            </td>
                            <td class="p-3 text-[#64748d] tnum">{{ $sale->sale_date }}</td>
                            <td class="p-3">
                                <span class="font-medium text-white block">{{ $sale->customer->name ?? '-' }}</span>
                                <span class="text-[11px] text-[#64748d]">{{ $sale->customer->phone ?? '' }}</span>
                            </td>
                            <td class="p-3 text-[#64748d]">{{ $sale->warehouse->name ?? '-' }}</td>
                            <td class="p-3 text-right tnum">৳{{ number_format($sale->subtotal, 2) }}</td>
                            <td class="p-3 text-right tnum text-[#64748d]">৳{{ number_format($sale->carrying_cost + $sale->labor_fitting_cost, 2) }}</td>
                            <td class="p-3 text-right tnum font-semibold text-white">৳{{ number_format($sale->grand_total, 2) }}</td>
                            <td class="p-3 text-right tnum text-emerald-400">৳{{ number_format($sale->paid_amount, 2) }}</td>
                            <td class="p-3 text-right tnum font-semibold text-amber-400">৳{{ number_format($sale->due_amount, 2) }}</td>
                            <td class="p-3 text-center">
                                @if($sale->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">{{ __('Paid') }}</span>
                                @elseif($sale->payment_status === 'partial')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">{{ __('Partial') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">{{ __('Due') }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('sales.show', $sale->id) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/5 hover:bg-white/10 text-slate-200 border border-[#e3e8ee]/10 transition font-medium text-[11px]">
                                    <span>{{ __('View Invoice') }}</span>
                                    <span>↗</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="p-8 text-center text-[#64748d]">
                                {{ __('No sales invoices found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="p-4 border-t border-[#e3e8ee]/10 bg-[#070d18]/60">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
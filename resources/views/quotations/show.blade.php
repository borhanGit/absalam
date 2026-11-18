@extends('layouts.app')

@section('title', __('Quotation #') . ' ' . $quotation->quotation_no)
@section('page_title', __('Quotation #') . ' ' . $quotation->quotation_no)

@section('content')
<div class="max-w-4xl mx-auto space-y-6 print:max-w-none print:w-full print:m-0 print:p-0 print:space-y-0">

    <!-- Action Toolbar (Hidden in Print) -->
    <div class="flex items-center justify-between gap-4 print:hidden no-print">
        <a href="{{ route('quotations.index') }}" class="btn-secondary text-xs py-1.5 px-3">
            ← {{ __('Back') }}
        </a>
        <div class="flex items-center gap-2.5">
            @if($quotation->status !== 'converted_to_sale')
                <form method="POST" action="{{ route('quotations.convert', $quotation->id) }}" onsubmit="return confirm('{{ __('Are you sure you want to convert this quotation to a sale invoice?') }}');">
                    @csrf
                    <button type="submit" class="btn-primary text-xs">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>{{ __('Converted to Sale') }}</span>
                    </button>
                </form>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 text-emerald-400 font-semibold text-xs border border-emerald-500/20">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>{{ __('Converted to Sale') }}</span>
                </span>
            @endif

            <button onclick="window.print()" class="btn-secondary text-xs">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                </svg>
                <span>{{ __('Print') }}</span>
            </button>
        </div>
    </div>

    <!-- Official Proposal Sheet (Clean White Canvas for Physical Print) -->
    <div class="rounded-2xl border border-slate-200 bg-white text-slate-900 p-8 sm:p-10 shadow-xl print:shadow-none print:border-none print:p-0 print:m-0 print:w-full print:rounded-none">
        <!-- Header -->
        <div class="border-b-2 border-slate-900 pb-5 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-950 uppercase">
                    {{ $company->company_name ?? __('Thai Aluminium & Glass Management System') }}
                </h1>
                <p class="text-xs text-slate-600 mt-1">
                    {{ $company->address ?? 'মিরপুর-১০, ঢাকা-১২১৬, বাংলাদেশ' }}
                </p>
                <p class="text-xs text-slate-700 font-semibold mt-0.5">
                    {{ __('Phone') }}: {{ $company->phone ?? '01712-345678' }}
                </p>
            </div>
            <div class="sm:text-right">
                <span class="inline-block px-3 py-1 rounded bg-slate-900 text-white text-xs font-bold uppercase tracking-wider mb-2">
                    {{ __('Quotation #') }}
                </span>
                <div class="text-xs font-mono font-bold text-slate-800">
                    {{ __('Quotation #') }}: <span class="text-sm font-black">{{ $quotation->quotation_no }}</span>
                </div>
                <div class="text-xs text-slate-600 tnum">{{ __('Date') }}: {{ $quotation->quotation_date }}</div>
            </div>
        </div>

        <!-- Project & Client Metadata -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 grid grid-cols-2 gap-4 text-xs mb-6">
            <div>
                <span class="text-slate-500 font-bold uppercase text-[10px] block">{{ __('Customer / Client') }}:</span>
                <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ $quotation->customer->name ?? '-' }}</span>
                <span class="text-slate-600 block">{{ $quotation->customer->phone ?? '' }}</span>
                <span class="text-slate-600 block">{{ $quotation->customer->address ?? '' }}</span>
            </div>
            <div class="text-right">
                <span class="text-slate-500 font-bold uppercase text-[10px] block">{{ __('Project Name') }}:</span>
                <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ $quotation->project_name }}</span>
                <span class="text-slate-600 block">{{ __('Total Units') }}: {{ $quotation->total_units }}</span>
                <span class="text-slate-700 font-bold font-mono block tnum">{{ __('Total Sqft') }}: {{ number_format($quotation->total_sqft, 2) }} SFT</span>
            </div>
        </div>

        <!-- Quotation Specification Table -->
        <div class="border border-slate-300 rounded-lg overflow-hidden mb-6">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 border-b border-slate-300 font-bold text-slate-800">
                    <tr>
                        <th class="p-2.5 text-center w-8">#</th>
                        <th class="p-2.5">{{ __('Structure Type & Location') }}</th>
                        <th class="p-2.5 text-center">{{ __('Width (inch)') }} × {{ __('Height (inch)') }}</th>
                        <th class="p-2.5 text-center">{{ __('Quantity') }}</th>
                        <th class="p-2.5 text-center">{{ __('Total SFT') }}</th>
                        <th class="p-2.5">{{ __('Section & Glass') }}</th>
                        <th class="p-2.5 text-right">{{ __('Rate/SFT (৳)') }}</th>
                        <th class="p-2.5 text-right">{{ __('Total (৳)') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @foreach($quotation->items as $index => $item)
                        <tr>
                            <td class="p-2.5 text-center text-slate-500 font-mono">{{ $index + 1 }}</td>
                            <td class="p-2.5">
                                <span class="font-bold block">{{ ucwords(str_replace('_', ' ', $item->structure_type)) }}</span>
                                @if($item->location_tag)
                                    <span class="text-[11px] text-slate-600">{{ $item->location_tag }}</span>
                                @endif
                            </td>
                            <td class="p-2.5 text-center font-mono text-slate-700 tnum">
                                {{ $item->width_inch }}" × {{ $item->height_inch }}"
                            </td>
                            <td class="p-2.5 text-center font-bold font-mono tnum">{{ $item->quantity }}</td>
                            <td class="p-2.5 text-center font-bold font-mono text-slate-900 tnum">{{ number_format($item->total_sqft, 2) }}</td>
                            <td class="p-2.5 text-slate-700">
                                <div>{{ $item->profileProduct->name ?? 'Standard Profile' }}</div>
                                <div>{{ $item->glassProduct->name ?? 'Glass' }}</div>
                            </td>
                            <td class="p-2.5 text-right font-mono tnum">৳{{ number_format($item->rate_per_sqft, 2) }}</td>
                            <td class="p-2.5 text-right font-bold font-mono tnum">৳{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Terms & Totals Summary -->
        <div class="flex flex-col sm:flex-row justify-between gap-6 text-xs mb-8">
            <div class="max-w-xs text-slate-600 space-y-2">
                <span class="font-bold text-slate-900 block">{{ __('Terms & Conditions') }}</span>
                <p class="whitespace-pre-line">{{ $quotation->terms_and_conditions ?? '১. ডেলিভারি সময়কাল ৭-১০ কার্যদিবস।' }}</p>
            </div>

            <div class="w-full sm:w-64 space-y-1.5 font-mono text-slate-800 border-t sm:border-t-0 pt-3 sm:pt-0">
                <div class="flex justify-between">
                    <span class="text-slate-600">{{ __('Total Sqft') }}:</span>
                    <span class="tnum">{{ number_format($quotation->total_sqft, 2) }} SFT</span>
                </div>
                @if($quotation->fitting_installation_cost > 0)
                    <div class="flex justify-between">
                        <span class="text-slate-600">{{ __('Installation / Fitting (+):') }}</span>
                        <span class="tnum">৳{{ number_format($quotation->fitting_installation_cost, 2) }}</span>
                    </div>
                @endif
                @if($quotation->transport_cost > 0)
                    <div class="flex justify-between">
                        <span class="text-slate-600">{{ __('Van / Transport Cost (+)') }}</span>
                        <span class="tnum">৳{{ number_format($quotation->transport_cost, 2) }}</span>
                    </div>
                @endif
                @if($quotation->discount_amount > 0)
                    <div class="flex justify-between text-rose-600">
                        <span>{{ __('Discount (-)') }}</span>
                        <span class="tnum">৳{{ number_format($quotation->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-black text-base text-slate-950 border-t-2 border-slate-900 pt-2">
                    <span>{{ __('Total Budget') }}:</span>
                    <span class="tnum">৳{{ number_format($quotation->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Signature -->
        <div class="pt-12 flex items-center justify-between text-xs text-slate-600">
            <div class="text-center">
                <div class="w-36 border-t border-slate-400 mb-1"></div>
                <span>{{ __('Customer Signature') }}</span>
            </div>
            <div class="text-center">
                <div class="w-36 border-t border-slate-400 mb-1"></div>
                <span>{{ __('Authorized Signature') }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
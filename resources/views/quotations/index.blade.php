@extends('layouts.app')

@section('title', __('Window & Door Quotations'))
@section('page_title', __('Thai Project Quotations'))

@section('content')
<div class="space-y-6">

    <!-- Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <form method="GET" action="{{ route('quotations.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search quotation or project...') }}" class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-[#070d18] border border-slate-300 dark:border-[#e3e8ee]/15 text-slate-900 dark:text-white text-xs placeholder-slate-400 dark:placeholder-[#64748d] focus:outline-none focus:border-[#533afd] w-full sm:w-72">
            <button type="submit" class="btn-secondary text-xs py-2 px-4 shrink-0">
                {{ __('Search') }}
            </button>
            @if(request('search'))
                <a href="{{ route('quotations.index') }}" class="px-3 py-2 text-xs text-rose-400 hover:underline shrink-0">{{ __('Reset') }}</a>
            @endif
        </form>

        <a href="{{ route('quotations.create') }}" class="btn-primary w-full sm:w-auto justify-center">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>
            </svg>
            <span>{{ __('New Project Quotation') }}</span>
        </a>
    </div>

    <!-- Quotations Table -->
    <div class="rounded-xl card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#070d18]/80 text-[#64748d] border-b border-[#e3e8ee]/5">
                    <tr>
                        <th class="p-3 font-semibold">{{ __('Quotation #') }}</th>
                        <th class="p-3 font-semibold">{{ __('Date') }}</th>
                        <th class="p-3 font-semibold">{{ __('Customer / Client') }}</th>
                        <th class="p-3 font-semibold">{{ __('Project Name') }}</th>
                        <th class="p-3 text-center font-semibold">{{ __('Total Units') }}</th>
                        <th class="p-3 text-center font-semibold">{{ __('Total Sqft') }}</th>
                        <th class="p-3 text-right font-semibold">{{ __('Total Budget') }}</th>
                        <th class="p-3 text-center font-semibold">{{ __('Status') }}</th>
                        <th class="p-3 text-center font-semibold">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e8ee]/5 text-slate-300">
                    @forelse($quotations as $q)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="p-3 font-mono font-semibold">
                                <a href="{{ route('quotations.show', $q->id) }}" class="text-[#533afd] hover:underline">{{ $q->quotation_no }}</a>
                            </td>
                            <td class="p-3 text-[#64748d] tnum">{{ $q->quotation_date }}</td>
                            <td class="p-3">
                                <span class="font-medium text-white block">{{ $q->customer->name ?? '-' }}</span>
                                <span class="text-[11px] text-[#64748d]">{{ $q->customer->phone ?? '' }}</span>
                            </td>
                            <td class="p-3 text-slate-300 font-medium">{{ $q->project_name }}</td>
                            <td class="p-3 text-center font-mono tnum">{{ $q->total_units }}</td>
                            <td class="p-3 text-center font-mono font-semibold text-[#533afd] tnum">{{ number_format($q->total_sqft, 2) }} SFT</td>
                            <td class="p-3 text-right font-medium text-emerald-400 tnum">৳{{ number_format($q->grand_total, 2) }}</td>
                            <td class="p-3 text-center">
                                @if($q->status === 'converted_to_sale')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">{{ __('Converted to Sale') }}</span>
                                @elseif($q->status === 'approved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#533afd]/15 text-[#533afd] border border-[#533afd]/30">{{ __('Approved') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/5 text-[#64748d] border border-white/10">{{ __('Draft') }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('quotations.show', $q->id) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/5 hover:bg-white/10 text-slate-200 border border-[#e3e8ee]/10 transition font-medium text-[11px]">
                                    <span>{{ __('Details') }}</span>
                                    <span>↗</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-[#64748d]">
                                {{ __('No quotations found.') }} <a href="{{ route('quotations.create') }}" class="text-[#533afd] font-semibold hover:underline">{{ __('Create new quotation') }}</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotations->hasPages())
            <div class="p-4 border-t border-[#e3e8ee]/10 bg-[#070d18]/60">
                {{ $quotations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
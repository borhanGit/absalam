@extends('layouts.app')

@section('title', __('Off-Cuts & Scrap'))
@section('page_title', __('Off-Cuts & Scrap Management'))

@section('content')
<div class="space-y-6">

    <!-- Scrap / Bhangari Overview Banner -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-xl card-shell border-[#533afd]/25">
            <span class="text-xs text-[#b9b9f9] font-semibold block">{{ __('Scrap Weight (Kg)') }}</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-bold text-white tnum">{{ number_format($scrapStock, 2) }}</span>
                <span class="text-xs text-[#64748d]">Kg</span>
            </div>
            <p class="text-[11px] text-[#64748d] mt-1 tnum">৳{{ number_format($scrapProduct->selling_price_retail ?? 270, 0) }}/Kg</p>
        </div>

        <div class="p-5 rounded-xl card-shell">
            <span class="text-xs text-[#64748d] font-semibold block">{{ __('Total Usable Off-Cuts') }}</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-bold text-[#533afd] tnum">{{ $offCuts->total() }}</span>
                <span class="text-xs text-[#64748d]">Pcs</span>
            </div>
            <p class="text-[11px] text-[#64748d] mt-1">{{ __('Available Usable Off-Cuts & Profiles') }}</p>
        </div>

        <div class="p-5 rounded-xl card-shell flex flex-col justify-between">
            <div>
                <span class="text-xs text-[#64748d] font-semibold block">{{ __('Use in POS') }}</span>
                <p class="text-xs text-slate-300 mt-1">{{ __('Usable Cut-Pieces of Aluminium & Glass') }}</p>
            </div>
            <a href="{{ route('pos.create') }}" class="text-xs text-[#533afd] hover:text-[#b9b9f9] font-medium transition mt-2">
                {{ __('New POS Billing') }} →
            </a>
        </div>
    </div>

    <!-- Manual Off-cut Entry Form & Table Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left: Form to record workshop leftover (4 cols) -->
        <div class="lg:col-span-4 rounded-xl card-shell p-5 space-y-4">
            <div class="flex items-center gap-2 border-b border-[#e3e8ee]/10 pb-3">
                <svg class="w-4 h-4 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" x2="8.12" y1="4" y2="15.88"/><line x1="14.47" x2="20" y1="14.48" y2="20"/><line x1="8.12" x2="12" y1="8.12" y2="12"/>
                </svg>
                <h2 class="text-sm font-semibold text-white">{{ __('Add New Off-Cut') }}</h2>
            </div>

            <form method="POST" action="{{ route('offcuts.store') }}" class="space-y-3 text-xs">
                @csrf

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">{{ __('Product') }} <span class="text-rose-400">*</span></label>
                    <select name="product_id" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">{{ __('Warehouse') }} <span class="text-rose-400">*</span></label>
                    <select name="warehouse_id" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">{{ __('Length (Feet)') }} <span class="text-rose-400">*</span></label>
                        <input type="number" name="length_feet" value="6.5" step="0.5" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">{{ __('Width (inch)') }}</label>
                        <input type="number" name="width_inch" placeholder="24" step="0.5" class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">{{ __('Quantity') }} (Pcs) <span class="text-rose-400">*</span></label>
                        <input type="number" name="quantity" value="1" min="1" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">{{ __('Rate / Price') }} (৳)</label>
                        <input type="number" name="rate" placeholder="0.00" step="10" class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 btn-primary py-2.5">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>{{ __('Add New Off-Cut') }}</span>
                </button>
            </form>
        </div>

        <!-- Right: Off-cut Inventory Table (8 cols) -->
        <div class="lg:col-span-8 rounded-xl card-shell overflow-hidden">
            <div class="p-4 bg-[#070d18]/60 border-b border-[#e3e8ee]/10 flex items-center justify-between text-xs">
                <span class="font-semibold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/>
                    </svg>
                    <span>{{ __('Available Usable Off-Cuts & Profiles') }}</span>
                </span>
                <span class="text-[#64748d] tnum">{{ $offCuts->total() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#070d18]/80 text-[#64748d] border-b border-[#e3e8ee]/5">
                        <tr>
                            <th class="p-3 font-semibold">{{ __('Product') }}</th>
                            <th class="p-3 font-semibold">{{ __('Size / Specification') }}</th>
                            <th class="p-3 font-semibold">{{ __('Warehouse') }}</th>
                            <th class="p-3 text-center font-semibold">{{ __('Quantity') }}</th>
                            <th class="p-3 text-right font-semibold">{{ __('Rate (৳)') }}</th>
                            <th class="p-3 text-center font-semibold">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e3e8ee]/5 text-slate-300">
                        @forelse($offCuts as $item)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="p-3">
                                    <span class="font-medium text-white block">{{ $item->product->name ?? '-' }}</span>
                                    @if($item->notes)
                                        <span class="block text-[10px] text-[#64748d]">{{ $item->notes }}</span>
                                    @endif
                                </td>
                                <td class="p-3 font-mono font-semibold text-[#533afd] tnum">
                                    {{ $item->length_feet }} {{ __('Feet') }}
                                    @if($item->width_inch)
                                        × {{ $item->width_inch }}" ({{ number_format($item->sqft, 2) }} SFT)
                                    @endif
                                </td>
                                <td class="p-3 text-[#64748d]">{{ $item->warehouse->name ?? '-' }}</td>
                                <td class="p-3 text-center font-semibold font-mono">
                                    <span class="px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 text-white tnum">{{ $item->quantity }}</span>
                                </td>
                                <td class="p-3 text-right tnum text-[#64748d]">
                                    {{ $item->rate > 0 ? '৳' . number_format($item->rate, 2) : 'Negotiable' }}
                                </td>
                                <td class="p-3 text-center">
                                    <form method="POST" action="{{ route('offcuts.scrap', $item->id) }}" onsubmit="return confirm('Convert this cut-piece into scrap metal?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-purple-500/10 hover:bg-purple-500/20 text-purple-300 border border-purple-500/30 transition text-[11px]" title="{{ __('Mark as Scrap') }}">
                                            <span>{{ __('Mark as Scrap') }}</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-[#64748d]">
                                    {{ __('No usable off-cuts available.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($offCuts->hasPages())
                <div class="p-4 border-t border-[#e3e8ee]/10 bg-[#070d18]/60">
                    {{ $offCuts->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
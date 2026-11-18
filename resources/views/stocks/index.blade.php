@extends('layouts.app')

@section('title', __('Warehouse Stock'))
@section('page_title', __('Warehouse Stock'))

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 card-shell p-4">
        <div>
            <h1 class="text-base font-semibold text-white tracking-tight flex items-center gap-2">
                <svg class="w-4 h-4 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/>
                </svg>
                <span>{{ __('Stock Valuation & Location') }}</span>
            </h1>
            <p class="text-xs text-[#64748d] mt-0.5">{{ __('Realtime stock balance across showrooms and central warehouses') }}</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('stocks.transfer') }}" class="btn-primary text-xs">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m16 3 4 4-4 4"/><path d="M20 7H4"/><path d="m8 21-4-4 4-4"/><path d="M4 17h16"/>
                </svg>
                <span>{{ __('Stock Transfer') }}</span>
            </a>
            <a href="{{ route('products.create') }}" class="btn-secondary text-xs">
                <svg class="w-3.5 h-3.5 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>{{ __('Add New Product') }}</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl card-shell">
            <div class="text-xs font-medium text-[#64748d]">{{ __('Total Inventory Items') }}</div>
            <div class="text-2xl font-bold text-white tnum mt-1">{{ number_format($stats['total_items_in_stock'] ?? 0) }}</div>
            <div class="text-[11px] text-[#64748d] mt-1">{{ __('Across all active warehouses') }}</div>
        </div>
        <div class="p-4 rounded-xl card-shell">
            <div class="text-xs font-medium text-[#64748d]">{{ __('Total Stock Value') }}</div>
            <div class="text-2xl font-bold text-emerald-400 tnum mt-1">৳ {{ number_format($stats['total_stock_value'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-[#64748d] mt-1">{{ __('Calculated at purchase price') }}</div>
        </div>
        <div class="p-4 rounded-xl card-shell">
            <div class="text-xs font-medium text-[#64748d]">{{ __('Low Stock Warning') }}</div>
            <div class="text-2xl font-bold text-amber-400 tnum mt-1">{{ $stats['low_stock_count'] ?? 0 }}</div>
            <div class="text-[11px] text-amber-400/80 mt-1">{{ __('Below re-order threshold') }}</div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('stocks.index') }}" class="p-4 rounded-xl card-shell grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
        <div>
            <label class="block text-[#64748d] mb-1 font-medium">{{ __('Warehouse') }}</label>
            <select name="warehouse_id" class="w-full bg-[#070d18] border border-[#e3e8ee]/15 rounded-lg px-2.5 py-1.5 text-slate-200 focus:border-[#533afd] focus:outline-none">
                <option value="">-- {{ __('Select Warehouse') }} --</option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}" {{ request('warehouse_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[#64748d] mb-1 font-medium">{{ __('Category') }}</label>
            <select name="category_id" class="w-full bg-[#070d18] border border-[#e3e8ee]/15 rounded-lg px-2.5 py-1.5 text-slate-200 focus:border-[#533afd] focus:outline-none">
                <option value="">-- {{ __('All Types') }} --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[#64748d] mb-1 font-medium">{{ __('Search...') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search name, code or barcode...') }}" class="w-full bg-[#070d18] border border-[#e3e8ee]/15 rounded-lg px-2.5 py-1.5 text-slate-200 placeholder-[#64748d] focus:border-[#533afd] focus:outline-none">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 btn-primary py-1.5 text-xs">{{ __('Filter') }}</button>
            <a href="{{ route('stocks.index') }}" class="btn-secondary py-1.5 px-3 text-xs">{{ __('Reset') }}</a>
        </div>
    </form>

    <!-- Stock Table -->
    <div class="rounded-xl card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#070d18]/80 text-[#64748d] font-semibold border-b border-[#e3e8ee]/5">
                    <tr>
                        <th class="p-3.5">{{ __('Product') }}</th>
                        <th class="p-3.5">{{ __('Category & Brand') }}</th>
                        <th class="p-3.5">{{ __('Warehouse') }}</th>
                        <th class="p-3.5 text-right">{{ __('Current Stock') }}</th>
                        <th class="p-3.5 text-right">{{ __('Purchase Price') }}</th>
                        <th class="p-3.5 text-right">{{ __('Retail Selling Price') }}</th>
                        <th class="p-3.5 text-center">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e8ee]/5 text-slate-300">
                    @forelse($stocks as $stock)
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="p-3.5">
                            <span class="font-medium text-white block">{{ $stock->product->name ?? '-' }}</span>
                            <div class="flex items-center gap-2 text-[10px] text-[#64748d] font-mono mt-0.5">
                                <span>{{ __('Code / Barcode') }}: {{ $stock->product->code ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="p-3.5">
                            <span class="text-slate-200 block font-medium">{{ $stock->product->category->name ?? '-' }}</span>
                            <span class="text-[10px] text-[#64748d]">{{ $stock->product->brand->name ?? 'N/A' }}</span>
                        </td>
                        <td class="p-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-white/5 text-slate-300 border border-white/10">
                                <svg class="w-3 h-3 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/>
                                </svg>
                                <span>{{ $stock->warehouse->name ?? '-' }}</span>
                            </span>
                        </td>
                        <td class="p-3.5 text-right font-semibold text-white tnum">
                            {{ number_format($stock->quantity, 2) }}
                            <span class="text-[10px] text-[#64748d] font-normal">{{ $stock->product->unit->name ?? 'Unit' }}</span>
                        </td>
                        <td class="p-3.5 text-right text-[#64748d] tnum">
                            ৳ {{ number_format($stock->product->purchase_price ?? 0, 2) }}
                        </td>
                        <td class="p-3.5 text-right text-emerald-400 font-semibold tnum">
                            ৳ {{ number_format($stock->product->selling_price_retail ?? 0, 2) }}
                        </td>
                        <td class="p-3.5 text-center">
                            @if($stock->quantity <= 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">Out of Stock</span>
                            @elseif($stock->quantity <= ($stock->product->min_stock_alert ?? 5))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Low Stock</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">In Stock</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-[#64748d]">{{ __('No products found.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stocks->hasPages())
        <div class="p-3 border-t border-[#e3e8ee]/10 bg-[#070d18]/60">
            {{ $stocks->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
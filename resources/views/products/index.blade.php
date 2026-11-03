@extends('layouts.app')

@section('title', 'পণ্য তালিকা')
@section('page_title', 'পণ্য ও ইনভেন্টরি মাস্টার (Products Master)')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম, কোড বা বারকোড খুঁজুন..." class="px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs placeholder-[#64748d] focus:outline-none focus:border-[#533afd] w-64">
            
            <select name="product_type" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:outline-none focus:border-[#533afd]">
                <option value="">সকল টাইপ</option>
                <option value="profile" {{ request('product_type') === 'profile' ? 'selected' : '' }}>Aluminium Profile</option>
                <option value="glass" {{ request('product_type') === 'glass' ? 'selected' : '' }}>Glass (কাঁচ)</option>
                <option value="accessory" {{ request('product_type') === 'accessory' ? 'selected' : '' }}>Hardware / Accessory</option>
                <option value="scrap" {{ request('product_type') === 'scrap' ? 'selected' : '' }}>Scrap / ভাঙারি</option>
            </select>

            <button type="submit" class="btn-secondary text-xs py-2 px-4">
                ফিল্টার
            </button>
            @if(request()->hasAny(['search', 'product_type', 'category_id']))
                <a href="{{ route('products.index') }}" class="px-3 py-2 text-xs text-rose-400 hover:underline">রিসেট</a>
            @endif
        </form>

        <a href="{{ route('products.create') }}" class="btn-primary">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>
            </svg>
            <span>নতুন পণ্য যুক্ত করুন</span>
        </a>
    </div>

    <!-- Products Table -->
    <div class="rounded-xl card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#070d18]/80 text-[#64748d] border-b border-[#e3e8ee]/5">
                    <tr>
                        <th class="p-3 font-semibold">কোড / বারকোড</th>
                        <th class="p-3 font-semibold">পণ্যের নাম ও বিবরণ</th>
                        <th class="p-3 font-semibold">ক্যাটাগরি ও ব্র্যান্ড</th>
                        <th class="p-3 font-semibold">পরিমাপ ইউনিট</th>
                        <th class="p-3 font-semibold text-right">ক্রয়মূল্য</th>
                        <th class="p-3 font-semibold text-right">খুচরা বিক্রয়</th>
                        <th class="p-3 font-semibold text-right">মিস্ত্রি রেট</th>
                        <th class="p-3 font-semibold text-center">বর্তমান স্টক</th>
                        <th class="p-3 font-semibold text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e8ee]/5 text-slate-300">
                    @forelse($products as $product)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="p-3 font-mono font-semibold text-[#533afd]">
                                {{ $product->code }}
                                @if($product->barcode)
                                    <span class="block text-[10px] text-[#64748d] font-mono">{{ $product->barcode }}</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <span class="font-medium text-white block">{{ $product->name }}</span>
                                <div class="flex items-center gap-2 mt-0.5 text-[11px] text-[#64748d]">
                                    @if($product->thickness)
                                        <span>গেজ: {{ $product->thickness->value }}</span>
                                    @endif
                                    @if($product->color)
                                        <span>• কালার: {{ $product->color->name }}</span>
                                    @endif
                                    @if($product->length_feet)
                                        <span>• দৈর্ঘ্য: {{ $product->length_feet }}'</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-3">
                                <span class="text-white block font-medium">{{ $product->category->name ?? '-' }}</span>
                                <span class="text-[11px] text-[#64748d]">{{ $product->brand->name ?? 'N/A' }}</span>
                            </td>
                            <td class="p-3">
                                <span class="inline-flex px-2 py-0.5 rounded-full bg-white/5 border border-white/10 text-slate-300 font-mono text-[11px]">
                                    {{ $product->unit->name ?? $product->unit->code }}
                                </span>
                                @if($product->secondaryUnit)
                                    <span class="block text-[10px] text-[#64748d] mt-0.5 tnum">
                                        = {{ $product->conversion_ratio }} {{ $product->secondaryUnit->code }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-right tnum text-[#64748d]">
                                ৳{{ number_format($product->purchase_price, 2) }}
                            </td>
                            <td class="p-3 text-right tnum font-semibold text-emerald-400">
                                ৳{{ number_format($product->selling_price_retail, 2) }}
                            </td>
                            <td class="p-3 text-right tnum font-medium text-amber-300">
                                ৳{{ number_format($product->selling_price_contractor, 2) }}
                            </td>
                            <td class="p-3 text-center">
                                @php $totalStock = $product->stocks->sum('quantity'); @endphp
                                <span class="inline-flex items-center gap-1 font-semibold font-mono px-2.5 py-0.5 rounded-full text-[11px] tnum {{ $totalStock <= $product->min_stock_alert ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                                    {{ number_format($totalStock, 1) }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('products.edit', $product->id) }}" class="w-7 h-7 rounded-full inline-flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/10 transition" title="এডিট">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-[#64748d]">
                                কোনো পণ্য পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-[#e3e8ee]/10 bg-[#070d18]/60">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'পণ্য তালিকা')
@section('page_title', 'পণ্য ও ইনভেন্টরি মাস্টার (Products Master)')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম, কোড বা বারকোড খুঁজুন..." class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-cyan-500 w-64">
            
            <select name="product_type" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                <option value="">সকল টাইপ</option>
                <option value="profile" {{ request('product_type') === 'profile' ? 'selected' : '' }}>Aluminium Profile</option>
                <option value="glass" {{ request('product_type') === 'glass' ? 'selected' : '' }}>Glass (কাঁচ)</option>
                <option value="accessory" {{ request('product_type') === 'accessory' ? 'selected' : '' }}>Hardware / Accessory</option>
                <option value="scrap" {{ request('product_type') === 'scrap' ? 'selected' : '' }}>Scrap / ভাঙারি</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition">
                ফিল্টার
            </button>
            @if(request()->hasAny(['search', 'product_type', 'category_id']))
                <a href="{{ route('products.index') }}" class="px-3 py-2 text-xs text-rose-400 hover:underline">রিসেট</a>
            @endif
        </form>

        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/20 transition">
            <span>➕</span> নতুন পণ্য যুক্ত করুন
        </a>
    </div>

    <!-- Products Table -->
    <div class="rounded-2xl border border-slate-800 glass-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="p-3">কোড / বারকোড</th>
                        <th class="p-3">পণ্যের নাম ও বিবরণ</th>
                        <th class="p-3">ক্যাটাগরি ও ব্র্যান্ড</th>
                        <th class="p-3">পরিমাপ ইউনিট</th>
                        <th class="p-3 text-right">ক্রয়মূল্য</th>
                        <th class="p-3 text-right">খুচরা বিক্রয়</th>
                        <th class="p-3 text-right">মিস্ত্রি রেট</th>
                        <th class="p-3 text-center">বর্তমান স্টক</th>
                        <th class="p-3 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="p-3 font-mono text-cyan-400 font-bold">
                                {{ $product->code }}
                                @if($product->barcode)
                                    <span class="block text-[10px] text-slate-500 font-mono">{{ $product->barcode }}</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <span class="font-bold text-white block">{{ $product->name }}</span>
                                <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-400">
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
                                <span class="text-white block">{{ $product->category->name ?? '-' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $product->brand->name ?? 'N/A' }}</span>
                            </td>
                            <td class="p-3">
                                <span class="inline-flex px-2 py-0.5 rounded bg-slate-800 text-slate-300 font-mono">
                                    {{ $product->unit->name ?? $product->unit->code }}
                                </span>
                                @if($product->secondaryUnit)
                                    <span class="block text-[10px] text-slate-500 mt-0.5">
                                        = {{ $product->conversion_ratio }} {{ $product->secondaryUnit->code }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-right font-mono-numbers text-slate-400">
                                ৳{{ number_format($product->purchase_price, 2) }}
                            </td>
                            <td class="p-3 text-right font-mono-numbers font-bold text-emerald-400">
                                ৳{{ number_format($product->selling_price_retail, 2) }}
                            </td>
                            <td class="p-3 text-right font-mono-numbers font-medium text-amber-300">
                                ৳{{ number_format($product->selling_price_contractor, 2) }}
                            </td>
                            <td class="p-3 text-center">
                                @php $totalStock = $product->stocks->sum('quantity'); @endphp
                                <span class="inline-flex items-center gap-1 font-bold font-mono px-2 py-1 rounded {{ $totalStock <= $product->min_stock_alert ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                                    {{ number_format($totalStock, 1) }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="p-1 rounded hover:bg-slate-800 text-slate-300 hover:text-cyan-400 transition" title="এডিট">
                                        ✏️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-500">
                                কোনো পণ্য পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/60">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', __('Add Product'))
@section('page_title', __('Add New Product'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="rounded-xl card-shell p-6 sm:p-8">
        <form method="POST" action="{{ route('products.store') }}" class="space-y-6">
            @csrf

            <!-- Form Header -->
            <div class="border-b border-[#e3e8ee]/10 pb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-white">{{ __('Product Information') }}</h2>
                    <p class="text-xs text-[#64748d] mt-0.5">{{ __('Enter accurate specifications, dimensions, and pricing') }}</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn-secondary text-xs py-1.5 px-3">
                    ← {{ __('Back') }}
                </a>
            </div>

            <!-- Basic Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Product Name') }} <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. 4 Sliding Bottom (1.2mm SS Finish)" required class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                    @error('name') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Product Type') }} <span class="text-rose-400">*</span></label>
                    <select name="product_type" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        <option value="profile" {{ old('product_type') == 'profile' ? 'selected' : '' }}>Aluminium Profile</option>
                        <option value="glass" {{ old('product_type') == 'glass' ? 'selected' : '' }}>Glass</option>
                        <option value="accessory" {{ old('product_type') == 'accessory' ? 'selected' : '' }}>Hardware / Accessories</option>
                        <option value="board_ceiling" {{ old('product_type') == 'board_ceiling' ? 'selected' : '' }}>Board & Ceiling</option>
                        <option value="scrap" {{ old('product_type') == 'scrap' ? 'selected' : '' }}>Aluminium Scrap</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Product Code') }} <span class="text-rose-400">*</span></label>
                    <input type="text" name="code" value="{{ old('code', 'SEC-' . rand(1000, 9999)) }}" required class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono focus:border-[#533afd] focus:outline-none">
                    @error('code') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Barcode') }}</label>
                    <input type="text" name="barcode" value="{{ old('barcode') }}" placeholder="" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono focus:border-[#533afd] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Category') }}</label>
                    <select name="category_id" class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        <option value="">{{ __('Category') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Brand') }}</label>
                    <select name="brand_id" class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        <option value="">{{ __('Brand') }}</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Primary Unit') }} <span class="text-rose-400">*</span></label>
                    <select name="unit_id" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Secondary Unit') }}</label>
                    <select name="secondary_unit_id" class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        <option value="">--</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('secondary_unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Dimensions & Specifications -->
            <div class="border-t border-[#e3e8ee]/10 pt-4">
                <h3 class="text-xs font-semibold text-[#533afd] uppercase tracking-wider mb-3">{{ __('Size / Specification') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Color') }}</label>
                        <select name="color_id" class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                            <option value="">{{ __('Color') }}</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Thickness') }}</label>
                        <select name="thickness_id" class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                            <option value="">{{ __('Thickness') }}</option>
                            @foreach($thicknesses as $th)
                                <option value="{{ $th->id }}" {{ old('thickness_id') == $th->id ? 'selected' : '' }}>{{ $th->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Length (Feet)') }}</label>
                        <input type="number" name="length_feet" value="{{ old('length_feet', '16.00') }}" step="0.5" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs tnum focus:border-[#533afd] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Weight (Kg)') }}</label>
                        <input type="number" name="weight_per_unit_kg" value="{{ old('weight_per_unit_kg') }}" step="0.01" placeholder="4.25" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Pricing & Initial Stock -->
            <div class="border-t border-[#e3e8ee]/10 pt-4">
                <h3 class="text-xs font-semibold text-emerald-400 uppercase tracking-wider mb-3">{{ __('Purchase Price') }} & {{ __('Current Stock') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Purchase Price') }} (৳) <span class="text-rose-400">*</span></label>
                        <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" step="0.01" required placeholder="0.00" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Retail Selling Price') }} (৳) <span class="text-rose-400">*</span></label>
                        <input type="number" name="selling_price_retail" value="{{ old('selling_price_retail') }}" step="0.01" required placeholder="0.00" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono font-semibold text-emerald-400 tnum focus:border-[#533afd] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Mistri Rate') }} (৳)</label>
                        <input type="number" name="selling_price_contractor" value="{{ old('selling_price_contractor') }}" step="0.01" placeholder="0.00" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono text-amber-300 tnum focus:border-[#533afd] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Wholesale (৳)</label>
                        <input type="number" name="selling_price_wholesale" value="{{ old('selling_price_wholesale') }}" step="0.01" placeholder="0.00" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Current Stock') }}</label>
                        <input type="number" name="initial_stock" value="{{ old('initial_stock', '0') }}" step="0.5" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Warehouse') }}</label>
                        <select name="warehouse_id" class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">{{ __('Alert Quantity') }}</label>
                        <input type="number" name="min_stock_alert" value="{{ old('min_stock_alert', '5') }}" class="w-full px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="border-t border-[#e3e8ee]/10 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('products.index') }}" class="btn-secondary text-xs py-2 px-4">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn-primary min-h-[42px] px-6">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>{{ __('Save') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
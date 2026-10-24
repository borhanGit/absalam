@extends('layouts.app')

@section('title', 'কোটেশন #' . $quotation->quotation_no)
@section('page_title', 'প্রজেক্ট কোটেশন ও এস্টিমেট #' . $quotation->quotation_no)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Action Toolbar (Hidden in Print) -->
    <div class="flex items-center justify-between gap-4 print:hidden">
        <a href="{{ route('quotations.index') }}" class="text-xs text-slate-400 hover:text-white transition">
            ← কোটেশন তালিকায় ফিরুন
        </a>
        <div class="flex items-center gap-2">
            @if($quotation->status !== 'converted_to_sale')
                <form method="POST" action="{{ route('quotations.convert', $quotation->id) }}" onsubmit="return confirm('আপনি কি এই কোটেশনটি বিক্রয় চালানে রূপান্তর করতে চান?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition flex items-center gap-1.5">
                        <span>⚡</span> সেলস চালানে রূপান্তর করুন
                    </button>
                </form>
            @else
                <span class="px-3 py-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 font-bold text-xs border border-emerald-500/20">
                    ✓ ইতিমধ্যে চালানে রূপান্তরিত
                </span>
            @endif

            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-bold shadow-lg shadow-cyan-500/20 transition flex items-center gap-1.5">
                <span>🖨️</span> প্রিন্ট কোটেশন
            </button>
        </div>
    </div>

    <!-- Official Proposal Sheet -->
    <div class="rounded-2xl border border-slate-800 bg-white text-slate-900 p-8 sm:p-10 shadow-2xl print:shadow-none print:border-none print:p-0">
        <!-- Header -->
        <div class="border-b-2 border-slate-900 pb-5 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-950 uppercase">
                    {{ $company->company_name ?? 'ঢাকা থাই & অ্যালুমিনিয়াম গ্লাস হাউজ' }}
                </h1>
                <p class="text-xs text-slate-600 mt-1">
                    {{ $company->address ?? 'মিরপুর-১০, ঢাকা-১২১৬, বাংলাদেশ' }}
                </p>
                <p class="text-xs text-slate-700 font-semibold mt-0.5">
                    📞 {{ $company->phone ?? '01712-345678' }}
                </p>
            </div>
            <div class="sm:text-right">
                <span class="inline-block px-3 py-1 rounded bg-slate-900 text-white text-xs font-bold uppercase tracking-wider mb-2">
                    অফিশিয়াল কোটেশন
                </span>
                <div class="text-xs font-mono font-bold text-slate-800">
                    কোটেশন নং: <span class="text-sm font-black">{{ $quotation->quotation_no }}</span>
                </div>
                <div class="text-xs text-slate-600">তারিখ: {{ $quotation->quotation_date }}</div>
            </div>
        </div>

        <!-- Project & Client Metadata -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 grid grid-cols-2 gap-4 text-xs mb-6">
            <div>
                <span class="text-slate-500 font-bold uppercase text-[10px] block">প্রস্তুত করা হয়েছে (ক্লায়েন্ট):</span>
                <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ $quotation->customer->name ?? '-' }}</span>
                <span class="text-slate-600 block">{{ $quotation->customer->phone ?? '' }}</span>
                <span class="text-slate-600 block">{{ $quotation->customer->address ?? '' }}</span>
            </div>
            <div class="text-right">
                <span class="text-slate-500 font-bold uppercase text-[10px] block">প্রজেক্ট সাইট:</span>
                <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ $quotation->project_name }}</span>
                <span class="text-slate-600 block">মোট কাঠামো: {{ $quotation->total_units }} টি</span>
                <span class="text-slate-700 font-bold font-mono block">মোট পরিমাপ: {{ number_format($quotation->total_sqft, 2) }} SFT</span>
            </div>
        </div>

        <!-- Quotation Specification Table -->
        <div class="border border-slate-300 rounded-lg overflow-hidden mb-6">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 border-b border-slate-300 font-bold text-slate-800">
                    <tr>
                        <th class="p-2.5 text-center w-8">#</th>
                        <th class="p-2.5">কাঠামোর বিবরণ ও অবস্থান</th>
                        <th class="p-2.5 text-center">মাপ (ইঞ্চি)</th>
                        <th class="p-2.5 text-center">পরিমাণ</th>
                        <th class="p-2.5 text-center">মোট SFT</th>
                        <th class="p-2.5">সেকশন ও কাঁচ</th>
                        <th class="p-2.5 text-right">দর/SFT (৳)</th>
                        <th class="p-2.5 text-right">মোট টাকা (৳)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @foreach($quotation->items as $index => $item)
                        <tr>
                            <td class="p-2.5 text-center text-slate-500 font-mono">{{ $index + 1 }}</td>
                            <td class="p-2.5">
                                <span class="font-bold block">{{ ucwords(str_replace('_', ' ', $item->structure_type)) }}</span>
                                @if($item->location_tag)
                                    <span class="text-[11px] text-slate-600">অবস্থান: {{ $item->location_tag }}</span>
                                @endif
                            </td>
                            <td class="p-2.5 text-center font-mono text-slate-700">
                                {{ $item->width_inch }}" × {{ $item->height_inch }}"
                            </td>
                            <td class="p-2.5 text-center font-bold font-mono">{{ $item->quantity }} টি</td>
                            <td class="p-2.5 text-center font-bold font-mono text-slate-900">{{ number_format($item->total_sqft, 2) }}</td>
                            <td class="p-2.5 text-slate-700">
                                <div>সেকশন: {{ $item->profileProduct->name ?? 'স্ট্যান্ডার্ড 1.2mm SS' }}</div>
                                <div>কাঁচ: {{ $item->glassProduct->name ?? '5mm Clear Glass' }}</div>
                            </td>
                            <td class="p-2.5 text-right font-mono">৳{{ number_format($item->rate_per_sqft, 2) }}</td>
                            <td class="p-2.5 text-right font-bold font-mono">৳{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Terms & Totals Summary -->
        <div class="flex flex-col sm:flex-row justify-between gap-6 text-xs mb-8">
            <div class="max-w-xs text-slate-600 space-y-2">
                <span class="font-bold text-slate-900 block">শর্তাবলী ও নিয়মাবলী:</span>
                <p class="whitespace-pre-line">{{ $quotation->terms_and_conditions ?? '১. ডেলিভারি সময়কাল ৭-১০ কার্যদিবস।' }}</p>
            </div>

            <div class="w-full sm:w-64 space-y-1.5 font-mono text-slate-800 border-t sm:border-t-0 pt-3 sm:pt-0">
                <div class="flex justify-between">
                    <span class="text-slate-600">মোট স্কয়ার ফিট:</span>
                    <span>{{ number_format($quotation->total_sqft, 2) }} SFT</span>
                </div>
                @if($quotation->fitting_installation_cost > 0)
                    <div class="flex justify-between">
                        <span class="text-slate-600">ফিটিং চার্জ (+):</span>
                        <span>৳{{ number_format($quotation->fitting_installation_cost, 2) }}</span>
                    </div>
                @endif
                @if($quotation->transport_cost > 0)
                    <div class="flex justify-between">
                        <span class="text-slate-600">পরিবহন ভাড়া (+):</span>
                        <span>৳{{ number_format($quotation->transport_cost, 2) }}</span>
                    </div>
                @endif
                @if($quotation->discount_amount > 0)
                    <div class="flex justify-between text-rose-600">
                        <span>ডিসকাউন্ট (-):</span>
                        <span>৳{{ number_format($quotation->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-black text-base text-slate-950 border-t-2 border-slate-900 pt-2">
                    <span>সর্বমোট বাজেট:</span>
                    <span>৳{{ number_format($quotation->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Signature -->
        <div class="pt-12 flex items-center justify-between text-xs text-slate-600">
            <div class="text-center">
                <div class="w-36 border-t border-slate-400 mb-1"></div>
                <span>ক্লায়েন্ট সম্মতি স্বাক্ষর</span>
            </div>
            <div class="text-center">
                <div class="w-36 border-t border-slate-400 mb-1"></div>
                <span>কর্তৃপক্ষের স্বাক্ষর</span>
            </div>
        </div>
    </div>

</div>
@endsection

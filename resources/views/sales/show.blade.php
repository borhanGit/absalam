@extends('layouts.app')

@section('title', 'চালান #' . $sale->invoice_no)
@section('page_title', 'বিক্রয় চালান ও ক্যাশ মেমো #' . $sale->invoice_no)

@section('content')
<div class="max-w-4xl mx-auto space-y-6 print:max-w-none print:w-full print:m-0 print:p-0 print:space-y-0">

    <!-- Action Toolbar (Hidden in Print) -->
    <div class="flex items-center justify-between gap-4 print:hidden no-print">
        <a href="{{ route('sales.index') }}" class="btn-secondary text-xs py-1.5 px-3">
            ← চালান তালিকায় ফিরুন
        </a>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('pos.create') }}" class="btn-primary text-xs">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
                <span>নতুন পিওএস</span>
            </a>
            <button onclick="window.print()" class="btn-secondary text-xs">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                </svg>
                <span>প্রিন্ট চালান</span>
            </button>
        </div>
    </div>

    <!-- Printable Invoice Sheet -->
    <div class="rounded-2xl border border-slate-200 bg-white text-slate-900 p-8 sm:p-10 shadow-xl print:shadow-none print:border-none print:p-0 print:m-0 print:w-full print:rounded-none">
        <!-- Invoice Header -->
        <div class="border-b-2 border-slate-900 pb-5 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-950 uppercase">
                    {{ $company->company_name ?? 'ঢাকা থাই & অ্যালুমিনিয়াম গ্লাস হাউজ' }}
                </h1>
                <p class="text-xs text-slate-600 mt-1">
                    {{ $company->address ?? 'মিরপুর-১০, ঢাকা-১২১৬, বাংলাদেশ' }}
                </p>
                <p class="text-xs text-slate-700 font-semibold mt-0.5">
                    ফোন: {{ $company->phone ?? '01712-345678' }} • প্রোপাইটর: {{ $company->proprietor_name ?? 'মো: বোরহান উদ্দিন' }}
                </p>
            </div>
            <div class="sm:text-right">
                <span class="inline-block px-3 py-1 rounded bg-slate-900 text-white text-xs font-bold uppercase tracking-wider mb-2">
                    ক্যাশ মেমো / ইনভয়েস
                </span>
                <div class="text-xs font-mono font-bold text-slate-800">
                    চালান নং: <span class="text-sm font-black">{{ $sale->invoice_no }}</span>
                </div>
                <div class="text-xs text-slate-600 tnum">তারিখ: {{ $sale->sale_date }}</div>
            </div>
        </div>

        <!-- Customer & Branch Meta -->
        <div class="grid grid-cols-2 gap-4 text-xs mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200">
            <div>
                <span class="text-slate-500 block uppercase font-bold text-[10px]">ক্রেতা / কাস্টমার বিবরণ:</span>
                <span class="font-bold text-slate-900 text-sm block mt-0.5">{{ $sale->customer->name ?? '-' }}</span>
                <span class="text-slate-600 block">{{ $sale->customer->phone ?? '' }}</span>
                <span class="text-slate-600 block">{{ $sale->customer->address ?? '' }}</span>
            </div>
            <div class="text-right">
                <span class="text-slate-500 block uppercase font-bold text-[10px]">শাখা / ডেলিভারি গোডাউন:</span>
                <span class="font-bold text-slate-900 block mt-0.5">{{ $sale->warehouse->name ?? '-' }}</span>
                @if($sale->contractor)
                    <span class="text-slate-700 font-semibold block mt-1">মিস্ত্রি রেফারেন্স: {{ $sale->contractor->name }}</span>
                @endif
            </div>
        </div>

        <!-- Invoice Table -->
        <div class="border border-slate-300 rounded-lg overflow-hidden mb-6">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 border-b border-slate-300 font-bold text-slate-800">
                    <tr>
                        <th class="p-2.5 w-10 text-center">নং</th>
                        <th class="p-2.5">পণ্যের বিবরণ (আইটেম)</th>
                        <th class="p-2.5">মাপ / স্পেসিফিকেশন</th>
                        <th class="p-2.5 text-center">পরিমাণ</th>
                        <th class="p-2.5 text-right">দর (৳)</th>
                        <th class="p-2.5 text-right">মোট টাকা (৳)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @foreach($sale->items as $index => $item)
                        <tr>
                            <td class="p-2.5 text-center text-slate-500 font-mono">{{ $index + 1 }}</td>
                            <td class="p-2.5 font-bold">
                                {{ $item->product->name ?? '-' }}
                            </td>
                            <td class="p-2.5 text-slate-600 font-mono tnum">
                                @if($item->height_inch && $item->width_inch)
                                    {{ $item->height_inch }}" × {{ $item->width_inch }}" ({{ number_format($item->total_sqft, 2) }} SFT)
                                @elseif($item->length_feet)
                                    {{ $item->length_feet }} ফুট
                                @else
                                    ফুল বার / পিস
                                @endif
                            </td>
                            <td class="p-2.5 text-center font-bold font-mono tnum">
                                {{ number_format($item->quantity, 2) }} {{ $item->unit->code ?? '' }}
                            </td>
                            <td class="p-2.5 text-right font-mono tnum">৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="p-2.5 text-right font-bold font-mono tnum">৳{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals & Payment Summary -->
        <div class="flex flex-col sm:flex-row justify-between gap-6 text-xs mb-8">
            <div class="max-w-xs text-slate-600 space-y-1">
                <span class="font-bold text-slate-900 block">শর্তাবলী:</span>
                <p>{{ $company->invoice_footer_note ?? 'ধন্যবাদ! কাঁচ ও অ্যালুমিনিয়াম মাল ডেলিভারির সময় ভালো করে দেখে বুঝে নিন। কাটার পর মাল ফেরত গ্রহণযোগ্য নয়।' }}</p>
                @if($sale->notes)
                    <p class="text-slate-800 font-medium mt-2">নোট: {{ $sale->notes }}</p>
                @endif
            </div>

            <div class="w-full sm:w-64 space-y-1.5 font-mono text-slate-800 border-t sm:border-t-0 pt-3 sm:pt-0">
                <div class="flex justify-between">
                    <span class="text-slate-600">আইটেম মোট:</span>
                    <span class="tnum">৳{{ number_format($sale->subtotal, 2) }}</span>
                </div>
                @if($sale->carrying_cost > 0)
                    <div class="flex justify-between">
                        <span class="text-slate-600">ভ্যান / পরিবহন ভাড়া (+):</span>
                        <span class="tnum">৳{{ number_format($sale->carrying_cost, 2) }}</span>
                    </div>
                @endif
                @if($sale->labor_fitting_cost > 0)
                    <div class="flex justify-between">
                        <span class="text-slate-600">কাটিং / লেবার মজুরি (+):</span>
                        <span class="tnum">৳{{ number_format($sale->labor_fitting_cost, 2) }}</span>
                    </div>
                @endif
                @if($sale->discount_amount > 0)
                    <div class="flex justify-between text-rose-600">
                        <span>ডিসকাউন্ট (-):</span>
                        <span class="tnum">৳{{ number_format($sale->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-sm text-slate-950 border-t border-slate-900 pt-1.5 pb-1">
                    <span>সর্বমোট বিল:</span>
                    <span class="tnum">৳{{ number_format($sale->grand_total, 2) }}</span>
                </div>
                <div class="flex justify-between text-emerald-700 font-bold">
                    <span>নগদ পরিশোধ:</span>
                    <span class="tnum">৳{{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-amber-700 font-black border-t border-slate-300 pt-1">
                    <span>বকেয়া / বাকি (Due):</span>
                    <span class="tnum">৳{{ number_format($sale->due_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Signature Blocks -->
        <div class="pt-12 flex items-center justify-between text-xs text-slate-600">
            <div class="text-center">
                <div class="w-36 border-t border-slate-400 mb-1"></div>
                <span>ক্রেতার স্বাক্ষর</span>
            </div>
            <div class="text-center">
                <div class="w-36 border-t border-slate-400 mb-1"></div>
                <span>কর্তৃপক্ষের স্বাক্ষর</span>
            </div>
        </div>
    </div>

</div>
@endsection

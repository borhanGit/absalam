@extends('layouts.app')

@section('title', 'কাটিং অফ-কাট ও স্ক্র্যাপ স্টক')
@section('page_title', '✂️ কাটিং অফ-কাট ও অ্যালুমিনিয়াম ভাঙারি (Off-Cut & Scrap Stock)')

@section('content')
<div class="space-y-6">

    <!-- Scrap / Bhangari Overview Banner -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl bg-gradient-to-tr from-purple-900/40 via-slate-900 to-slate-900 border border-purple-500/30">
            <span class="text-xs text-purple-300 font-semibold block">মজুত অ্যালুমিনিয়াম ভাঙারি (Scrap)</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-white font-mono-numbers">{{ number_format($scrapStock, 2) }}</span>
                <span class="text-xs text-purple-300">কেজি (Kg)</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">বিক্রয়মূল্য: ৳{{ number_format($scrapProduct->selling_price_retail ?? 270, 0) }}/কেজি</p>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400 font-semibold block">অব্যবহৃত অফ-কাট টুকরো</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-cyan-400 font-mono-numbers">{{ $offCuts->total() }}</span>
                <span class="text-xs text-slate-400">টি সাইজের পিস</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">ছোট অর্ডারে রিইউজ করে মুনাফা বাড়ানো যায়</p>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-800 flex flex-col justify-between">
            <div>
                <span class="text-xs text-slate-400 font-semibold block">অফ-কাটের সুবিধা</span>
                <p class="text-xs text-slate-300 mt-1">ফুল ১৬ ফুট বার না কেটে আগে এখান থেকে সাইজ মিলিয়ে কাস্টমারকে দিন।</p>
            </div>
            <a href="{{ route('pos.create') }}" class="text-xs text-cyan-400 font-bold hover:underline">
                পিওএস সেলসে যান →
            </a>
        </div>
    </div>

    <!-- Manual Off-cut Entry Form & Table Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left: Form to record workshop leftover (4 cols) -->
        <div class="lg:col-span-4 rounded-2xl border border-slate-800 glass-panel p-5 space-y-4">
            <h3 class="text-sm font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
                <span>➕</span> নতুন অফ-কাট টুকরো এন্ট্রি
            </h3>

            <form method="POST" action="{{ route('offcuts.store') }}" class="space-y-3 text-xs">
                @csrf

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">পণ্য / সেকশন বা কাঁচ <span class="text-rose-400">*</span></label>
                    <select name="product_id" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">ওয়্যারহাউস / ওয়ার্কশপ <span class="text-rose-400">*</span></label>
                    <select name="warehouse_id" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">দৈর্ঘ্য (ফুট) <span class="text-rose-400">*</span></label>
                        <input type="number" name="length_feet" value="6.5" step="0.5" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">চওড়া (ইঞ্চি কাঁচ হলে)</label>
                        <input type="number" name="width_inch" placeholder="যেমন: 24" step="0.5" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">সংখ্যা (Pcs) <span class="text-rose-400">*</span></label>
                        <input type="number" name="quantity" value="1" min="1" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">সম্ভাব্য রেট (৳)</label>
                        <input type="number" name="rate" placeholder="0.00" step="10" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-md shadow-cyan-500/20 transition">
                    ✓ অফ-কাট স্টকে যুক্ত করুন
                </button>
            </form>
        </div>

        <!-- Right: Off-cut Inventory Table (8 cols) -->
        <div class="lg:col-span-8 rounded-2xl border border-slate-800 glass-panel overflow-hidden">
            <div class="p-4 bg-slate-900/80 border-b border-slate-800 flex items-center justify-between text-xs">
                <span class="font-bold text-white flex items-center gap-2">
                    <span>📦</span> স্টকে বিদ্যমান অফ-কাটসমূহ
                </span>
                <span class="text-slate-400">মোট {{ $offCuts->total() }} আইটেম</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-900 text-slate-400">
                        <tr>
                            <th class="p-3">পণ্যের নাম</th>
                            <th class="p-3">সাইজ / পরিমাপ</th>
                            <th class="p-3">অবস্থান</th>
                            <th class="p-3 text-center">পরিমাণ</th>
                            <th class="p-3 text-right">রেট (৳)</th>
                            <th class="p-3 text-center">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($offCuts as $item)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="p-3 font-bold text-white">
                                    {{ $item->product->name ?? '-' }}
                                    @if($item->notes)
                                        <span class="block text-[10px] text-slate-500 font-normal">{{ $item->notes }}</span>
                                    @endif
                                </td>
                                <td class="p-3 font-mono font-bold text-cyan-400">
                                    {{ $item->length_feet }} ফুট
                                    @if($item->width_inch)
                                        × {{ $item->width_inch }}" ({{ number_format($item->sqft, 2) }} SFT)
                                    @endif
                                </td>
                                <td class="p-3 text-slate-400">{{ $item->warehouse->name ?? '-' }}</td>
                                <td class="p-3 text-center font-bold font-mono">
                                    <span class="px-2 py-0.5 rounded bg-slate-800 text-cyan-300">{{ $item->quantity }} পিস</span>
                                </td>
                                <td class="p-3 text-right font-mono-numbers">
                                    {{ $item->rate > 0 ? '৳' . number_format($item->rate, 2) : 'নেগোশিয়েবল' }}
                                </td>
                                <td class="p-3 text-center">
                                    <form method="POST" action="{{ route('offcuts.scrap', $item->id) }}" onsubmit="return confirm('এই টুকরোটি ভেঙে ভাঙারি/স্ক্র্যাপ স্টকে যুক্ত করতে চান?');">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 rounded bg-purple-500/10 hover:bg-purple-500/20 text-purple-300 border border-purple-500/30 transition text-[11px]" title="ভাঙারিতে রূপান্তর">
                                            ভাঙারি করুন ♻️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500">
                                    বর্তমানে কোনো অবিক্রিত কাটিং অফ-কাট টুকরো নেই।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($offCuts->hasPages())
                <div class="p-4 border-t border-slate-800 bg-slate-900/60">
                    {{ $offCuts->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

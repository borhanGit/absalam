@extends('layouts.app')

@section('title', 'জানালা ও দরজা কোটেশন')
@section('page_title', '📐 থাই প্রজেক্ট কোটেশন তালিকা (Job Quotations)')

@section('content')
<div class="space-y-6">

    <!-- Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('quotations.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="কোটেশন নং বা প্রজেক্ট নাম খুঁজুন..." class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-cyan-500 w-72">
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition">
                খুঁজুন
            </button>
        </form>

        <a href="{{ route('quotations.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/20 transition">
            <span>➕</span> নতুন প্রজেক্ট কোটেশন তৈরি
        </a>
    </div>

    <!-- Quotations Table -->
    <div class="rounded-2xl border border-slate-800 glass-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="p-3">কোটেশন নং</th>
                        <th class="p-3">তারিখ</th>
                        <th class="p-3">কাস্টমার / ক্লায়েন্ট</th>
                        <th class="p-3">প্রজেক্টের নাম</th>
                        <th class="p-3 text-center">মোট ইউনিট</th>
                        <th class="p-3 text-center">মোট স্কয়ার ফিট</th>
                        <th class="p-3 text-right">সর্বমোট বাজেট</th>
                        <th class="p-3 text-center">স্ট্যাটাস</th>
                        <th class="p-3 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($quotations as $q)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="p-3 font-mono font-bold text-cyan-400">
                                <a href="{{ route('quotations.show', $q->id) }}" class="hover:underline">{{ $q->quotation_no }}</a>
                            </td>
                            <td class="p-3 text-slate-400">{{ $q->quotation_date }}</td>
                            <td class="p-3">
                                <span class="font-bold text-white block">{{ $q->customer->name ?? '-' }}</span>
                                <span class="text-[11px] text-slate-500">{{ $q->customer->phone ?? '' }}</span>
                            </td>
                            <td class="p-3 text-slate-300 font-medium">{{ $q->project_name }}</td>
                            <td class="p-3 text-center font-mono font-bold">{{ $q->total_units }} টি</td>
                            <td class="p-3 text-center font-mono font-bold text-cyan-400">{{ number_format($q->total_sqft, 2) }} SFT</td>
                            <td class="p-3 text-right font-mono-numbers font-bold text-emerald-400">৳{{ number_format($q->grand_total, 2) }}</td>
                            <td class="p-3 text-center">
                                @if($q->status === 'converted_to_sale')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">চালানে রূপান্তরিত</span>
                                @elseif($q->status === 'approved')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">অনুমোদিত</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">খসড়া (Draft)</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('quotations.show', $q->id) }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 transition font-semibold text-[11px]">
                                    বিস্তারিত ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-500">
                                কোনো কোটেশন তৈরি হয়নি। <a href="{{ route('quotations.create') }}" class="text-cyan-400 font-bold hover:underline">নতুন কোটেশন তৈরি করুন</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotations->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/60">
                {{ $quotations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

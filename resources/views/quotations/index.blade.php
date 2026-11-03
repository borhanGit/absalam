@extends('layouts.app')

@section('title', 'জানালা ও দরজা কোটেশন')
@section('page_title', 'থাই প্রজেক্ট কোটেশন তালিকা (Job Quotations)')

@section('content')
<div class="space-y-6">

    <!-- Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('quotations.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="কোটেশন নং বা প্রজেক্ট নাম খুঁজুন..." class="px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs placeholder-[#64748d] focus:outline-none focus:border-[#533afd] w-72">
            <button type="submit" class="btn-secondary text-xs py-2 px-4">
                খুঁজুন
            </button>
            @if(request('search'))
                <a href="{{ route('quotations.index') }}" class="px-3 py-2 text-xs text-rose-400 hover:underline">রিসেট</a>
            @endif
        </form>

        <a href="{{ route('quotations.create') }}" class="btn-primary">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>
            </svg>
            <span>নতুন প্রজেক্ট কোটেশন তৈরি</span>
        </a>
    </div>

    <!-- Quotations Table -->
    <div class="rounded-xl card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#070d18]/80 text-[#64748d] border-b border-[#e3e8ee]/5">
                    <tr>
                        <th class="p-3 font-semibold">কোটেশন নং</th>
                        <th class="p-3 font-semibold">তারিখ</th>
                        <th class="p-3 font-semibold">কাস্টমার / ক্লায়েন্ট</th>
                        <th class="p-3 font-semibold">প্রজেক্টের নাম</th>
                        <th class="p-3 text-center font-semibold">মোট ইউনিট</th>
                        <th class="p-3 text-center font-semibold">মোট স্কয়ার ফিট</th>
                        <th class="p-3 text-right font-semibold">সর্বমোট বাজেট</th>
                        <th class="p-3 text-center font-semibold">স্ট্যাটাস</th>
                        <th class="p-3 text-center font-semibold">অ্যাকশন</th>
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
                            <td class="p-3 text-center font-mono tnum">{{ $q->total_units }} টি</td>
                            <td class="p-3 text-center font-mono font-semibold text-[#533afd] tnum">{{ number_format($q->total_sqft, 2) }} SFT</td>
                            <td class="p-3 text-right font-medium text-emerald-400 tnum">৳{{ number_format($q->grand_total, 2) }}</td>
                            <td class="p-3 text-center">
                                @if($q->status === 'converted_to_sale')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">চালানে রূপান্তরিত</span>
                                @elseif($q->status === 'approved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#533afd]/15 text-[#533afd] border border-[#533afd]/30">অনুমোদিত</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/5 text-[#64748d] border border-white/10">খসড়া (Draft)</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('quotations.show', $q->id) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/5 hover:bg-white/10 text-slate-200 border border-[#e3e8ee]/10 transition font-medium text-[11px]">
                                    <span>বিস্তারিত</span>
                                    <span>↗</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-[#64748d]">
                                কোনো কোটেশন তৈরি হয়নি। <a href="{{ route('quotations.create') }}" class="text-[#533afd] font-semibold hover:underline">নতুন কোটেশন তৈরি করুন</a>
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

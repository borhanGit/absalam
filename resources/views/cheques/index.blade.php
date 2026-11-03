@extends('layouts.app')

@section('title', 'ব্যাংক চেক ব্যবস্থাপনা')
@section('page_title', 'ব্যাংক চেক ব্যবস্থাপনা (Cheque Management)')

@section('content')
<div class="space-y-6">

    <!-- Header with Actions -->
    <div class="card-shell p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-lg font-bold text-white tracking-tight">চেক ট্র্যাকিং ও ব্যাংক ক্লিয়ারেন্স রেজিস্টার</h1>
            <p class="text-xs text-slate-400 mt-0.5">কাস্টমার থেকে প্রাপ্ত চেক ও সাপ্লায়ারকে দেওয়া চেকের স্থিতি ও ক্লিয়ারিং ট্র্যাক করুন</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('cheques.create') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                নতুন চেক এন্ট্রি
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-amber-400">হাতে থাকা চেক (In-Hand)</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['in_hand'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">{{ $stats['in_hand_count'] ?? 0 }} টি চেক বাকি আছে</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-indigo-400">ব্যাংকে জমা কৃত (Deposited)</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['deposited'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">ক্লিয়ারেন্সের অপেক্ষায়</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-emerald-400">পাস / অনার হয়েছে (Honored)</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['honored'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">টাকা সফলভাবে জমা হয়েছে</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-rose-400">বাউন্স / বাতিল (Bounced)</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['bounced'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-rose-400 mt-1">{{ $stats['bounced_count'] ?? 0 }} টি চেক বাউন্স করেছে</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card-shell p-4 flex flex-wrap gap-3 items-center justify-between">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('cheques.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request('status') && !request('type') ? 'bg-indigo-600 text-white font-semibold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">সব চেক</a>
            <a href="{{ route('cheques.index', ['status' => 'in_hand']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'in_hand' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">হাতে আছে (In Hand)</a>
            <a href="{{ route('cheques.index', ['status' => 'deposited']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'deposited' ? 'bg-indigo-500 text-white font-semibold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">জমা দেওয়া হয়েছে</a>
            <a href="{{ route('cheques.index', ['status' => 'honored']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'honored' ? 'bg-emerald-500 text-slate-950 font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">ক্লিয়ার হয়েছে</a>
            <a href="{{ route('cheques.index', ['status' => 'bounced']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'bounced' ? 'bg-rose-500 text-white font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">বাউন্স চেক</a>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('cheques.index', ['type' => 'received_from_customer']) }}" class="text-xs px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700/80 text-slate-300 hover:text-white transition {{ request('type') === 'received_from_customer' ? 'border-indigo-500 text-indigo-400' : '' }}">গ্রাহক থেকে প্রাপ্ত</a>
            <a href="{{ route('cheques.index', ['type' => 'issued_to_supplier']) }}" class="text-xs px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700/80 text-slate-300 hover:text-white transition {{ request('type') === 'issued_to_supplier' ? 'border-indigo-500 text-indigo-400' : '' }}">সাপ্লায়ারকে দেওয়া</a>
        </div>
    </div>

    <!-- Cheque List Table -->
    <div class="card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">চেক নং ও ব্যাংক</th>
                        <th class="p-3.5">পার্টি / গ্রাহক</th>
                        <th class="p-3.5">চেকের ধরন</th>
                        <th class="p-3.5">চেক তারিখ</th>
                        <th class="p-3.5 text-right">টাকার পরিমাণ</th>
                        <th class="p-3.5 text-center">বর্তমান স্ট্যাটাস</th>
                        <th class="p-3.5 text-right">স্ট্যাটাস পরিবর্তন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($cheques as $cheque)
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-3.5">
                            <span class="font-bold font-mono tnum text-white block">{{ $cheque->cheque_no }}</span>
                            <span class="text-[11px] text-slate-400 font-sans">{{ $cheque->bank_name }} {{ $cheque->branch_name ? "({$cheque->branch_name})" : '' }}</span>
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($cheque->customer)
                                <a href="{{ route('customers.show', $cheque->customer_id) }}" class="font-semibold text-indigo-400 hover:underline block">{{ $cheque->customer->name }}</a>
                                <span class="text-[10px] text-slate-500 font-mono">গ্রাহক ({{ $cheque->customer->phone }})</span>
                            @elseif($cheque->supplier)
                                <span class="font-semibold text-amber-400 block">{{ $cheque->supplier->name }}</span>
                                <span class="text-[10px] text-slate-500">সাপ্লায়ার / মিল</span>
                            @else
                                <span class="text-slate-500">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($cheque->type === 'received_from_customer')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                    কাস্টমার চেক
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                    সাপ্লায়ারকে প্রদান
                                </span>
                            @endif
                        </td>
                        <td class="p-3.5 text-slate-400">
                            {{ $cheque->cheque_date ? \Carbon\Carbon::parse($cheque->cheque_date)->format('d M, Y') : '-' }}
                        </td>
                        <td class="p-3.5 text-right font-bold font-mono tnum text-white text-sm">
                            ৳ {{ number_format($cheque->amount, 2) }}
                        </td>
                        <td class="p-3.5 text-center font-sans">
                            @if($cheque->status === 'in_hand')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-500/15 text-amber-400 border border-amber-500/30">হাতে আছে (In Hand)</span>
                            @elseif($cheque->status === 'deposited')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/15 text-indigo-400 border border-indigo-500/30">ব্যাংকে জমা</span>
                            @elseif($cheque->status === 'honored')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">পাস হয়েছে</span>
                            @elseif($cheque->status === 'bounced')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-500/15 text-rose-400 border border-rose-500/30">বাউন্স</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-700 text-slate-300">{{ $cheque->status }}</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <!-- Quick Status Update Form -->
                            <form action="{{ route('cheques.update-status', $cheque->id) }}" method="POST" class="inline-flex items-center gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700/80 text-[11px] rounded-lg px-2.5 py-1 text-slate-200 focus:outline-none focus:border-indigo-500">
                                    <option value="in_hand" {{ $cheque->status === 'in_hand' ? 'selected' : '' }}>হাতে আছে</option>
                                    <option value="deposited" {{ $cheque->status === 'deposited' ? 'selected' : '' }}>ব্যাংকে জমা দিন</option>
                                    <option value="honored" {{ $cheque->status === 'honored' ? 'selected' : '' }}>ক্লিয়ার (অনার)</option>
                                    <option value="bounced" {{ $cheque->status === 'bounced' ? 'selected' : '' }}>বাউন্স মার্ক করুন</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 font-sans">কোনো চেকের তথ্য পাওয়া যায়নি।</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cheques->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-900/60">
            {{ $cheques->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

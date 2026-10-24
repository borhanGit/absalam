@extends('layouts.app')

@section('page_title', 'ব্যাংক চেক ব্যবস্থাপনা (Cheque Management)')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions & Stats -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>🏦</span> চেক ট্র্যাকিং ও ব্যাংক ক্লিয়ারেন্স
            </h1>
            <p class="text-xs text-slate-400 mt-1">কাস্টমার থেকে প্রাপ্ত চেক ও সাপ্লায়ারকে দেওয়া চেকের স্থিতি ও ক্লিয়ারিং ট্র্যাক করুন</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('cheques.create') }}" class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-cyan-500/20 transition flex items-center gap-2">
                <span>➕</span> নতুন চেক এন্ট্রি
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-slate-900/80 border border-amber-500/20">
            <div class="text-xs font-medium text-amber-400">হাতে থাকা চেক (In-Hand)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['in_hand'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">{{ $stats['in_hand_count'] ?? 0 }} টি চেক বাকি আছে</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-blue-500/20">
            <div class="text-xs font-medium text-blue-400">ব্যাংকে জমা কৃত (Deposited)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['deposited'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">ক্লিয়ারেন্সের অপেক্ষায়</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-emerald-500/20">
            <div class="text-xs font-medium text-emerald-400">পাস / অনার হয়েছে (Honored)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['honored'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">টাকা সফলভাবে জমা হয়েছে</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-rose-500/20">
            <div class="text-xs font-medium text-rose-400">বাউন্স / বাতিল (Bounced)</div>
            <div class="text-xl font-bold text-white mt-1">৳ {{ number_format($stats['bounced'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-rose-400 mt-0.5">{{ $stats['bounced_count'] ?? 0 }} টি চেক বাউন্স করেছে</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-xl bg-slate-900/50 border border-slate-800 flex flex-wrap gap-3 items-center justify-between">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('cheques.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request('status') && !request('type') ? 'bg-cyan-500 text-slate-950 font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">সব চেক</a>
            <a href="{{ route('cheques.index', ['status' => 'in_hand']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'in_hand' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">হাতে আছে (In Hand)</a>
            <a href="{{ route('cheques.index', ['status' => 'deposited']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'deposited' ? 'bg-blue-500 text-slate-950 font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">জমা দেওয়া হয়েছে</a>
            <a href="{{ route('cheques.index', ['status' => 'honored']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'honored' ? 'bg-emerald-500 text-slate-950 font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">ক্লিয়ার হয়েছে</a>
            <a href="{{ route('cheques.index', ['status' => 'bounced']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'bounced' ? 'bg-rose-500 text-slate-950 font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">বাউন্স চেক</a>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('cheques.index', ['type' => 'received_from_customer']) }}" class="text-xs px-2.5 py-1 rounded bg-slate-800 border border-slate-700 text-slate-300 hover:text-white {{ request('type') === 'received_from_customer' ? 'border-cyan-500 text-cyan-400' : '' }}">গ্রাহক থেকে প্রাপ্ত</a>
            <a href="{{ route('cheques.index', ['type' => 'issued_to_supplier']) }}" class="text-xs px-2.5 py-1 rounded bg-slate-800 border border-slate-700 text-slate-300 hover:text-white {{ request('type') === 'issued_to_supplier' ? 'border-cyan-500 text-cyan-400' : '' }}">সাপ্লায়ারকে দেওয়া</a>
        </div>
    </div>

    <!-- Cheque List Table -->
    <div class="bg-slate-900/60 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 font-semibold uppercase text-[11px] border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">চেক নং ও ব্যাংক</th>
                        <th class="p-3.5">পার্টি / গ্রাহক</th>
                        <th class="p-3.5">চেকের ধরন</th>
                        <th class="p-3.5">চেক তারিখ</th>
                        <th class="p-3.5 text-right">টাকার পরিমাণ</th>
                        <th class="p-3.5 text-center">বর্তমান স্ট্যাটাস</th>
                        <th class="p-3.5 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($cheques as $cheque)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="p-3.5">
                            <span class="font-bold text-white block">{{ $cheque->cheque_no }}</span>
                            <span class="text-[11px] text-slate-400 font-sans">{{ $cheque->bank_name }} {{ $cheque->branch_name ? "({$cheque->branch_name})" : '' }}</span>
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($cheque->customer)
                                <a href="{{ route('customers.show', $cheque->customer_id) }}" class="font-semibold text-cyan-400 hover:underline block">{{ $cheque->customer->name }}</a>
                                <span class="text-[10px] text-slate-500">গ্রাহক ({{ $cheque->customer->phone }})</span>
                            @elseif($cheque->supplier)
                                <span class="font-semibold text-amber-400 block">{{ $cheque->supplier->name }}</span>
                                <span class="text-[10px] text-slate-500">সাপ্লায়ার / মিল</span>
                            @else
                                <span class="text-slate-500">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($cheque->type === 'received_from_customer')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                    📥 কাস্টমার চেক
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    📤 সাপ্লায়ারকে প্রদান
                                </span>
                            @endif
                        </td>
                        <td class="p-3.5">
                            {{ $cheque->cheque_date ? \Carbon\Carbon::parse($cheque->cheque_date)->format('d M, Y') : '-' }}
                        </td>
                        <td class="p-3.5 text-right font-bold text-white">
                            ৳ {{ number_format($cheque->amount, 2) }}
                        </td>
                        <td class="p-3.5 text-center font-sans">
                            @if($cheque->status === 'in_hand')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/15 text-amber-400 border border-amber-500/30">হাতে আছে (In Hand)</span>
                            @elseif($cheque->status === 'deposited')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-500/15 text-blue-400 border border-blue-500/30">ব্যাংকে জমা</span>
                            @elseif($cheque->status === 'honored')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">পাস হয়েছে ✓</span>
                            @elseif($cheque->status === 'bounced')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/15 text-rose-400 border border-rose-500/30">বাউন্স ✖</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-700 text-slate-300">{{ $cheque->status }}</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <!-- Quick Status Update Form -->
                            <form action="{{ route('cheques.update-status', $cheque->id) }}" method="POST" class="inline-flex items-center gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="bg-slate-800 border border-slate-700 text-[11px] rounded-lg px-2 py-1 text-slate-200 focus:outline-none focus:border-cyan-500">
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
        <div class="p-3 border-t border-slate-800">
            {{ $cheques->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

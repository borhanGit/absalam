@extends('layouts.app')

@section('title', 'কাস্টমার ও থাই মিস্ত্রি')
@section('page_title', '👥 কাস্টমার ও থাই মিস্ত্রি তালিকা (Parties CRM)')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">মোট কাস্টমার সংখ্যা</span>
            <div class="text-xl font-black text-white font-mono-numbers mt-1">{{ $customers->total() }} জন</div>
        </div>
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">নিবন্ধিত থাই মিস্ত্রি / কন্ট্রাক্টর</span>
            <div class="text-xl font-black text-cyan-400 font-mono-numbers mt-1">{{ $mistriCount }} জন</div>
        </div>
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">কাস্টমারদের নিকট মোট পাওনা (বাকি)</span>
            <div class="text-xl font-black text-amber-400 font-mono-numbers mt-1">৳{{ number_format($totalDue, 2) }}</div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম বা মোবাইল নম্বর খুঁজুন..." class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-cyan-500 w-64">
            
            <select name="customer_type" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500">
                <option value="">সকল ধরন</option>
                <option value="contractor_mistri" {{ request('customer_type') === 'contractor_mistri' ? 'selected' : '' }}>থাই মিস্ত্রি (Mistri)</option>
                <option value="builder" {{ request('customer_type') === 'builder' ? 'selected' : '' }}>রিয়েল এস্টেট / বিল্ডার</option>
                <option value="retail" {{ request('customer_type') === 'retail' ? 'selected' : '' }}>খুচরা ক্রেতা (Retail)</option>
                <option value="wholesale" {{ request('customer_type') === 'wholesale' ? 'selected' : '' }}>পাইকারি (Wholesale)</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition">
                ফিল্টার
            </button>
        </form>

        <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/20 transition">
            <span>➕</span> নতুন কাস্টমার / মিস্ত্রি যুক্ত করুন
        </a>
    </div>

    <!-- Customers Table -->
    <div class="rounded-2xl border border-slate-800 glass-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="p-3">নাম</th>
                        <th class="p-3">ধরন (Type)</th>
                        <th class="p-3">মোবাইল নম্বর</th>
                        <th class="p-3">ঠিকানা ও এরিয়া</th>
                        <th class="p-3 text-right">বাকি লিমিট (Credit Limit)</th>
                        <th class="p-3 text-right">বর্তমান বাকি (Due)</th>
                        <th class="p-3 text-center">লেজার খাতা</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="p-3 font-bold text-white">
                                <a href="{{ route('customers.show', $c->id) }}" class="hover:underline hover:text-cyan-400">{{ $c->name }}</a>
                            </td>
                            <td class="p-3">
                                @if($c->customer_type === 'contractor_mistri')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">থাই মিস্ত্রি</span>
                                @elseif($c->customer_type === 'builder')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">বিল্ডার / ডেভেলপার</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-slate-800 text-slate-300">{{ ucfirst($c->customer_type) }}</span>
                                @endif
                            </td>
                            <td class="p-3 font-mono">{{ $c->phone }}</td>
                            <td class="p-3 text-slate-400">{{ $c->address ?? '-' }}</td>
                            <td class="p-3 text-right font-mono-numbers text-slate-400">
                                ৳{{ number_format($c->credit_limit, 2) }}
                            </td>
                            <td class="p-3 text-right font-mono-numbers font-bold {{ $c->current_balance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                                ৳{{ number_format($c->current_balance, 2) }}
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('customers.show', $c->id) }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-cyan-400 font-semibold text-[11px] transition">
                                    লেজার দেখুন ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500">
                                কোনো কাস্টমার বা মিস্ত্রি পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/60">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
